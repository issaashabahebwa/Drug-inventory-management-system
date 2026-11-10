@extends('layouts.app')
@section('title', 'Dispensing Log')
@section('page-title', 'Dispensing Log')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    Dispensing Log
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Dispensing Log</h2>
        <p>Complete history of all drug dispensing transactions.</p>
    </div>
    <div class="page-header-actions no-print">
        <a href="{{ route('dispensing.create') }}" class="btn btn-kiswa btn-sm">
            <i class="bi bi-clipboard2-pulse me-1"></i>New Dispense
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="filter-bar no-print animate-fade-up">
    <form method="GET" action="{{ route('dispensing.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-md-3">
            <div class="search-input-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Patient ref, name or drug…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="drug_id" class="form-select form-select-sm">
                <option value="">All Drugs</option>
                @foreach($drugs as $drug)
                <option value="{{ $drug->id }}" {{ request('drug_id') == $drug->id ? 'selected' : '' }}>
                    {{ Str::limit($drug->name, 30) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <input type="date" name="date_from" class="form-control form-control-sm"
                   value="{{ request('date_from') }}" placeholder="From date">
        </div>
        <div class="col-6 col-md-2">
            <input type="date" name="date_to" class="form-control form-control-sm"
                   value="{{ request('date_to') }}" placeholder="To date">
        </div>
        <div class="col-6 col-md-3 d-flex gap-1">
            <button class="btn btn-kiswa btn-sm flex-fill">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            @if(request()->hasAny(['search','drug_id','date_from','date_to']))
            <a href="{{ route('dispensing.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<div class="card animate-fade-up stagger-1">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                <i class="bi bi-journal-text"></i>
            </div>
            Dispensing Records
        </div>
        <span class="fs-xs text-muted">{{ $records->total() }} record{{ $records->total() !== 1 ? 's' : '' }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Patient Ref</th>
                        <th>Patient Name</th>
                        <th>Drug</th>
                        <th>Batch</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Time</th>
                        <th>Dispensed By</th>
                        <th class="no-print text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                    <tr>
                        <td class="fw-700 fs-sm text-kiswa">{{ $rec->patient_reference }}</td>
                        <td class="fs-sm">{{ $rec->patient_name ?: '—' }}</td>
                        <td>
                            <div class="fw-600 fs-sm">{{ $rec->drug->name }}</div>
                            @if($rec->diagnosis)
                            <div class="fs-xs text-muted">{{ Str::limit($rec->diagnosis, 25) }}</div>
                            @endif
                        </td>
                        <td class="fs-xs text-muted">{{ $rec->stockBatch->batch_number ?? '—' }}</td>
                        <td class="text-center fw-700 text-kiswa">{{ number_format($rec->quantity_dispensed) }}</td>
                        <td class="text-center fs-sm">{{ $rec->dispensed_date->format('d M Y') }}</td>
                        <td class="text-center fs-xs text-muted">{{ $rec->dispensed_time ?? '—' }}</td>
                        <td class="fs-xs text-muted">{{ $rec->dispensedBy->name ?? '—' }}</td>
                        <td class="text-center no-print">
                            <a href="{{ route('dispensing.show', $rec) }}"
                               class="btn btn-sm btn-icon btn-outline-secondary" title="View details">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-0">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-clipboard2"></i></div>
                                <h5>No Records Found</h5>
                                <p>No dispensing records match your current filters.</p>
                                <a href="{{ route('dispensing.create') }}" class="btn btn-kiswa btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Record First Dispensing
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between no-print">
        <span class="fs-xs text-muted">
            Showing {{ $records->firstItem() }}–{{ $records->lastItem() }} of {{ $records->total() }} results
        </span>
        {{ $records->links() }}
    </div>
    @endif
</div>

@endsection
