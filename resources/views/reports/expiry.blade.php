@extends('layouts.app')
@section('title','Expiry Report')
@section('page-title','Expiry Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <p class="text-muted fs-sm mb-0">Batches expiring within the selected window.</p>
    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i>Print</button>
</div>
<div class="card mb-3 no-print">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-2">
                <select name="window" class="form-select form-select-sm">
                    <option value="30"  {{ request('window',90)==30 ?'selected':'' }}>30 days</option>
                    <option value="60"  {{ request('window',90)==60 ?'selected':'' }}>60 days</option>
                    <option value="90"  {{ request('window',90)==90 ?'selected':'' }}>90 days</option>
                    <option value="180" {{ request('window',90)==180?'selected':'' }}>180 days</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <div class="form-check mt-1">
                    <input class="form-check-input" type="checkbox" name="include_expired" value="1" {{ request('include_expired')?'checked':'' }}>
                    <label class="form-check-label fs-sm">Include already expired</label>
                </div>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button class="btn btn-kiswa btn-sm flex-fill"><i class="bi bi-search me-1"></i>Apply</button>
                <a href="{{ route('reports.expiry') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3"><div class="metric-card red p-3"><div class="metric-value">{{ $expired }}</div><div class="metric-label">Already Expired</div></div></div>
    <div class="col-6 col-md-3"><div class="metric-card red p-3"><div class="metric-value">{{ $critical }}</div><div class="metric-label">&le;30 Days</div></div></div>
    <div class="col-6 col-md-3"><div class="metric-card orange p-3"><div class="metric-value">{{ $warning }}</div><div class="metric-label">31–60 Days</div></div></div>
    <div class="col-6 col-md-3"><div class="metric-card blue p-3"><div class="metric-value">{{ $soon }}</div><div class="metric-label">61–90 Days</div></div></div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Drug</th><th>Batch No.</th><th>Supplier</th><th class="text-center">Expiry Date</th><th class="text-center">Days Left</th><th class="text-center">Qty Remaining</th><th class="text-center">Action</th></tr></thead>
                <tbody>
                @forelse($batches as $batch)
                <tr class="{{ $batch->isExpired()?'table-danger':($batch->isExpiringSoon(30)?'table-warning':'') }}">
                    <td><div class="fw-semibold fs-sm">{{ $batch->drug->name }}</div><div class="text-muted" style="font-size:.7rem">{{ $batch->drug->strength }}</div></td>
                    <td class="fs-sm text-muted">{{ $batch->batch_number }}</td>
                    <td class="fs-sm text-muted">{{ $batch->supplier->name ?? 'N/A' }}</td>
                    <td class="text-center fs-sm">{{ $batch->expiry_date->format('d/m/Y') }}</td>
                    <td class="text-center"><span class="badge badge-{{ $batch->expiry_status }}">{{ $batch->days_to_expiry>=0?$batch->days_to_expiry.' days':'EXPIRED' }}</span></td>
                    <td class="text-center fs-sm fw-bold">{{ number_format($batch->quantity_remaining) }}</td>
                    <td class="text-center no-print">
                        @if($batch->isExpired())
                        <a href="{{ route('stock.edit',$batch) }}" class="btn btn-xs btn-sm btn-outline-danger">Quarantine</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No batches in this expiry window.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
