@extends('layouts.app')
@section('title', 'Register New Drug')
@section('page-title', 'Register New Drug')
@section('breadcrumb')
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <i class="bi bi-chevron-right"></i>
    <a href="{{ route('drugs.index') }}">Drug Register</a>
    <i class="bi bi-chevron-right"></i>
    New Drug
@endsection

@section('content')

<div class="page-header">
    <div class="page-header-title">
        <h2>Register New Drug</h2>
        <p>Add a new medicine to the Kiswa Health drug register.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('drugs.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Register
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-8">
        <div class="card animate-fade-up">
            <div class="card-header">
                <div class="card-header-icon" style="background:#f0fdf4;color:#16a34a;">
                    <i class="bi bi-capsule"></i>
                </div>
                Drug Information
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('drugs.store') }}" class="needs-validation" novalidate>
                    @csrf

                    <p class="form-section-title">Basic Details</p>

                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Generic Name <span class="text-danger">*</span></label>
                            <input type="text" name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   placeholder="e.g. Amoxicillin" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Brand / Trade Name</label>
                            <input type="text" name="brand_name"
                                   class="form-control @error('brand_name') is-invalid @enderror"
                                   value="{{ old('brand_name') }}"
                                   placeholder="e.g. Amoxil">
                            @error('brand_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category"
                                    class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">— Select category —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
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
                                <option value="{{ $f }}" {{ old('formulation') == $f ? 'selected' : '' }}>
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
                                   value="{{ old('strength') }}"
                                   placeholder="e.g. 500mg, 250mg/5ml">
                            @error('strength')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Unit of Measure <span class="text-danger">*</span></label>
                            <input type="text" name="unit_of_measure"
                                   class="form-control @error('unit_of_measure') is-invalid @enderror"
                                   value="{{ old('unit_of_measure') }}"
                                   placeholder="e.g. tablets, vials, ml" required>
                            @error('unit_of_measure')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Reorder Level <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="reorder_level"
                                       class="form-control @error('reorder_level') is-invalid @enderror"
                                       value="{{ old('reorder_level', 50) }}"
                                       min="1" required>
                                <span class="input-group-text">units</span>
                            </div>
                            @error('reorder_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="fs-xs text-muted mt-1">
                                <i class="bi bi-info-circle me-1"></i>Minimum stock level before alert is triggered.
                            </div>
                        </div>
                    </div>

                    <p class="form-section-title">Notes</p>

                    <div class="mb-4">
                        <label class="form-label">Additional Notes</label>
                        <textarea name="notes" rows="3"
                                  class="form-control @error('notes') is-invalid @enderror"
                                  placeholder="Storage requirements, special handling, clinical notes…">{{ old('notes') }}</textarea>
                        @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('drugs.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-kiswa">
                            <i class="bi bi-check2-circle me-1"></i>Register Drug
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card animate-fade-up stagger-2">
            <div class="card-header">
                <div class="card-header-icon" style="background:#dbeafe;color:#2563eb;">
                    <i class="bi bi-info-circle"></i>
                </div>
                Registration Tips
            </div>
            <div class="card-body fs-sm">
                <ul class="text-secondary ps-3 mb-0" style="line-height:1.9;">
                    <li>Use the official <strong>generic/INN name</strong> as the primary name.</li>
                    <li>Specify strength exactly as labelled (e.g. <em>500mg</em>, <em>250mg/5ml</em>).</li>
                    <li>Set the <strong>reorder level</strong> to reflect your typical consumption for 1–2 months.</li>
                    <li>After registering a drug, use <strong>Receive Stock</strong> to add the first batch.</li>
                    <li>EMHS Uganda essential medicines list is used for category classification.</li>
                </ul>
            </div>
        </div>

        <div class="card mt-3 animate-fade-up stagger-3">
            <div class="card-header">
                <div class="card-header-icon" style="background:#fef3c7;color:#d97706;">
                    <i class="bi bi-tags"></i>
                </div>
                Formulation Types
            </div>
            <div class="card-body p-0">
                @foreach($formulations as $f)
                <div class="d-flex align-items-center px-3 py-2 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:#f1f5f9!important;">
                    <span class="badge me-2" style="background:#f1f5f9;color:#475569;font-weight:600;font-size:.68rem;">
                        {{ ucfirst($f) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
