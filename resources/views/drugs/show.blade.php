@extends('layouts.app')
@section('title', $drug->name)
@section('page-title', 'Drug Details')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('drugs.index') }}">Drug Register</a>
    <i class="bi bi-chevron-right"></i>
    {{ Str::limit($drug->name, 30) }}
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>{{ $drug->name }}</h2>
        <p>
            {{ ucfirst($drug->formulation) }}
            @if($drug->strength)· {{ $drug->strength }}@endif
            &mdash; {{ $drug->category }}
        </p>
    </div>
    <div class="page-header-actions no-print">
        <a href="{{ route('drugs.edit', $drug) }}" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i>Edit
        </a>
        <a href="{{ route('stock.create', ['drug_id' => $drug->id]) }}" class="btn btn-sm btn-outline-success">
            <i class="bi bi-plus-circle me-1"></i>Receive Stock
        </a>
        <a href="{{ route('dispensing.create', ['drug_id' => $drug->id]) }}" class="btn btn-sm btn-kiswa">
            <i class="bi bi-clipboard2-pulse me-1"></i>Dispense
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    {{-- Stock stats --}}
    @php $sm=['adequate'=>'green','moderate'=>'teal','low'=>'amber','out_of_stock'=>'red']; @endphp
    <div class="col-6 col-md-3">
        <div class="stat-card {{ $sm[$drug->stock_status] ?? 'green' }} animate-fade-up stagger-1">
            <div class="stat-card-icon"><i class="bi bi-boxes"></i></div>
            <div class="stat-card-value">{{ number_format($drug->total_quantity) }}</div>
            <div class="stat-card-label">Total in Stock</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card blue animate-fade-up stagger-2">
            <div class="stat-card-icon"><i class="bi bi-box-seam"></i></div>
            <div class="stat-card-value">{{ $drug->stockBatches->where('status','active')->count() }}</div>
            <div class="stat-card-label">Active Batches</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card teal animate-fade-up stagger-3">
            <div class="stat-card-icon"><i class="bi bi-clipboard2-check"></i></div>
            <div class="stat-card-value">{{ $drug->dispensingRecords->count() }}</div>
            <div class="stat-card-label">Dispense Records</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card {{ $drug->isLowStock() ? 'amber' : 'green' }} animate-fade-up stagger-4">
            <div class="stat-card-icon"><i class="bi bi-arrow-down-circle"></i></div>
            <div class="stat-card-value">{{ number_format($drug->reorder_level) }}</div>
            <div class="stat-card-label">Reorder Level</div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Drug details card --}}
    <div class="col-12 col-lg-4">
        <div class="card animate-fade-up stagger-1">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-info-circle"></i>
                </div>
                Drug Profile
            </div>
            <div class="card-body p-0">
                <ul class="info-list px-3">
                    <li>
                        <span class="info-key">Generic Name</span>
                        <span class="info-val fw-700">{{ $drug->name }}</span>
                    </li>
                    @if($drug->brand_name)
                    <li>
                        <span class="info-key">Brand Name</span>
                        <span class="info-val">{{ $drug->brand_name }}</span>
                    </li>
                    @endif
                    <li>
                        <span class="info-key">Category</span>
                        <span class="info-val fs-xs">{{ $drug->category }}</span>
                    </li>
                    <li>
                        <span class="info-key">Formulation</span>
                        <span class="badge" style="background:#f1f5f9;color:#475569;font-weight:600;font-size:.7rem;">
                            {{ ucfirst($drug->formulation) }}
                        </span>
                    </li>
                    <li>
                        <span class="info-key">Strength</span>
                        <span class="info-val">{{ $drug->strength ?: '—' }}</span>
                    </li>
                    <li>
                        <span class="info-key">Unit</span>
                        <span class="info-val">{{ $drug->unit_of_measure }}</span>
                    </li>
                    <li>
                        <span class="info-key">Status</span>
                        <span class="status-pill {{ $drug->is_active ? 'green' : 'gray' }}">
                            {{ $drug->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </li>
                    <li>
                        <span class="info-key">Stock Level</span>
                        <span class="status-pill {{ $sm[$drug->stock_status] ?? 'gray' }}">
                            {{ $drug->stock_status_label }}
                        </span>
                    </li>
                    <li>
                        <span class="info-key">Registered</span>
                        <span class="info-val">{{ $drug->created_at->format('d M Y') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        @if($drug->notes)
        <div class="card mt-3 animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="bi bi-sticky"></i>
                </div>
                Notes
            </div>
            <div class="card-body fs-sm text-secondary" style="line-height:1.75;">
                {{ $drug->notes }}
            </div>
        </div>
        @endif
    </div>

    {{-- Batches --}}
    <div class="col-12 col-lg-8">
        <div class="card animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-box-seam"></i>
                </div>
                Stock Batches
                <span class="badge ms-1" style="background:#dbeafe;color:#1e40af;">
                    {{ $drug->stockBatches->count() }}
                </span>
                <a href="{{ route('stock.create', ['drug_id' => $drug->id]) }}"
                   class="btn btn-sm ms-auto btn-outline-success no-print" style="font-size:.72rem;padding:.25rem .65rem;">
                    <i class="bi bi-plus me-1"></i>Receive Stock
                </a>
            </div>
            <div class="card-body p-0">
                @if($drug->stockBatches->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="bi bi-box"></i></div>
                    <h5>No Batches Yet</h5>
                    <p>No stock has been received for this drug.</p>
                </div>
                @else
                <div class="table-wrapper">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Batch #</th>
                                <th>Supplier</th>
                                <th class="text-center">Received</th>
                                <th class="text-center">Remaining</th>
                                <th class="text-center">Expiry</th>
                                <th class="text-center">Status</th>
                                <th class="no-print"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($drug->stockBatches->sortByDesc('created_at') as $batch)
                            <tr>
                                <td class="fw-600 fs-sm">{{ $batch->batch_number }}</td>
                                <td class="fs-xs text-muted">{{ $batch->supplier->name ?? '—' }}</td>
                                <td class="text-center fs-xs">{{ $batch->received_date->format('d M Y') }}</td>
                                <td class="text-center fw-700 {{ $batch->quantity_remaining == 0 ? 'text-muted' : 'text-success' }}">
                                    {{ number_format($batch->quantity_remaining) }}
                                </td>
                                <td class="text-center fs-xs">{{ $batch->expiry_date->format('d M Y') }}</td>
                                <td class="text-center">
                                    @php
                                        $bsm = ['active'=>'green','depleted'=>'gray','expired'=>'red','quarantined'=>'amber'];
                                    @endphp
                                    <span class="status-pill {{ $bsm[$batch->status] ?? 'gray' }}">
                                        {{ ucfirst($batch->status) }}
                                    </span>
                                </td>
                                <td class="no-print">
                                    <a href="{{ route('stock.show', $batch) }}"
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

        {{-- Recent dispensing --}}
        <div class="card mt-3 animate-fade-up stagger-3">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
                Recent Dispensing
                <a href="{{ route('dispensing.index', ['drug_id' => $drug->id]) }}"
                   class="btn btn-sm btn-link p-0 ms-auto fs-xs no-print">View all →</a>
            </div>
            <div class="card-body p-0">
                @if($drug->dispensingRecords->isEmpty())
                <div class="empty-state py-4">
                    <div class="empty-state-icon"><i class="bi bi-clipboard2"></i></div>
                    <p class="fs-sm text-muted mb-0">No dispensing records yet.</p>
                </div>
                @else
                <div class="table-wrapper">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Patient Ref</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Date</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($drug->dispensingRecords as $rec)
                            <tr>
                                <td class="fs-sm fw-500">{{ $rec->patient_reference }}</td>
                                <td class="text-center fw-700 text-kiswa">{{ number_format($rec->quantity_dispensed) }}</td>
                                <td class="text-center fs-xs">{{ $rec->dispensed_date->format('d M Y') }}</td>
                                <td class="fs-xs text-muted">{{ $rec->dispensedBy->name ?? '—' }}</td>
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
