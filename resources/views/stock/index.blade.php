@extends('layouts.app')
@section('title', 'Stock Batches')
@section('page-title', 'Stock Batches')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    Stock Batches
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Stock Batches</h2>
        <p>All received drug batches with expiry tracking.</p>
    </div>
    <div class="page-header-actions no-print">
        <a href="{{ route('stock.create') }}" class="btn btn-kiswa btn-sm">
            <i class="bi bi-plus-circle me-1"></i>Receive New Stock
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="filter-bar no-print animate-fade-up">
    <form method="GET" action="{{ route('stock.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="search-input-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search batch number or drug name…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                <option value="active"      {{ request('status')=='active'      ? 'selected':'' }}>Active</option>
                <option value="depleted"    {{ request('status')=='depleted'    ? 'selected':'' }}>Depleted</option>
                <option value="expired"     {{ request('status')=='expired'     ? 'selected':'' }}>Expired</option>
                <option value="quarantined" {{ request('status')=='quarantined' ? 'selected':'' }}>Quarantined</option>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select name="expiry_filter" class="form-select form-select-sm">
                <option value="">All Expiry Dates</option>
                <option value="expired"  {{ request('expiry_filter')=='expired'  ? 'selected':'' }}>⛔ Already Expired</option>
                <option value="critical" {{ request('expiry_filter')=='critical' ? 'selected':'' }}>🔴 Critical (≤30 days)</option>
                <option value="warning"  {{ request('expiry_filter')=='warning'  ? 'selected':'' }}>🟡 Warning (31–60 days)</option>
                <option value="soon"     {{ request('expiry_filter')=='soon'     ? 'selected':'' }}>🔵 Soon (61–90 days)</option>
            </select>
        </div>
        <div class="col-6 col-md-3 d-flex gap-1">
            <button class="btn btn-kiswa btn-sm flex-fill">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            @if(request()->hasAny(['search','status','expiry_filter']))
            <a href="{{ route('stock.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear">
                <i class="bi bi-x-lg"></i>
            </a>
            @endif
        </div>
    </form>
</div>

<div class="card animate-fade-up stagger-1">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="card-header-icon" style="background:#dbeafe;color:#2563eb;">
                <i class="bi bi-box-seam"></i>
            </div>
            Stock Batch List
        </div>
        <span class="fs-xs text-muted">{{ $batches->total() }} batch{{ $batches->total() !== 1 ? 'es' : '' }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Drug</th>
                        <th>Batch #</th>
                        <th>Supplier</th>
                        <th class="text-center">Received</th>
                        <th class="text-center">Qty Recv.</th>
                        <th class="text-center">Remaining</th>
                        <th class="text-center">Expiry Date</th>
                        <th class="text-center">Days Left</th>
                        <th class="text-center">Status</th>
                        <th class="no-print text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $batch)
                    @php
                        $rowStyle = '';
                        if ($batch->expiry_status === 'expired')  $rowStyle = 'background:#fff5f5;';
                        if ($batch->expiry_status === 'critical') $rowStyle = 'background:#fffbeb;';
                    @endphp
                    <tr style="{{ $rowStyle }}">
                        <td>
                            <div class="fw-600 fs-sm">{{ $batch->drug->name }}</div>
                            <div class="fs-xs text-muted">{{ ucfirst($batch->drug->formulation) }}
                                @if($batch->drug->strength)· {{ $batch->drug->strength }}@endif
                            </div>
                        </td>
                        <td class="fw-600 fs-sm text-kiswa">{{ $batch->batch_number }}</td>
                        <td class="fs-xs text-muted">{{ $batch->supplier->name ?? '—' }}</td>
                        <td class="text-center fs-xs">{{ $batch->received_date->format('d M Y') }}</td>
                        <td class="text-center fs-sm fw-500">{{ number_format($batch->quantity_received) }}</td>
                        <td class="text-center">
                            <span class="fw-700 {{ $batch->quantity_remaining == 0 ? 'text-muted' : 'text-success' }}"
                                  style="font-size:.88rem;">
                                {{ number_format($batch->quantity_remaining) }}
                            </span>
                            @if($batch->quantity_received > 0)
                            <div class="stock-bar mt-1" style="width:60px;margin:0 auto;">
                                @php $usedPct = round(($batch->quantity_remaining / $batch->quantity_received) * 100); @endphp
                                <div class="stock-bar-fill {{ $usedPct > 50 ? 'green' : ($usedPct > 20 ? 'amber' : 'red') }}"
                                     style="width:{{ $usedPct }}%"></div>
                            </div>
                            @endif
                        </td>
                        <td class="text-center fs-sm">{{ $batch->expiry_date->format('d M Y') }}</td>
                        <td class="text-center">
                            @if($batch->days_to_expiry < 0)
                                <span class="badge badge-critical">Expired</span>
                            @else
                                <span class="badge badge-{{ $batch->expiry_status }}">
                                    {{ $batch->days_to_expiry }}d
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @php $bsm=['active'=>'green','depleted'=>'gray','expired'=>'red','quarantined'=>'amber']; @endphp
                            <span class="status-pill {{ $bsm[$batch->status] ?? 'gray' }}">
                                {{ ucfirst($batch->status) }}
                            </span>
                        </td>
                        <td class="text-center no-print">
                            <div class="table-actions justify-content-center">
                                <a href="{{ route('stock.show', $batch) }}"
                                   class="btn btn-sm btn-icon btn-outline-secondary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('stock.edit', $batch) }}"
                                   class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-0">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-box-seam"></i></div>
                                <h5>No Stock Batches Found</h5>
                                <p>No batches match your current filters, or none have been received yet.</p>
                                <a href="{{ route('stock.create') }}" class="btn btn-kiswa btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Receive First Batch
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($batches->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between no-print">
        <span class="fs-xs text-muted">
            Showing {{ $batches->firstItem() }}–{{ $batches->lastItem() }} of {{ $batches->total() }} results
        </span>
        {{ $batches->links() }}
    </div>
    @endif
</div>

@endsection
