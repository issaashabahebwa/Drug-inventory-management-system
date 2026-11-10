@extends('layouts.app')
@section('title', 'Edit Drug — ' . $drug->name)
@section('page-title', 'Edit Drug')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('drugs.index') }}">Drug Register</a>
    <i class="bi bi-chevron-right"></i>
    Edit
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Edit Drug</h2>
        <p>Update the details for <strong>{{ $drug->name }}</strong>.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('drugs.show', $drug) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-eye me-1"></i> View Drug
        </a>
        <a href="{{ route('drugs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Register
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card animate-fade-up">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-pencil-square"></i>
                </div>
                Update Drug Information
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('drugs.update', $drug) }}" class="needs-validation" novalidate>
                    @csrf @method('PUT')

                    <p class="form-section-title">Basic Details</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Generic Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $drug->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Brand / Trade Name</label>
                            <input type="text" name="brand_name"
                                   class="form-control @error('brand_name') is-invalid @enderror"
                                   value="{{ old('brand_name', $drug->brand_name) }}">
                            @error('brand_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category"
                                    class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">— Select category —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $drug->category) == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                                @endforeach
                            </select>
                            @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Formulation <span class="text-danger">*</span></label>
                            <select name="formulation"
                                    class="form-select @error('formulation') is-invalid @enderror" required>
                                <option value="">— Select formulation —</option>
                                @foreach($formulations as $f)
                                <option value="{{ $f }}" {{ old('formulation', $drug->formulation) == $f ? 'selected' : '' }}>
                                    {{ ucfirst($f) }}
                                </option>
                                @endforeach
                            </select>
                            @error('formulation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <p class="form-section-title">Strength &amp; Quantity</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-4">
                            <label class="form-label">Strength / Concentration</label>
                            <input type="text" name="strength"
                                   class="form-control @error('strength') is-invalid @enderror"
                                   value="{{ old('strength', $drug->strength) }}">
                            @error('strength')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Unit of Measure <span class="text-danger">*</span></label>
                            <input type="text" name="unit_of_measure"
                                   class="form-control @error('unit_of_measure') is-invalid @enderror"
                                   value="{{ old('unit_of_measure', $drug->unit_of_measure) }}" required>
                            @error('unit_of_measure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Reorder Level <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="reorder_level"
                                       class="form-control @error('reorder_level') is-invalid @enderror"
                                       value="{{ old('reorder_level', $drug->reorder_level) }}"
                                       min="1" required>
                                <span class="input-group-text">units</span>
                            </div>
                            @error('reorder_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <p class="form-section-title">Status &amp; Notes</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Drug Status</label>
                            <div class="d-flex gap-3 mt-1">
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="1"
                                           {{ old('is_active', $drug->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                                           style="accent-color:var(--k-green);">
                                    <span class="fs-sm">Active</span>
                                </label>
                                <label class="d-flex align-items-center gap-2 cursor-pointer">
                                    <input type="radio" name="is_active" value="0"
                                           {{ old('is_active', $drug->is_active ? '1' : '0') == '0' ? 'checked' : '' }}
                                           style="accent-color:var(--k-red);">
                                    <span class="fs-sm">Inactive</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" rows="3"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Storage requirements, special handling…">{{ old('notes', $drug->notes) }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('drugs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-kiswa">
                            <i class="bi bi-check2-circle me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-info-circle"></i>
                </div>
                Current Status
            </div>
            <div class="card-body">
                <ul class="info-list">
                    <li>
                        <span class="info-key">Stock Status</span>
                        @php $sm=['adequate'=>'green','moderate'=>'teal','low'=>'amber','out_of_stock'=>'red']; @endphp
                        <span class="status-pill {{ $sm[$drug->stock_status] ?? 'gray' }}">{{ $drug->stock_status_label }}</span>
                    </li>
                    <li>
                        <span class="info-key">In Stock</span>
                        <span class="info-val">{{ number_format($drug->total_quantity) }} {{ $drug->unit_of_measure }}</span>
                    </li>
                    <li>
                        <span class="info-key">Reorder Level</span>
                        <span class="info-val">{{ number_format($drug->reorder_level) }}</span>
                    </li>
                    <li>
                        <span class="info-key">Registered</span>
                        <span class="info-val">{{ $drug->created_at->format('d M Y') }}</span>
                    </li>
                    <li>
                        <span class="info-key">Last Updated</span>
                        <span class="info-val">{{ $drug->updated_at->diffForHumans() }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mt-3 animate-fade-up stagger-3">
            <div class="card-header">
                <div class="card-header-icon" style="background:#ede9fe;color:#7c3aed;">
                    <i class="bi bi-box-seam"></i>
                </div>
                Quick Actions
            </div>
            <div class="card-body d-flex flex-column gap-2">
                <a href="{{ route('stock.create', ['drug_id' => $drug->id]) }}" class="btn btn-sm btn-outline-success">
                    <i class="bi bi-plus-circle me-1"></i>Receive New Stock
                </a>
                <a href="{{ route('dispensing.create', ['drug_id' => $drug->id]) }}" class="btn btn-sm btn-outline-kiswa">
                    <i class="bi bi-clipboard2-pulse me-1"></i>Dispense This Drug
                </a>
                <a href="{{ route('drugs.show', $drug) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-eye me-1"></i>View Full Details
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
