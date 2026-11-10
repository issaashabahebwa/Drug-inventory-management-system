<?php

namespace App\Http\Controllers;

use App\Models\Drug;
use App\Models\ProcurementRequest;
use App\Models\ProcurementRequestItem;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    // ── Supplier Directory ─────────────────────────────────────────────
    public function index()
    {
        $suppliers = Supplier::withCount('stockBatches')->orderBy('name')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['nullable', 'string', 'max:50', 'unique:suppliers,code'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'email'          => ['nullable', 'email', 'max:255'],
            'address'        => ['nullable', 'string'],
        ]);

        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier '{$data['name']}' added successfully.");
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['nullable', 'string', 'max:50', "unique:suppliers,code,{$supplier->id}"],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:50'],
            'email'          => ['nullable', 'email', 'max:255'],
            'address'        => ['nullable', 'string'],
            'is_active'      => ['boolean'],
        ]);

        $supplier->update($data);

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier '{$supplier->name}' updated successfully.");
    }

    // ── Procurement Requests ───────────────────────────────────────────
    public function procurementIndex()
    {
        $requests = ProcurementRequest::with(['requestedBy', 'supplier', 'items'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('procurement.index', compact('requests'));
    }

    public function procurementCreate()
    {
        // Auto-populate with low-stock drugs
        $lowStockDrugs = Drug::where('is_active', true)
            ->whereRaw('total_quantity <= reorder_level')
            ->orderBy('name')
            ->get();

        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('procurement.create', compact('lowStockDrugs', 'suppliers'));
    }

    public function procurementStore(Request $request)
    {
        $data = $request->validate([
            'supplier_id'      => ['nullable', 'exists:suppliers,id'],
            'notes'            => ['nullable', 'string'],
            'items'            => ['required', 'array', 'min:1'],
            'items.*.drug_id'  => ['required', 'exists:drugs,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $pr = ProcurementRequest::create([
            'request_number' => ProcurementRequest::generateRequestNumber(),
            'requested_by'   => auth()->id(),
            'supplier_id'    => $data['supplier_id'] ?? null,
            'status'         => 'draft',
            'request_date'   => today(),
            'notes'          => $data['notes'] ?? null,
        ]);

        foreach ($data['items'] as $item) {
            $drug = Drug::find($item['drug_id']);
            ProcurementRequestItem::create([
                'procurement_request_id' => $pr->id,
                'drug_id'                => $drug->id,
                'quantity_requested'     => $item['quantity'],
                'current_stock'          => $drug->total_quantity,
                'reorder_level'          => $drug->reorder_level,
            ]);
        }

        return redirect()->route('procurement.show', $pr)
            ->with('success', "Procurement request {$pr->request_number} created successfully.");
    }

    public function procurementShow(ProcurementRequest $procurementRequest)
    {
        $procurementRequest->load(['requestedBy', 'supplier', 'items.drug']);
        return view('procurement.show', compact('procurementRequest'));
    }

    public function procurementUpdateStatus(Request $request, ProcurementRequest $procurementRequest)
    {
        $data = $request->validate([
            'status' => ['required', 'in:draft,submitted,approved,fulfilled'],
        ]);

        $procurementRequest->update($data);

        return redirect()->route('procurement.show', $procurementRequest)
            ->with('success', "Request status updated to: " . ucfirst($data['status']));
    }
}
