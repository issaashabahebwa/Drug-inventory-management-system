<?php

namespace App\Http\Controllers;

use App\Models\DispensingRecord;
use App\Models\Drug;
use App\Models\StockBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DispensingController extends Controller
{
    public function index(Request $request)
    {
        $query = DispensingRecord::with(['drug', 'stockBatch', 'dispensedBy']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('patient_reference', 'like', "%{$search}%")
                  ->orWhere('patient_name', 'like', "%{$search}%")
                  ->orWhereHas('drug', fn($d) => $d->where('name', 'like', "%{$search}%"));
            });
        }

        if ($from = $request->input('date_from')) {
            $query->where('dispensed_date', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->where('dispensed_date', '<=', $to);
        }

        if ($drugId = $request->input('drug_id')) {
            $query->where('drug_id', $drugId);
        }

        $records = $query->orderByDesc('dispensed_date')
                         ->orderByDesc('created_at')
                         ->paginate(25)
                         ->withQueryString();

        $drugs   = Drug::where('is_active', true)->orderBy('name')->get();

        return view('dispensing.index', compact('records', 'drugs'));
    }

    public function create(Request $request)
    {
        $drugs = Drug::where('is_active', true)
                     ->where('total_quantity', '>', 0)
                     ->orderBy('name')
                     ->get();

        $selectedDrug = null;
        $fefoBatch    = null;

        if ($drugId = $request->input('drug_id')) {
            $selectedDrug = Drug::find($drugId);
            $fefoBatch    = $selectedDrug?->next_fefo_batch;
        }

        return view('dispensing.create', compact('drugs', 'selectedDrug', 'fefoBatch'));
    }

    /**
     * AJAX endpoint: returns available batches (FEFO order) for a given drug.
     */
    public function getBatches(Drug $drug)
    {
        $batches = $drug->activeBatches()
                        ->get()
                        ->map(fn($b) => [
                            'id'                 => $b->id,
                            'batch_number'       => $b->batch_number,
                            'expiry_date'        => $b->expiry_date->format('d/m/Y'),
                            'quantity_remaining' => $b->quantity_remaining,
                            'days_to_expiry'     => $b->days_to_expiry,
                            'expiry_color'       => $b->expiry_color,
                        ]);

        return response()->json($batches);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'drug_id'           => ['required', 'exists:drugs,id'],
            'stock_batch_id'    => ['required', 'exists:stock_batches,id'],
            'patient_reference' => ['required', 'string', 'max:100'],
            'patient_name'      => ['nullable', 'string', 'max:255'],
            'quantity_dispensed'=> ['required', 'integer', 'min:1'],
            'dispensed_date'    => ['required', 'date', 'before_or_equal:today'],
            'diagnosis'         => ['nullable', 'string', 'max:255'],
            'notes'             => ['nullable', 'string'],
        ]);

        $batch = StockBatch::findOrFail($data['stock_batch_id']);

        // Validate sufficient stock in batch
        if ($data['quantity_dispensed'] > $batch->quantity_remaining) {
            return back()->withErrors([
                'quantity_dispensed' => "Insufficient stock in this batch. Available: {$batch->quantity_remaining}.",
            ])->withInput();
        }

        DB::transaction(function () use ($data, $batch) {
            // Create dispensing record
            DispensingRecord::create(array_merge($data, [
                'dispensed_by'   => auth()->id(),
                'dispensed_time' => now()->format('H:i:s'),
            ]));

            // Deduct from batch
            $newQty = $batch->quantity_remaining - $data['quantity_dispensed'];
            $batch->update([
                'quantity_remaining' => $newQty,
                'status'             => $newQty <= 0 ? 'depleted' : 'active',
            ]);

            // Recalculate drug total
            $batch->drug->recalculateTotalQuantity();
        });

        return redirect()->route('dispensing.create')
            ->with('success', "Dispensing recorded: {$data['quantity_dispensed']} unit(s) of {$batch->drug->name} issued to {$data['patient_reference']}.");
    }

    public function show(DispensingRecord $dispensingRecord)
    {
        $dispensingRecord->load(['drug', 'stockBatch.supplier', 'dispensedBy']);
        return view('dispensing.show', compact('dispensingRecord'));
    }
}
