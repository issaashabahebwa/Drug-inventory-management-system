@extends('layouts.app')
@section('title', 'Procurement Requests')
@section('page-title', 'Procurement Requests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted fs-sm mb-0">Drug requisition and purchase order tracking.</p>
    <a href="{{ route('procurement.create') }}" class="btn btn-kiswa btn-sm">
        <i class="bi bi-cart-plus me-1"></i>New Requisition
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Request No.</th>
                        <th>Date</th>
                        <th>Requested By</th>
                        <th>Supplier</th>
                        <th class="text-center">Items</th>
                        <th class="text-center">Status</th>
                        <th class="text-center no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $pr)
                    <tr>
                        <td class="fs-sm fw-semibold">{{ $pr->request_number }}</td>
                        <td class="fs-sm">{{ $pr->request_date->format('d/m/Y') }}</td>
                        <td class="fs-sm text-muted">{{ $pr->requestedBy->name ?? 'N/A' }}</td>
                        <td class="fs-sm text-muted">{{ $pr->supplier->name ?? 'Not specified' }}</td>
                        <td class="text-center fs-sm">{{ $pr->items->count() }}</td>
                        <td class="text-center">
                            <span class="badge bg-{{ $pr->status_color }}">{{ ucfirst($pr->status) }}</span>
                        </td>
                        <td class="text-center no-print">
                            <a href="{{ route('procurement.show', $pr) }}" class="btn btn-xs btn-sm btn-outline-secondary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-cart fs-2 d-block mb-2"></i>
                            No procurement requests yet.
                            <a href="{{ route('procurement.create') }}">Generate one from low-stock drugs</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($requests->hasPages())
    <div class="card-footer bg-white no-print">{{ $requests->links() }}</div>
    @endif
</div>
@endsection
