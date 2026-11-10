@extends('layouts.app')
@section('title', 'Batch ' . $stockBatch->batch_number)
@section('page-title', 'Stock Batch Details')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('stock.index') }}">Stock Batches</a>
    <i class="bi bi-chevron-right"></i>
    {{ $stockBatch->batch_number }}
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>{{ $stockBatch->batch_number }}</h2>
        <p>{{ $stockBatch->drug->name }} &mdash; received {{ $stockBatch->received_date->format('d M Y') }}</p>
    </div>
    <div class="page-header-actions no-print">
        <a href="{{ route('stock.edit', $stockBatch) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit Batch
        </a>
        <a href="{{ route('stock.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>All Batches
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-card blue animate-fade-up stagger-1">
            <div class="stat-card-icon"><i class="bi bi-box-arrow-in-down"></i></div>
            <div class="stat-card-value">{{ number_format($stockBatch->quantity_received) }}</div>
            <div class="stat-card-label">Qty Received</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card {{ $stockBatch->quantity_remaining > 0 ? 'green' : 'red' }} animate-fade-up stagger-2">
            <div class="stat-card-icon"><i class="bi bi-boxes"></i></div>
            <div class="stat-card-value">{{ number_format($stockBatch->quantity_remaining) }}</div>
            <div class="stat-card-label">Qty Remaining</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card teal animate-fade-up stagger-3">
            <div class="stat-card-icon"><i class="bi bi-clipboard2-check"></i></div>
            <div class="stat-card-value">{{ number_format($stockBatch->quantity_dispensed) }}</div>
            <div class="stat-card-label">Qty Dispensed</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card {{ $stockBatch->expiry_status === 'ok' ? 'green' : ($stockBatch->expiry_status === 'expired' ? 'red' : 'amber') }} animate-fade-up stagger-4">
            <div class="stat-card-icon"><i class="bi bi-calendar-x"></i></div>
            <div class="stat-card-value">
                @if($stockBatch->days_to_expiry < 0) EXP
                @else {{ $stockBatch->days_to_expiry }}d
                @endif
            </div>
            <div class="stat-card-label">
                @if($stockBatch->days_to_expiry < 0) Already Expired
                @else Days to Expiry
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-4">
        <div class="card animate-fade-up stagger-1">
            <div class="card-header">
                <div class="card-header-icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-info-circle"></i>
                </div>
                Batch Information
            </div>
            <div class="card-body p-0">
                <ul class="info-list px-3">
                    <li>
                        <span class="info-key">Drug</span>
                        <span class="info-val fw-700">{{ $stockBatch->drug->name }}</span>
                    </li>
                    <li>
                        <span class="info-key">Batch Number</span>
                        <span class="info-val text-kiswa fw-700">{{ $stockBatch->batch_number }}</span>
                    </li>
                    <li>
                        <span class="info-key">Supplier</span>
                        <span class="info-val">{{ $stockBatch->supplier->name ?? '—' }}</span>
                    </li>
                    <li>
                        <span class="info-key">Delivery Note</span>
                        <span class="info-val">{{ $stockBatch->delivery_note_number ?: '—' }}</span>
                    </li>
                    <li>
                        <span class="info-key">Received Date</span>
                        <span class="info-val">{{ $stockBatch->received_date->format('d M Y') }}</span>
                    </li>
                    <li>
                        <span class="info-key">Received By</span>
                        <span class="info-val">{{ $stockBatch->receivedBy->name ?? '—' }}</span>
                    </li>
                    <li>
                        <span class="info-key">Manufacture Date</span>
                        <span class="info-val">{{ $stockBatch->manufacture_date?->format('d M Y') ?? '—' }}</span>
                    </li>
                    <li>
                        <span class="info-key">Expiry Date</span>
                        <span class="info-val fw-700 {{ $stockBatch->isExpired() ? 'text-danger' : '' }}">
                            {{ $stockBatch->expiry_date->format('d M Y') }}
                        </span>
                    </li>
                    <li>
                        <span class="info-key">Unit Cost</span>
                        <span class="info-val">{{ $stockBatch->unit_cost ? 'UGX '.number_format($stockBatch->unit_cost, 2) : '—' }}</span>
                    </li>
                    <li>
                        <span class="info-key">Batch Status</span>
                        @php $bsm=['active'=>'green','depleted'=>'gray','expired'=>'red','quarantined'=>'amber']; @endphp
                        <span class="status-pill {{ $bsm[$stockBatch->status] ?? 'gray' }}">
                            {{ ucfirst($stockBatch->status) }}
                        </span>
                    </li>
                    <li>
                        <span class="info-key">Expiry Status</span>
                        <span class="badge badge-{{ $stockBatch->expiry_status }}">
                            {{ ucfirst($stockBatch->expiry_status) }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        @if($stockBatch->notes)
        <div class="card mt-3 animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="bi bi-sticky"></i>
                </div>
                Notes
            </div>
            <div class="card-body fs-sm text-secondary">{{ $stockBatch->notes }}</div>
        </div>
        @endif
    </div>

    <div class="col-12 col-lg-8">
        {{-- Usage bar --}}
        <div class="card mb-3 animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-bar-chart-steps"></i>
                </div>
                Batch Usage
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between fs-xs text-muted mb-1">
                    <span>Dispensed: <strong class="text-dark">{{ number_format($stockBatch->quantity_dispensed) }}</strong></span>
                    <span>Remaining: <strong class="text-success">{{ number_format($stockBatch->quantity_remaining) }}</strong></span>
                    <span>Total: <strong class="text-dark">{{ number_format($stockBatch->quantity_received) }}</strong></span>
                </div>
                <div class="progress" style="height:10px;border-radius:99px;background:#e2e8f0;">
                    <div class="progress-bar"
                         style="width:{{ $stockBatch->usage_percent }}%;background:var(--k-green);border-radius:99px;transition:width .6s ease;"
                         role="progressbar" aria-valuenow="{{ $stockBatch->usage_percent }}"
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <div class="fs-xs text-muted mt-1 text-end">{{ $stockBatch->usage_percent }}% dispensed</div>
            </div>
        </div>

        {{-- Dispensing records for this batch --}}
        <div class="card animate-fade-up stagger-3">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
                Dispensing Records
                <span class="badge ms-1" style="background:#f0fdf4;color:#166534;">
                    {{ $stockBatch->dispensingRecords->count() }}
                </span>
            </div>
            <div class="card-body p-0">
                @if($stockBatch->dispensingRecords->isEmpty())
                <div class="empty-state py-4">
                    <div class="empty-state-icon"><i class="bi bi-clipboard2"></i></div>
                    <p class="fs-sm text-muted mb-0">No dispensing records from this batch yet.</p>
                </div>
                @else
                <div class="table-wrapper">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Patient Ref</th>
                                <th>Patient Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Date</th>
                                <th>Dispensed By</th>
                                <th class="no-print"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockBatch->dispensingRecords as $rec)
                            <tr>
                                <td class="fw-600 fs-sm">{{ $rec->patient_reference }}</td>
                                <td class="fs-sm text-muted">{{ $rec->patient_name ?: '—' }}</td>
                                <td class="text-center fw-700 text-kiswa">{{ number_format($rec->quantity_dispensed) }}</td>
                                <td class="text-center fs-xs">{{ $rec->dispensed_date->format('d M Y') }}</td>
                                <td class="fs-xs text-muted">{{ $rec->dispensedBy->name ?? '—' }}</td>
                                <td class="no-print">
                                    <a href="{{ route('dispensing.show', $rec) }}"
                                       class="btn btn-sm btn-icon btn-outline-secondary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
