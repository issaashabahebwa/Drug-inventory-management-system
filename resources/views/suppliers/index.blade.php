@extends('layouts.app')
@section('title','Suppliers')
@section('page-title','Suppliers')
@section('content')
<div class="page-header">
    <div class="page-header-title"><h2>Suppliers</h2><p>Drug suppliers and delivery partners.</p></div>
    <div class="page-header-actions no-print">
        <a href="{{ route('suppliers.create') }}" class="btn btn-kiswa btn-sm"><i class="bi bi-plus-circle me-1"></i>Add Supplier</a>
    </div>
</div>
<div class="card animate-fade-up">
    <div class="card-header"><div class="card-header-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-building"></i></div>Supplier Directory<span class="ms-auto fs-xs text-muted">{{ $suppliers->count() }} suppliers</span></div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table table-hover mb-0">
                <thead><tr><th>Name</th><th>Code</th><th>Contact</th><th>Phone</th><th>Email</th><th class="text-center">Deliveries</th><th class="text-center">Status</th><th class="no-print text-center">Actions</th></tr></thead>
                <tbody>
                @forelse($suppliers as $s)
                <tr>
                    <td><div class="fw-700 fs-sm">{{ $s->name }}</div>@if($s->address)<div class="fs-xs text-muted">{{ Str::limit($s->address,35) }}</div>@endif</td>
                    <td class="fs-xs text-muted">{{ $s->code ?: '—' }}</td>
                    <td class="fs-sm">{{ $s->contact_person ?: '—' }}</td>
                    <td class="fs-sm">{{ $s->phone ?: '—' }}</td>
                    <td class="fs-xs text-muted">{{ $s->email ?: '—' }}</td>
                    <td class="text-center fw-700 text-kiswa">{{ $s->stock_batches_count }}</td>
                    <td class="text-center"><span class="status-pill {{ $s->is_active ? 'green' : 'gray' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td class="text-center no-print">
                        <div class="table-actions justify-content-center">
                            <a href="{{ route('suppliers.edit', $s) }}" class="btn btn-sm btn-icon btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="p-0"><div class="empty-state"><div class="empty-state-icon"><i class="bi bi-building"></i></div><h5>No Suppliers Yet</h5><p>Add your first supplier to track deliveries.</p><a href="{{ route('suppliers.create') }}" class="btn btn-kiswa btn-sm"><i class="bi bi-plus-circle me-1"></i>Add Supplier</a></div></td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
