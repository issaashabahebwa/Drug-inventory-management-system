@extends('layouts.app')
@section('title', 'Drug Register')
@section('page-title', 'Drug Register')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    Drug Register
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Drug Register</h2>
        <p>Master list of all registered drugs &amp; essential medicines.</p>
    </div>
    <div class="page-header-actions no-print">
        <a href="{{ route('drugs.create') }}" class="btn btn-kiswa btn-sm">
            <i class="bi bi-plus-circle"></i> Register New Drug
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="filter-bar no-print animate-fade-up">
    <form method="GET" action="{{ route('drugs.index') }}" class="row g-2 align-items-end">
        <div class="col-12 col-md-4">
            <div class="search-input-wrap">
                <i class="bi bi-search"></i>
                <input type="text" name="search" class="form-control form-control-sm"
                       placeholder="Search drug name, brand, category…" value="{{ request('search') }}">
            </div>
        </div>
        <div class="col-6 col-md-2">
            <select name="category" class="form-select form-select-sm">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                    {{ Str::limit($cat, 30) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="formulation" class="form-select form-select-sm">
                <option value="">All Formulations</option>
                @foreach($formulations as $f)
                <option value="{{ $f }}" {{ request('formulation') == $f ? 'selected' : '' }}>
                    {{ ucfirst($f) }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-6 col-md-2">
            <select name="status" class="form-select form-select-sm">
                <option value="">All Stock Levels</option>
                <option value="adequate" {{ request('status')=='adequate' ? 'selected':'' }}>Adequate</option>
                <option value="low"      {{ request('status')=='low'      ? 'selected':'' }}>Low Stock</option>
                <option value="out"      {{ request('status')=='out'      ? 'selected':'' }}>Out of Stock</option>
            </select>
        </div>
        <div class="col-6 col-md-2 d-flex gap-1">
            <button class="btn btn-kiswa btn-sm flex-fill">
                <i class="bi bi-search"></i> Filter
            </button>
            @if(request()->hasAny(['search','category','formulation','status']))
            <a href="{{ route('drugs.index') }}" class="btn btn-outline-secondary btn-sm" title="Clear filters">
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
                <i class="bi bi-capsule"></i>
            </div>
            Drug List
        </div>
        <span class="fs-xs text-muted">
            {{ $drugs->total() }} drug{{ $drugs->total() !== 1 ? 's' : '' }} found
        </span>
    </div>
    <div class="card-body p-0">
        <div class="table-wrapper">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th>Drug Name</th>
                        <th>Category</th>
                        <th>Formulation</th>
                        <th>Strength</th>
                        <th class="text-center">In Stock</th>
                        <th class="text-center">Reorder</th>
                        <th class="text-center">Status</th>
                        <th class="text-center no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drugs as $drug)
                    <tr class="animate-fade-in">
                        <td class="fs-xs text-muted">
                            {{ ($drugs->currentPage() - 1) * $drugs->perPage() + $loop->iteration }}
                        </td>
                        <td>
                            <div class="fw-600 fs-sm">{{ $drug->name }}</div>
                            @if($drug->brand_name)
                            <div class="fs-xs text-muted">{{ $drug->brand_name }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="fs-xs text-secondary" title="{{ $drug->category }}">
                                {{ Str::limit($drug->category, 24) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background:#f1f5f9;color:#475569;font-weight:600;font-size:.68rem;">
                                {{ ucfirst($drug->formulation) }}
                            </span>
                        </td>
                        <td class="fs-sm text-muted">{{ $drug->strength ?? '—' }}</td>
                        <td class="text-center">
                            <div class="fw-700 {{ $drug->total_quantity == 0 ? 'text-danger' : ($drug->isLowStock() ? 'text-warning' : 'text-success') }}"
                                 style="font-size:.9rem;">
                                {{ number_format($drug->total_quantity) }}
                            </div>
                            <div class="fs-xs text-muted">{{ $drug->unit_of_measure }}</div>
                        </td>
                        <td class="text-center fs-sm text-muted">
                            {{ number_format($drug->reorder_level) }}
                        </td>
                        <td class="text-center">
                            @php
                                $statusMap = [
                                    'adequate'    => 'green',
                                    'moderate'    => 'teal',
                                    'low'         => 'amber',
                                    'out_of_stock'=> 'red',
                                ];
                            @endphp
                            <span class="status-pill {{ $statusMap[$drug->stock_status] ?? 'gray' }}">
                                {{ $drug->stock_status_label }}
                            </span>
                        </td>
                        <td class="text-center no-print">
                            <div class="table-actions justify-content-center">
                                <a href="{{ route('drugs.show', $drug) }}"
                                   class="btn btn-sm btn-icon btn-outline-secondary" title="View details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('drugs.edit', $drug) }}"
                                   class="btn btn-sm btn-icon btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('stock.create', ['drug_id' => $drug->id]) }}"
                                   class="btn btn-sm btn-icon btn-outline-success" title="Receive stock">
                                    <i class="bi bi-plus-lg"></i>
                                </a>
                                @if(auth()->user()->isAdmin() || auth()->user()->isStoresInCharge())
                                <form method="POST" action="{{ route('drugs.destroy', $drug) }}" class="d-inline"
                                      onsubmit="return confirm('Deactivate {{ addslashes($drug->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger" title="Deactivate">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="p-0">
                            <div class="empty-state">
                                <div class="empty-state-icon"><i class="bi bi-capsule"></i></div>
                                <h5>No Drugs Found</h5>
                                <p>No drugs match your current filters, or none have been registered yet.</p>
                                <a href="{{ route('drugs.create') }}" class="btn btn-kiswa btn-sm">
                                    <i class="bi bi-plus-circle me-1"></i>Register First Drug
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($drugs->hasPages())
    <div class="card-footer d-flex align-items-center justify-content-between no-print">
        <span class="fs-xs text-muted">
            Showing {{ $drugs->firstItem() }}–{{ $drugs->lastItem() }} of {{ $drugs->total() }} results
        </span>
        {{ $drugs->links() }}
    </div>
    @endif
</div>

@endsection
