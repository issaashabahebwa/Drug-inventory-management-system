@extends('layouts.app')
@section('title', 'Dispense Drug')
@section('page-title', 'Dispense Drug')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('dispensing.index') }}">Dispensing Log</a>
    <i class="bi bi-chevron-right"></i>
    New Dispense
@endsection

@push('styles')
<style>
.batch-card {
    border: 2px solid var(--border-color);
    border-radius: 10px;
    padding: .9rem 1rem;
    cursor: pointer;
    transition: all .2s;
    background: var(--bg-card);
}
.batch-card:hover { border-color: var(--k-green); background: var(--k-green-xlight); }
.batch-card.selected { border-color: var(--k-green); background: var(--k-green-xlight); box-shadow: 0 0 0 3px rgba(22,163,74,.12); }
.batch-card-top { display: flex; justify-content: space-between; align-items: center; }
.batch-num { font-weight: 700; font-size: .84rem; color: var(--k-green); }
.batch-qty { font-weight: 800; font-size: 1rem; color: var(--text-primary); }
.batch-qty-label { font-size: .62rem; color: var(--text-muted); }
.batch-expiry { margin-top: .4rem; display: flex; align-items: center; gap: .5rem; font-size: .72rem; }

.qty-stepper {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    overflow: hidden;
}
.qty-stepper button {
    width: 42px; height: 42px;
    background: var(--bg-muted);
    border: none;
    font-size: 1.1rem;
    color: var(--text-secondary);
    cursor: pointer;
    transition: background .15s;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.qty-stepper button:hover { background: var(--k-green-light); color: var(--k-green); }
.qty-stepper input {
    flex: 1;
    text-align: center;
    border: none;
    border-left: 1px solid var(--border-color);
    border-right: 1px solid var(--border-color);
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
    padding: 0;
    min-width: 0;
    height: 42px;
    outline: none;
    background: var(--bg-card);
}
.qty-stepper input:focus { background: var(--k-green-xlight); }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Dispense Drug</h2>
        <p>Record a drug dispensing transaction using FEFO batch selection.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('dispensing.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-journal-text me-1"></i>Dispensing Log
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card animate-fade-up">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
                Dispensing Entry Form
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('dispensing.store') }}" id="dispenseForm" class="needs-validation" novalidate>
                    @csrf

                    <p class="form-section-title">Patient Information</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">OPD / Prescription Reference <span class="text-danger">*</span></label>
                            <input type="text" name="patient_reference"
                                   class="form-control @error('patient_reference') is-invalid @enderror"
                                   value="{{ old('patient_reference') }}"
                                   placeholder="e.g. OPD-2025-10051" required>
                            @error('patient_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Patient Name <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" name="patient_name"
                                   class="form-control @error('patient_name') is-invalid @enderror"
                                   value="{{ old('patient_name') }}" placeholder="For record keeping only">
                            @error('patient_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Diagnosis / Indication <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" name="diagnosis"
                                   class="form-control @error('diagnosis') is-invalid @enderror"
                                   value="{{ old('diagnosis') }}" placeholder="e.g. Malaria, UTI, Hypertension">
                            @error('diagnosis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Dispensing Date <span class="text-danger">*</span></label>
                            <input type="date" name="dispensed_date"
                                   class="form-control @error('dispensed_date') is-invalid @enderror"
                                   value="{{ old('dispensed_date', today()->toDateString()) }}"
                                   max="{{ today()->toDateString() }}" required>
                            @error('dispensed_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <p class="form-section-title">Drug &amp; Batch Selection</p>

                    <div class="mb-3">
                        <label class="form-label">Drug <span class="text-danger">*</span></label>
                        <div class="search-input-wrap mb-1">
                            <i class="bi bi-capsule"></i>
                            <select id="drug_id" name="drug_id"
                                    class="form-select @error('drug_id') is-invalid @enderror" required>
                                <option value="">— Select drug to dispense —</option>
                                @foreach($drugs as $drug)
                                <option value="{{ $drug->id }}"
                                    {{ old('drug_id', $selectedDrug?->id) == $drug->id ? 'selected' : '' }}>
                                    {{ $drug->name }}
                                    @if($drug->strength) ({{ $drug->strength }}) @endif
                                    — {{ number_format($drug->total_quantity) }} {{ $drug->unit_of_measure }} in stock
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @error('drug_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    {{-- Batch selector (populated by JS) --}}
                    <div class="mb-3" id="batchSection" style="{{ $fefoBatch ? '' : 'display:none;' }}">
                        <label class="form-label">Select Batch <span class="text-danger">*</span></label>
                        <div id="batchCards" class="d-flex flex-column gap-2">
                            @if($fefoBatch)
                            <div class="batch-card selected" onclick="selectBatch(this, {{ $fefoBatch->id }}, {{ $fefoBatch->quantity_remaining }})">
                                <div class="batch-card-top">
                                    <span class="batch-num">{{ $fefoBatch->batch_number }}</span>
                                    <div class="text-end">
                                        <div class="batch-qty">{{ number_format($fefoBatch->quantity_remaining) }}</div>
                                        <div class="batch-qty-label">units available</div>
                                    </div>
                                </div>
                                <div class="batch-expiry">
                                    <span class="badge badge-{{ $fefoBatch->expiry_status }}">
                                        {{ $fefoBatch->days_to_expiry }}d left
                                    </span>
                                    <span class="text-muted">Expires {{ $fefoBatch->expiry_date->format('d M Y') }}</span>
                                    <span class="badge" style="background:#f0fdf4;color:#166534;font-size:.62rem;">FEFO</span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <input type="hidden" id="stock_batch_id" name="stock_batch_id"
                               value="{{ old('stock_batch_id', $fefoBatch?->id) }}">
                        @error('stock_batch_id')<div class="text-danger fs-xs mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div id="batchLoadingMsg" class="text-muted fs-sm py-2" style="display:none;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Loading available batches…
                    </div>

                    {{-- Quantity --}}
                    <div class="mb-3" id="qtySection" style="{{ $fefoBatch ? '' : 'display:none;' }}">
                        <label class="form-label">Quantity to Dispense <span class="text-danger">*</span></label>
                        <div class="qty-stepper" style="max-width:200px;">
                            <button type="button" onclick="adjustQty(-1)">−</button>
                            <input type="number" id="quantity_dispensed" name="quantity_dispensed"
                                   class="@error('quantity_dispensed') is-invalid @enderror"
                                   value="{{ old('quantity_dispensed', 1) }}"
                                   min="1" max="{{ $fefoBatch?->quantity_remaining ?? '' }}" required>
                            <button type="button" onclick="adjustQty(1)">+</button>
                        </div>
                        <div id="qtyFeedback" class="fs-xs mt-1 text-muted"></div>
                        @error('quantity_dispensed')<div class="text-danger fs-xs mt-1">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Notes <span class="text-muted fw-normal">(optional)</span></label>
                        <input type="text" name="notes" class="form-control"
                               value="{{ old('notes') }}" placeholder="Special instructions or remarks…">
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('dispensing.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-kiswa" id="submitBtn">
                            <i class="bi bi-clipboard2-check me-1"></i>Record Dispensing
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Info panel --}}
    <div class="col-12 col-lg-5">
        {{-- Selected drug summary --}}
        <div id="drugSummaryCard" class="{{ $selectedDrug ? '' : 'd-none' }} animate-fade-up stagger-1 mb-3">
            <div class="card">
                <div class="card-header">
                    <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                        <i class="bi bi-capsule"></i>
                    </div>
                    <span id="drugSummaryName">{{ $selectedDrug?->name }}</span>
                </div>
                <div class="card-body p-0">
                    <ul class="info-list px-3" id="drugSummaryList">
                        @if($selectedDrug)
                        <li>
                            <span class="info-key">Total in Stock</span>
                            <span class="info-val fw-700 {{ $selectedDrug->isLowStock() ? 'text-warning' : 'text-success' }}">
                                {{ number_format($selectedDrug->total_quantity) }} {{ $selectedDrug->unit_of_measure }}
                            </span>
                        </li>
                        <li>
                            <span class="info-key">Stock Status</span>
                            @php $sm=['adequate'=>'green','moderate'=>'teal','low'=>'amber','out_of_stock'=>'red']; @endphp
                            <span class="status-pill {{ $sm[$selectedDrug->stock_status] ?? 'gray' }}">
                                {{ $selectedDrug->stock_status_label }}
                            </span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <div class="card animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#ede9fe;color:#7c3aed;">
                    <i class="bi bi-info-circle"></i>
                </div>
                FEFO Dispensing Guide
            </div>
            <div class="card-body fs-sm">
                <div class="d-flex flex-column gap-3">
                    <div class="d-flex gap-3">
                        <div style="width:32px;height:32px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#16a34a;">
                            <i class="bi bi-1-circle-fill"></i>
                        </div>
                        <div>
                            <div class="fw-600 fs-sm">Select the drug</div>
                            <div class="text-muted" style="font-size:.74rem;">Available batches load automatically in FEFO order.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div style="width:32px;height:32px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#2563eb;">
                            <i class="bi bi-2-circle-fill"></i>
                        </div>
                        <div>
                            <div class="fw-600 fs-sm">Batch is auto-selected</div>
                            <div class="text-muted" style="font-size:.74rem;">The nearest expiry batch is pre-selected to minimise wastage.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div style="width:32px;height:32px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#d97706;">
                            <i class="bi bi-3-circle-fill"></i>
                        </div>
                        <div>
                            <div class="fw-600 fs-sm">Set quantity</div>
                            <div class="text-muted" style="font-size:.74rem;">Cannot exceed available units in the selected batch.</div>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <div style="width:32px;height:32px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#dc2626;">
                            <i class="bi bi-4-circle-fill"></i>
                        </div>
                        <div>
                            <div class="fw-600 fs-sm">Submit</div>
                            <div class="text-muted" style="font-size:.74rem;">Stock deducts instantly and the record is saved permanently.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let selectedBatchId  = {{ $fefoBatch ? $fefoBatch->id : 'null' }};
let selectedBatchQty = {{ $fefoBatch ? $fefoBatch->quantity_remaining : 0 }};

function selectBatch(el, id, qty) {
    document.querySelectorAll('.batch-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    selectedBatchId  = id;
    selectedBatchQty = qty;
    document.getElementById('stock_batch_id').value = id;
    updateQtyFeedback();
    const qtyInput = document.getElementById('quantity_dispensed');
    qtyInput.max = qty;
    if (parseInt(qtyInput.value) > qty) qtyInput.value = qty;
}

function adjustQty(delta) {
    const input = document.getElementById('quantity_dispensed');
    let val = parseInt(input.value) || 0;
    val = Math.max(1, Math.min(selectedBatchQty || 9999, val + delta));
    input.value = val;
    updateQtyFeedback();
}

function updateQtyFeedback() {
    const feedback = document.getElementById('qtyFeedback');
    if (!feedback || !selectedBatchQty) return;
    const qty = parseInt(document.getElementById('quantity_dispensed').value) || 0;
    const rem = selectedBatchQty - qty;
    if (rem < 0) {
        feedback.innerHTML = `<span class="text-danger"><i class="bi bi-exclamation-circle me-1"></i>Exceeds available stock (${selectedBatchQty.toLocaleString()} units)</span>`;
    } else {
        feedback.innerHTML = `<span class="text-muted"><i class="bi bi-info-circle me-1"></i>${rem.toLocaleString()} units will remain after this dispense.</span>`;
    }
}

document.getElementById('quantity_dispensed').addEventListener('input', updateQtyFeedback);

// Drug change → load batches via AJAX
document.getElementById('drug_id').addEventListener('change', function () {
    const drugId = this.value;
    const batchSection  = document.getElementById('batchSection');
    const batchCards    = document.getElementById('batchCards');
    const qtySection    = document.getElementById('qtySection');
    const loadingMsg    = document.getElementById('batchLoadingMsg');
    const summaryCard   = document.getElementById('drugSummaryCard');

    if (!drugId) {
        batchSection.style.display = 'none';
        qtySection.style.display   = 'none';
        summaryCard.classList.add('d-none');
        return;
    }

    batchCards.innerHTML = '';
    batchSection.style.display  = 'none';
    qtySection.style.display    = 'none';
    loadingMsg.style.display    = 'flex';
    document.getElementById('stock_batch_id').value = '';
    selectedBatchId  = null;
    selectedBatchQty = 0;

    fetch(`/api/drugs/${drugId}/batches`)
        .then(r => r.json())
        .then(batches => {
            loadingMsg.style.display = 'none';
            batchSection.style.display = '';
            qtySection.style.display   = '';

            if (batches.length === 0) {
                batchCards.innerHTML = `
                    <div class="alert-banner warning fs-sm p-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        No active batches available for this drug.
                    </div>`;
                return;
            }

            batches.forEach((b, i) => {
                const badgeColor = { success: 'ok', warning: 'warning', info: 'soon', danger: 'critical' }[b.expiry_color] || 'ok';
                const fefoLabel  = i === 0 ? `<span class="badge ms-1" style="background:#f0fdf4;color:#166534;font-size:.62rem;">FEFO</span>` : '';
                const card = document.createElement('div');
                card.className = 'batch-card' + (i === 0 ? ' selected' : '');
                card.innerHTML = `
                    <div class="batch-card-top">
                        <span class="batch-num">${b.batch_number}${fefoLabel}</span>
                        <div class="text-end">
                            <div class="batch-qty">${b.quantity_remaining.toLocaleString()}</div>
                            <div class="batch-qty-label">units available</div>
                        </div>
                    </div>
                    <div class="batch-expiry">
                        <span class="badge badge-${badgeColor}">${b.days_to_expiry}d left</span>
                        <span class="text-muted">Expires ${b.expiry_date}</span>
                    </div>`;
                card.addEventListener('click', () => selectBatch(card, b.id, b.quantity_remaining));
                batchCards.appendChild(card);
                if (i === 0) {
                    selectedBatchId  = b.id;
                    selectedBatchQty = b.quantity_remaining;
                    document.getElementById('stock_batch_id').value = b.id;
                    const qtyInput = document.getElementById('quantity_dispensed');
                    qtyInput.max   = b.quantity_remaining;
                    updateQtyFeedback();
                }
            });
        })
        .catch(() => {
            loadingMsg.style.display = 'none';
            batchCards.innerHTML = `<div class="alert-banner danger fs-sm p-3"><i class="bi bi-x-circle me-2"></i>Failed to load batches. Please try again.</div>`;
            batchSection.style.display = '';
        });
});

// Loading state on submit
document.getElementById('dispenseForm').addEventListener('submit', function (e) {
    if (!document.getElementById('stock_batch_id').value) {
        e.preventDefault();
        alert('Please select a batch before submitting.');
        return;
    }
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Recording…';
});

// Init feedback on page load
updateQtyFeedback();
</script>
@endpush
