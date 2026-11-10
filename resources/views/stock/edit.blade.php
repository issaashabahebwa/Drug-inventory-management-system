@extends('layouts.app')
@section('title', 'Edit Batch — ' . $stockBatch->batch_number)
@section('page-title', 'Edit Stock Batch')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('stock.index') }}">Stock Batches</a>
    <i class="bi bi-chevron-right"></i>
    Edit {{ $stockBatch->batch_number }}
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Edit Batch</h2>
        <p>Update administrative details for <strong>{{ $stockBatch->batch_number }}</strong>.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('stock.show', $stockBatch) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-eye me-1"></i>View Batch
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <div class="card animate-fade-up">
            <div class="card-header">
                <div class="card-header-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="bi bi-pencil-square"></i>
                </div>
                Update Batch Details
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('stock.update', $stockBatch) }}" class="needs-validation" novalidate>
                    @csrf @method('PUT')

                    <p class="form-section-title">Administrative</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Delivery Note Number</label>
                            <input type="text" name="delivery_note_number"
                                   class="form-control @error('delivery_note_number') is-invalid @enderror"
                                   value="{{ old('delivery_note_number', $stockBatch->delivery_note_number) }}"
                                   placeholder="e.g. DN-2025-0120">
                            @error('delivery_note_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Unit Cost (UGX)</label>
                            <div class="input-group">
                                <span class="input-group-text">UGX</span>
                                <input type="number" name="unit_cost"
                                       class="form-control @error('unit_cost') is-invalid @enderror"
                                       value="{{ old('unit_cost', $stockBatch->unit_cost) }}"
                                       min="0" step="0.01">
                            </div>
                            @error('unit_cost')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <p class="form-section-title">Status</p>

                    <div class="mb-4">
                        <label class="form-label">Batch Status <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            @foreach(['active' => ['green', 'check-circle', 'Available for dispensing'],
                                       'depleted' => ['gray', 'dash-circle', 'All stock dispensed'],
                                       'expired' => ['red', 'x-circle', 'Past expiry date'],
                                       'quarantined' => ['amber', 'exclamation-octagon', 'Held pending investigation']]
                                       as $val => [$color, $icon, $desc])
                            <div class="col-6">
                                <label class="d-flex align-items-start gap-2 p-3 border rounded cursor-pointer
                                              {{ old('status', $stockBatch->status) === $val ? 'border-kiswa' : '' }}"
                                       style="border-radius:8px!important;cursor:pointer;transition:all .15s;"
                                       onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                    <input type="radio" name="status" value="{{ $val }}"
                                           {{ old('status', $stockBatch->status) === $val ? 'checked' : '' }}
                                           style="accent-color:var(--k-green);margin-top:2px;" required>
                                    <div>
                                        <div class="fw-600 fs-sm">
                                            <i class="bi bi-{{ $icon }} me-1"></i>{{ ucfirst($val) }}
                                        </div>
                                        <div class="fs-xs text-muted">{{ $desc }}</div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @error('status')<div class="text-danger fs-xs mt-1">{{ $message }}</div>@enderror
                    </div>

                    <p class="form-section-title">Notes</p>

                    <div class="mb-4">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Quarantine reason, storage issues…">{{ old('notes', $stockBatch->notes) }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('stock.show', $stockBatch) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-kiswa">
                            <i class="bi bi-check2-circle me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-lock"></i>
                </div>
                Read-only Details
            </div>
            <div class="card-body p-0">
                <ul class="info-list px-3">
                    <li><span class="info-key">Drug</span><span class="info-val fw-700">{{ $stockBatch->drug->name }}</span></li>
                    <li><span class="info-key">Batch Number</span><span class="info-val text-kiswa fw-700">{{ $stockBatch->batch_number }}</span></li>
                    <li><span class="info-key">Qty Received</span><span class="info-val">{{ number_format($stockBatch->quantity_received) }}</span></li>
                    <li><span class="info-key">Qty Remaining</span><span class="info-val fw-700 {{ $stockBatch->quantity_remaining > 0 ? 'text-success' : 'text-muted' }}">{{ number_format($stockBatch->quantity_remaining) }}</span></li>
                    <li><span class="info-key">Received Date</span><span class="info-val">{{ $stockBatch->received_date->format('d M Y') }}</span></li>
                    <li><span class="info-key">Expiry Date</span>
                        <span class="info-val {{ $stockBatch->isExpired() ? 'text-danger fw-700' : '' }}">
                            {{ $stockBatch->expiry_date->format('d M Y') }}
                        </span>
                    </li>
                    <li>
                        <span class="info-key">Expiry Status</span>
                        <span class="badge badge-{{ $stockBatch->expiry_status }}">{{ ucfirst($stockBatch->expiry_status) }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection
