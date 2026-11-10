@extends('layouts.app')
@section('title', 'Procurement Request')
@section('page-title', 'Procurement Request')

@section('content')
<div class="mb-3 d-flex gap-2 no-print">
    <a href="{{ route('procurement.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer me-1"></i>Print</button>
</div>

<div class="row g-3">
<div class="col-12 col-md-4">
    <div class="card">
        <div class="card-header"><i class="bi bi-cart me-2 text-kiswa"></i>Request Details</div>
        <div class="card-body fs-sm">
            <dl class="row mb-0">
                <dt class="col-5 text-muted">Request No.</dt>
                <dd class="col-7 fw-bold">{{ $procurementRequest->request_number }}</dd>

                <dt class="col-5 text-muted">Date</dt>
                <dd class="col-7">{{ $procurementRequest->request_date->format('d M Y') }}</dd>

                <dt class="col-5 text-muted">Requested By</dt>
                <dd class="col-7">{{ $procurementRequest->requestedBy->name ?? 'N/A' }}</dd>

                <dt class="col-5 text-muted">Supplier</dt>
                <dd class="col-7">{{ $procurementRequest->supplier->name ?? 'Not specified' }}</dd>

                <dt class="col-5 text-muted">Status</dt>
                <dd class="col-7">
                    <span class="badge bg-{{ $procurementRequest->status_color }}">
                        {{ ucfirst($procurementRequest->status) }}
                    </span>
                </dd>

                @if($procurementRequest->notes)
                <dt class="col-5 text-muted">Notes</dt>
                <dd class="col-7">{{ $procurementRequest->notes }}</dd>
                @endif
            </dl>

            @if(auth()->user()->isAdmin() || auth()->user()->isStoresInCharge())
            <hr>
            <form method="POST" action="{{ route('procurement.updateStatus', $procurementRequest) }}">
                @csrf @method('PATCH')
                <label class="form-label fw-semibold">Update Status</label>
                <select name="status" class="form-select form-select-sm mb-2">
                    <option value="draft"     {{ $procurementRequest->status=='draft'     ? 'selected':'' }}>Draft</option>
                    <option value="submitted" {{ $procurementRequest->status=='submitted' ? 'selected':'' }}>Submitted</option>
                    <option value="approved"  {{ $procurementRequest->status=='approved'  ? 'selected':'' }}>Approved</option>
                    <option value="fulfilled" {{ $procurementRequest->status=='fulfilled' ? 'selected':'' }}>Fulfilled</option>
                </select>
                <button type="submit" class="btn btn-kiswa btn-sm w-100">Update Status</button>
            </form>
            @endif
        </div>
    </div>
</div>

<div class="col-12 col-md-8">
    <div class="card">
        <div class="card-header"><i class="bi bi-list-ul me-2 text-kiswa"></i>Requested Items ({{ $procurementRequest->items->count() }})</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Drug</th>
                            <th>Category</th>
                            <th class="text-center">Current Stock</th>
                            <th class="text-center">Reorder Lvl</th>
                            <th class="text-center">Qty Requested</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($procurementRequest->items as $i => $item)
                        <tr>
                            <td class="text-muted fs-xs">{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold fs-sm">{{ $item->drug->name }}</div>
                                <div class="text-muted" style="font-size:.7rem;">{{ $item->drug->strength }} &bull; {{ ucfirst($item->drug->formulation) }}</div>
                            </td>
                            <td class="fs-xs text-muted">{{ Str::limit($item->drug->category, 25) }}</td>
                            <td class="text-center fs-sm {{ $item->current_stock <= $item->reorder_level ? 'text-danger fw-bold' : '' }}">
                                {{ number_format($item->current_stock) }}
                            </td>
                            <td class="text-center fs-sm text-muted">{{ number_format($item->reorder_level) }}</td>
                            <td class="text-center fs-sm fw-bold text-kiswa">{{ number_format($item->quantity_requested) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white fs-xs text-muted">
            Generated by {{ $procurementRequest->requestedBy->name ?? 'N/A' }} on {{ $procurementRequest->created_at->format('d M Y H:i') }}
            &mdash; Kiswa Health Centre III
        </div>
    </div>
</div>
</div>
@endsection
