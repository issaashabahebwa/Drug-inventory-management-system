@extends('layouts.app')
@section('title', 'Receive Stock')
@section('page-title', 'Receive Stock')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('stock.index') }}">Stock Batches</a>
    <i class="bi bi-chevron-right"></i>
    Receive Stock
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Receive Stock</h2>
        <p>Record a new drug batch received from a supplier.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Batches
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card animate-fade-up">
            <div class="card-header">
                <div class="card-header-icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-box-arrow-in-down"></i>
                </div>
                Stock Receipt Form
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <p class="form-section-title">Drug &amp; Supplier</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Drug <span class="text-danger">*</span></label>
                            <select name="drug_id"
                                    class="form-select @error('drug_id') is-invalid @enderror" required>
                                <option value="">— Select drug —</option>
                                @foreach($drugs as $drug)
                                <option value="{{ $drug->id }}"
                                    {{ old('drug_id', $selectedDrug?->id) == $drug->id ? 'selected' : '' }}>
                                    {{ $drug->name }}
                                    @if($drug->strength)({{ $drug->strength }})@endif
                                </option>
                                @endforeach
                            </select>
                            @error('drug_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Supplier</label>
                            <select name="supplier_id"
                                    class="form-select @error('supplier_id') is-invalid @enderror">
                                <option value="">— Select supplier (optional) —</option>
                                @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                    @if($supplier->code)({{ $supplier->code }})@endif
                                </option>
                                @endforeach
                            </select>
                            @error('supplier_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <p class="form-section-title">Batch Details</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Batch Number <span class="text-danger">*</span></label>
                            <input type="text" name="batch_number"
                                   class="form-control @error('batch_number') is-invalid @enderror"
                                   value="{{ old('batch_number') }}"
                                   placeholder="e.g. BN-2025-001" required>
                            @error('batch_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Delivery Note Number</label>
                            <input type="text" name="delivery_note_number"
                                   class="form-control @error('delivery_note_number') is-invalid @enderror"
                                   value="{{ old('delivery_note_number') }}"
                                   placeholder="e.g. DN-2025-0120">
                            @error('delivery_note_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Quantity Received <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="quantity_received"
                                       class="form-control @error('quantity_received') is-invalid @enderror"
                                       value="{{ old('quantity_received') }}"
                                       min="1" placeholder="0" required>
                                <span class="input-group-text">units</span>
                            </div>
                            @error('quantity_received')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Unit Cost (UGX)</label>
                            <div class="input-group">
                                <span class="input-group-text">UGX</span>
                                <input type="number" name="unit_cost"
                                       class="form-control @error('unit_cost') is-invalid @enderror"
                                       value="{{ old('unit_cost') }}"
                                       min="0" step="0.01" placeholder="0.00">
                            </div>
                            @error('unit_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Date Received <span class="text-danger">*</span></label>
                            <input type="date" name="received_date"
                                   class="form-control @error('received_date') is-invalid @enderror"
                                   value="{{ old('received_date', today()->toDateString()) }}"
                                   max="{{ today()->toDateString() }}" required>
                            @error('received_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <p class="form-section-title">Manufacturing &amp; Expiry</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Manufacture Date</label>
                            <input type="date" name="manufacture_date"
                                   class="form-control @error('manufacture_date') is-invalid @enderror"
                                   value="{{ old('manufacture_date') }}">
                            @error('manufacture_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Expiry Date <span class="text-danger">*</span></label>
                            <input type="date" name="expiry_date"
                                   class="form-control @error('expiry_date') is-invalid @enderror"
                                   value="{{ old('expiry_date') }}"
                                   min="{{ today()->addDay()->toDateString() }}" required>
                            @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="fs-xs text-muted mt-1" id="expiryPreview"></div>
                        </div>
                    </div>

                    <p class="form-section-title">Notes</p>

                    <div class="mb-4">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" rows="3"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Condition on receipt, storage instructions…">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-kiswa">
                            <i class="bi bi-box-arrow-in-down me-1"></i>Record Receipt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        {{-- Selected drug info --}}
        @if($selectedDrug)
        <div class="card animate-fade-up stagger-1 mb-3">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-capsule"></i>
                </div>
                Selected Drug
            </div>
            <div class="card-body">
                <div class="fw-700 fs-sm mb-1">{{ $selectedDrug->name }}</div>
                <div class="fs-xs text-muted mb-3">{{ ucfirst($selectedDrug->formulation) }}
                    @if($selectedDrug->strength)· {{ $selectedDrug->strength }}@endif
                </div>
                <ul class="info-list">
                    <li>
                        <span class="info-key">Current Stock</span>
                        <span class="info-val fw-700 {{ $selectedDrug->isLowStock() ? 'text-warning' : 'text-success' }}">
                            {{ number_format($selectedDrug->total_quantity) }} {{ $selectedDrug->unit_of_measure }}
                        </span>
                    </li>
                    <li>
                        <span class="info-key">Reorder Level</span>
                        <span class="info-val">{{ number_format($selectedDrug->reorder_level) }}</span>
                    </li>
                    <li>
                        <span class="info-key">Stock Status</span>
                        @php $sm=['adequate'=>'green','moderate'=>'teal','low'=>'amber','out_of_stock'=>'red']; @endphp
                        <span class="status-pill {{ $sm[$selectedDrug->stock_status] ?? 'gray' }}">
                            {{ $selectedDrug->stock_status_label }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        @endif

        <div class="card animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="bi bi-info-circle"></i>
                </div>
                Receiving Guide
            </div>
            <div class="card-body fs-sm">
                <ul class="text-secondary ps-3 mb-0" style="line-height:1.9;">
                    <li>Verify the batch number against the physical label.</li>
                    <li>Always enter the <strong>expiry date</strong> from the packaging.</li>
                    <li>Record the delivery note number for audit trails.</li>
                    <li>Stock balance updates automatically after saving.</li>
                    <li>FEFO ordering ensures the oldest batches are dispensed first.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const expiryInput   = document.querySelector('[name="expiry_date"]');
    const expiryPreview = document.getElementById('expiryPreview');

    if (expiryInput && expiryPreview) {
        expiryInput.addEventListener('change', function () {
            const val = this.value;
            if (!val) { expiryPreview.innerHTML = ''; return; }
            const exp   = new Date(val);
            const today = new Date();
            today.setHours(0,0,0,0);
            const diff  = Math.round((exp - today) / (1000 * 60 * 60 * 24));
            let cls = 'text-success', icon = 'bi-check-circle';
            if (diff <= 30)  { cls = 'text-danger';  icon = 'bi-exclamation-circle-fill'; }
            else if (diff <= 90) { cls = 'text-warning'; icon = 'bi-exclamation-triangle-fill'; }
            expiryPreview.innerHTML =
                `<i class="bi ${icon} me-1"></i><span class="${cls}">` +
                `Expires in <strong>${diff}</strong> day${diff !== 1 ? 's' : ''}</span>`;
        });
    }
});
</script>
@endpush
