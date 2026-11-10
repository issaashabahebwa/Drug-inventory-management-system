@extends('layouts.app')
@section('title', 'Dispensing Record')
@section('page-title', 'Dispensing Record')
@section('content')
<div class="page-header">
    <div class="page-header-title">
        <h2>Dispensing Record</h2>
        <p>{{ $dispensingRecord->drug->name }} — {{ $dispensingRecord->patient_reference }}</p>
    </div>
    <div class="page-header-actions no-print">
        <a href="{{ route('dispensing.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i>Print</button>
    </div>
</div>
<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header"><div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-clipboard2-pulse"></i></div>Dispensing Details</div>
            <div class="card-body p-0">
                <ul class="info-list px-3">
                    <li><span class="info-key">Drug</span><span class="info-val fw-700">{{ $dispensingRecord->drug->name }}</span></li>
                    <li><span class="info-key">Batch</span><span class="info-val text-kiswa fw-600">{{ $dispensingRecord->stockBatch->batch_number ?? '—' }}</span></li>
                    <li><span class="info-key">Quantity</span><span class="info-val fw-800" style="font-size:1rem;color:var(--k-green);">{{ number_format($dispensingRecord->quantity_dispensed) }} units</span></li>
                    <li><span class="info-key">Date</span><span class="info-val">{{ $dispensingRecord->dispensed_date->format('d M Y') }}</span></li>
                    <li><span class="info-key">Time</span><span class="info-val">{{ $dispensingRecord->dispensed_time ?? '—' }}</span></li>
                    <li><span class="info-key">Dispensed By</span><span class="info-val">{{ $dispensingRecord->dispensedBy->name ?? '—' }}</span></li>
                    @if($dispensingRecord->notes)
                    <li><span class="info-key">Notes</span><span class="info-val fs-xs">{{ $dispensingRecord->notes }}</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header"><div class="card-header-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-person"></i></div>Patient Information</div>
            <div class="card-body p-0">
                <ul class="info-list px-3">
                    <li><span class="info-key">Reference No.</span><span class="info-val fw-700 text-kiswa">{{ $dispensingRecord->patient_reference }}</span></li>
                    <li><span class="info-key">Patient Name</span><span class="info-val">{{ $dispensingRecord->patient_name ?: '—' }}</span></li>
                    <li><span class="info-key">Diagnosis</span><span class="info-val">{{ $dispensingRecord->diagnosis ?: '—' }}</span></li>
                    <li><span class="info-key">Supplier</span><span class="info-val">{{ $dispensingRecord->stockBatch->supplier->name ?? '—' }}</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
