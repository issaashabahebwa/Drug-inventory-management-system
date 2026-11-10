<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\StockBatch;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StockBatchController extends Controller
{
    public function index(Request $request)
    {
        $query = StockBatch::with(['drug', 'supplier', 'receivedBy']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('batch_number', 'like', "%{$search}%")
                  ->orWhereHas('drug', fn($d) => $d->where('name', 'like', "%{$search}%"));
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($expiry = $request->input('expiry_filter')) {
            $today = Carbon::today();
            match ($expiry) {
                'expired'   => $query->where('expiry_date', '<', $today),
                'critical'  => $query->whereBetween('expiry_date', [$today, $today->copy()->addDays(30)]),
                'warning'   => $query->whereBetween('expiry_date', [$today->copy()->addDays(31), $today->copy()->addDays(60)]),
                'soon'      => $query->whereBetween('expiry_date', [$today->copy()->addDays(61), $today->copy()->addDays(90)]),
                default     => null,
            };
        }

        $batches = $query->orderBy('expiry_date')->paginate(20)->withQueryString();

        return view('stock.index', compact('batches'));
    }

    public function create(Request $request)
    {
        $drugs     = Drug::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        $selectedDrug = $request->input('drug_id') ? Drug::find($request->input('drug_id')) : null;

        return view('stock.create', compact('drugs', 'suppliers', 'selectedDrug'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'drug_id'              => ['required', 'exists:drugs,id'],
            'supplier_id'          => ['nullable', 'exists:suppliers,id'],
            'batch_number'         => ['required', 'string', 'max:100'],
            'quantity_received'    => ['required', 'integer', 'min:1'],
            'expiry_date'          => ['required', 'date', 'after:today'],
            'manufacture_date'     => ['nullable', 'date', 'before:today'],
            'received_date'        => ['required', 'date', 'before_or_equal:today'],
            'delivery_note_number' => ['nullable', 'string', 'max:100'],
            'unit_cost'            => ['nullable', 'numeric', 'min:0'],
            'notes'                => ['nullable', 'string'],
        ]);

        $data['quantity_remaining'] = $data['quantity_received'];
        $data['received_by']        = auth()->id();
        $data['status']             = 'active';

        $batch = StockBatch::create($data);

        // Update drug total
        $batch->drug->recalculateTotalQuantity();

        return redirect()->route('stock.index')
            ->with('success', "Batch {$batch->batch_number} for {$batch->drug->name} received successfully.");
    }

    public function show(StockBatch $stockBatch)
    {
        $stockBatch->load(['drug', 'supplier', 'receivedBy', 'dispensingRecords.dispensedBy']);
        return view('stock.show', compact('stockBatch'));
    }

    public function edit(StockBatch $stockBatch)
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();
        return view('stock.edit', compact('stockBatch', 'suppliers'));
    }

    public function update(Request $request, StockBatch $stockBatch)
    {
        $data = $request->validate([
            'delivery_note_number' => ['nullable', 'string', 'max:100'],
            'unit_cost'            => ['nullable', 'numeric', 'min:0'],
            'status'               => ['required', 'in:active,depleted,expired,quarantined'],
            'notes'                => ['nullable', 'string'],
        ]);

        $stockBatch->update($data);
        $stockBatch->drug->recalculateTotalQuantity();

        return redirect()->route('stock.show', $stockBatch)
            ->with('success', 'Batch details updated successfully.');
    }
}
