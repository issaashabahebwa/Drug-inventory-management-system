@extends('layouts.app')
@section('title','Stock Balance Report')
@section('page-title','Stock Balance Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <p class="text-muted fs-sm mb-0">Current stock levels for all registered drugs.</p>
    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i>Print</button>
</div>
<div class="card mb-3 no-print">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3">
                <select name="category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)<option value="{{ $cat }}" {{ request('category')==$cat?'selected':'' }}>{{ Str::limit($cat,30) }}</option>@endforeach
                </select>
            </div>
            <div class="col-6 col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Levels</option>
                    <option value="adequate" {{ request('status')=='adequate'?'selected':'' }}>Adequate</option>
                    <option value="low"      {{ request('status')=='low'?'selected':'' }}>Low Stock</option>
                    <option value="out"      {{ request('status')=='out'?'selected':'' }}>Out of Stock</option>
                </select>
            </div>
            <div class="col-12 col-md-2 d-flex gap-1">
                <button class="btn btn-kiswa btn-sm flex-fill"><i class="bi bi-search me-1"></i>Filter</button>
                <a href="{{ route('reports.stock-balance') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3"><div class="metric-card green p-3"><div class="metric-value">{{ $summary['total'] }}</div><div class="metric-label">Total Drugs</div></div></div>
    <div class="col-6 col-md-3"><div class="metric-card blue p-3"><div class="metric-value">{{ $summary['adequate'] }}</div><div class="metric-label">Adequate</div></div></div>
    <div class="col-6 col-md-3"><div class="metric-card orange p-3"><div class="metric-value">{{ $summary['low'] }}</div><div class="metric-label">Low Stock</div></div></div>
    <div class="col-6 col-md-3"><div class="metric-card red p-3"><div class="metric-value">{{ $summary['out'] }}</div><div class="metric-label">Out of Stock</div></div></div>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Drug Name</th><th>Category</th><th>Formulation</th><th class="text-center">In Stock</th><th class="text-center">Reorder Lvl</th><th class="text-center">Status</th></tr></thead>
                <tbody>
                @forelse($drugs as $i => $drug)
                <tr>
                    <td class="text-muted fs-xs">{{ $i+1 }}</td>
                    <td><div class="fw-semibold fs-sm">{{ $drug->name }}</div><div class="text-muted" style="font-size:.7rem">{{ $drug->strength }}</div></td>
                    <td class="fs-sm">{{ Str::limit($drug->category,28) }}</td>
                    <td><span class="badge bg-light text-dark border">{{ ucfirst($drug->formulation) }}</span></td>
                    <td class="text-center fw-bold {{ $drug->total_quantity==0?'text-danger':($drug->isLowStock()?'text-warning':'text-success') }}">{{ number_format($drug->total_quantity) }}</td>
                    <td class="text-center fs-sm text-muted">{{ number_format($drug->reorder_level) }}</td>
                    <td class="text-center"><span class="badge badge-{{ $drug->stock_status=='adequate'?'adequate':($drug->stock_status=='out_of_stock'?'out':'low') }}">{{ $drug->stock_status_label }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">No drugs found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
