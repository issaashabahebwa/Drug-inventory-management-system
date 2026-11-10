@extends('layouts.app')
@section('title', 'Add Supplier')
@section('page-title', 'Add Supplier')

@section('content')
<div class="row justify-content-center">
<div class="col-12 col-lg-7">
<div class="mb-3"><a href="{{ route('suppliers.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="card">
    <div class="card-header"><i class="bi bi-building me-2 text-kiswa"></i>New Supplier</div>
    <div class="card-body">
        <form method="POST" action="{{ route('suppliers.store') }}" class="needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required placeholder="e.g. National Medical Stores">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label">Supplier Code</label>
                    <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code') }}" placeholder="e.g. NMS-001">
                    @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control"
                           value="{{ old('contact_person') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                </div>
            </div>
            <hr class="my-3">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-kiswa"><i class="bi bi-check-circle me-1"></i>Add Supplier</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
