@extends('layouts.app')
@section('title', 'New Requisition')
@section('page-title', 'New Procurement Requisition')

@section('content')
<div class="row">
<div class="col-12 col-xl-9">
<div class="mb-3"><a href="{{ route('procurement.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>

<div class="card">
    <div class="card-header"><i class="bi bi-cart-plus me-2 text-kiswa"></i>Drug Requisition / Purchase Request Form</div>
    <div class="card-body">
        <form method="POST" action="{{ route('procurement.store') }}" class="needs-validation" novalidate>
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-12 col-md-6">
                    <label class="form-label">Preferred Supplier</label>
                    <select name="supplier_id" class="form-select">
                        <option value="">— Not specified —</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected':'' }}>{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Notes / Justification</label>
                    <input type="text" name="notes" class="form-control" value="{{ old('notes') }}"
                           placeholder="e.g. Urgent — high patient load">
                </div>
            </div>

            <hr class="my-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Drug Items to Request</h6>
                <button type="button" id="addProcurementItem" class="btn btn-sm btn-outline-kiswa btn-outline-secondary">
                    <i class="bi bi-plus me-1"></i>Add Item
                </button>
            </div>

            <div id="procurementItems">

                {{-- Pre-populate with low-stock drugs --}}
                @foreach($lowStockDrugs as $i => $drug)
                <div class="procurement-row card mb-2">
                    <div class="card-body py-2">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-5">
                                <label class="form-label fs-xs">Drug</label>
                                <select name="items[{{ $i }}][drug_id]" class="form-select form-select-sm" required>
                                    <option value="">— select —</option>
                                    @foreach($lowStockDrugs as $d)
                                    <option value="{{ $d->id }}" {{ $d->id == $drug->id ? 'selected':'' }}>
                                        {{ $d->name }} ({{ $d->strength }}) — {{ $d->total_quantity }} in stock
                                    </option>
                                    @endforeach
                                    @foreach(\App\Models\Drug::where('is_active',true)->whereNotIn('id', $lowStockDrugs->pluck('id'))->orderBy('name')->get() as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8 col-md-4">
                                <label class="form-label fs-xs">Quantity Requested</label>
                                <input type="number" name="items[{{ $i }}][quantity]"
                                       class="form-control form-control-sm"
                                       value="{{ max($drug->reorder_level * 2 - $drug->total_quantity, $drug->reorder_level) }}"
                                       min="1" required>
                            </div>
                            <div class="col-4 col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn w-100">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                        <div class="mt-1">
                            <span class="badge badge-{{ $drug->stock_status == 'out_of_stock' ? 'out' : 'low' }} fs-xs">
                                Current stock: {{ $drug->total_quantity }} | Reorder level: {{ $drug->reorder_level }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($lowStockDrugs->isEmpty())
                <div class="text-center text-muted py-3 fs-sm" id="emptyMsg">
                    <i class="bi bi-info-circle me-1"></i>All drugs are adequately stocked.
                    Click "Add Item" to manually add a drug to this requisition.
                </div>
                @endif
            </div>

            {{-- Hidden template for JS row addition --}}
            <template id="procurementRowTemplate">
                <div class="procurement-row card mb-2">
                    <div class="card-body py-2">
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md-5">
                                <label class="form-label fs-xs">Drug</label>
                                <select name="items[__IDX__][drug_id]" class="form-select form-select-sm" required>
                                    <option value="">— select —</option>
                                    @foreach(\App\Models\Drug::where('is_active',true)->orderBy('name')->get() as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->strength }}) — {{ $d->total_quantity }} in stock</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-8 col-md-4">
                                <label class="form-label fs-xs">Quantity Requested</label>
                                <input type="number" name="items[__IDX__][quantity]"
                                       class="form-control form-control-sm" min="1" required>
                            </div>
                            <div class="col-4 col-md-3 d-flex align-items-end">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn w-100">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <hr class="my-4">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('procurement.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-kiswa">
                    <i class="bi bi-send me-1"></i>Submit Requisition
                </button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
