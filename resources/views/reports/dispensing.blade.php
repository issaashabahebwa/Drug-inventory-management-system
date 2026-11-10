@extends('layouts.app')
@section('title','Dispensing Report')
@section('page-title','Dispensing Summary Report')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <p class="text-muted fs-sm mb-0">Drug usage summary for the selected date range.</p>
    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i>Print</button>
</div>
<div class="card mb-3 no-print">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-6 col-md-3"><label class="form-label fs-xs mb-1">From</label><input type="date" name="date_from" class="form-control form-control-sm" value="{{ $from }}"></div>
            <div class="col-6 col-md-3"><label class="form-label fs-xs mb-1">To</label><input type="date" name="date_to" class="form-control form-control-sm" value="{{ $to }}"></div>
            <div class="col-12 col-md-2 d-flex gap-1 align-self-end">
                <button class="btn btn-kiswa btn-sm flex-fill"><i class="bi bi-search me-1"></i>Apply</button>
                <a href="{{ route('reports.dispensing') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
            </div>
        </form>
    </div>
</div>
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3"><div class="metric-card green p-3"><div class="metric-value">{{ number_format($totalUnits) }}</div><div class="metric-label">Units Dispensed</div></div></div>
    <div class="col-6 col-md-3"><div class="metric-card blue p-3"><div class="metric-value">{{ number_format($totalTransactions) }}</div><div class="metric-label">Transactions</div></div></div>
</div>
<div class="row g-3">
    <div class="col-12 col-lg-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-trophy me-2 text-kiswa"></i>Top 10 Drugs by Usage</div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>#</th><th>Drug</th><th class="text-center">Transactions</th><th class="text-center">Units</th></tr></thead>
                    <tbody>
                    @forelse($topDrugs as $i => $row)
                    <tr>
                        <td class="text-muted fs-xs">{{ $i+1 }}</td>
                        <td class="fs-sm fw-semibold">{{ $row->drug->name }}</td>
                        <td class="text-center fs-sm">{{ $row->transaction_count }}</td>
                        <td class="text-center fs-sm fw-bold text-kiswa">{{ number_format($row->total_qty) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-3 text-muted">No data</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-journal-text me-2 text-kiswa"></i>Transaction Log ({{ $from }} to {{ $to }})</div>
            <div class="card-body p-0" style="max-height:420px;overflow-y:auto;">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Date</th><th>Patient Ref.</th><th>Drug</th><th class="text-center">Qty</th></tr></thead>
                    <tbody>
                    @forelse($records as $rec)
                    <tr>
                        <td class="fs-sm">{{ $rec->dispensed_date->format('d/m/Y') }}</td>
                        <td class="fs-sm">{{ $rec->patient_reference }}</td>
                        <td class="fs-sm">{{ $rec->drug->name }}</td>
                        <td class="text-center fs-sm fw-bold">{{ $rec->quantity_dispensed }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4 text-muted">No records for this period.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
