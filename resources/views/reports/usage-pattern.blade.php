@extends('layouts.app')
@section('title','Usage Pattern')
@section('page-title','Drug Usage Pattern Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <p class="text-muted fs-sm mb-0">Fast vs slow-moving drug classification.</p>
    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i>Print</button>
</div>
<div class="card mb-3 no-print">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-2"><label class="form-label fs-xs mb-1">Period</label>
                <select name="months" class="form-select form-select-sm">
                    <option value="1"  {{ $months==1 ?'selected':'' }}>1 month</option>
                    <option value="3"  {{ $months==3 ?'selected':'' }}>3 months</option>
                    <option value="6"  {{ $months==6 ?'selected':'' }}>6 months</option>
                    <option value="12" {{ $months==12?'selected':'' }}>12 months</option>
                </select>
            </div>
            <div class="col-6 col-md-2 align-self-end">
                <button class="btn btn-kiswa btn-sm w-100"><i class="bi bi-search me-1"></i>Apply</button>
            </div>
        </form>
    </div>
</div>
<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header"><span class="text-success fw-bold"><i class="bi bi-arrow-up-circle me-2"></i>Fast-Moving Drugs</span> <span class="badge bg-success ms-1">{{ $fastMoving->count() }}</span></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Drug</th><th class="text-center">Units Dispensed</th></tr></thead>
                    <tbody>
                    @forelse($fastMoving->sortByDesc('total_dispensed') as $row)
                    <tr>
                        <td class="fs-sm fw-semibold">{{ $row->drug->name }}</td>
                        <td class="text-center fw-bold text-success">{{ number_format($row->total_dispensed) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header"><span class="text-warning fw-bold"><i class="bi bi-arrow-down-circle me-2"></i>Slow-Moving Drugs</span> <span class="badge bg-warning text-dark ms-1">{{ $slowMoving->count() }}</span></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Drug</th><th class="text-center">Units Dispensed</th></tr></thead>
                    <tbody>
                    @forelse($slowMoving->sortByDesc('total_dispensed') as $row)
                    <tr>
                        <td class="fs-sm fw-semibold">{{ $row->drug->name }}</td>
                        <td class="text-center fw-bold text-warning">{{ number_format($row->total_dispensed) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
