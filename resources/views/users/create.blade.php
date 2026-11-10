@extends('layouts.app')
@section('title','Add User')
@section('page-title','Add User')
@section('content')
<div class="row justify-content-center"><div class="col-12 col-lg-6">
<div class="mb-3"><a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="card">
    <div class="card-header"><i class="bi bi-person-plus me-2 text-kiswa"></i>New System User</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.store') }}" class="needs-validation" novalidate>
            @csrf
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-12"><label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-12 col-md-6"><label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-12 col-md-6"><label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="8"></div>
                <div class="col-12 col-md-6"><label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="">— Select role —</option>
                        <option value="administrator" {{ old('role')=='administrator'?'selected':'' }}>Administrator</option>
                        <option value="stores_in_charge" {{ old('role')=='stores_in_charge'?'selected':'' }}>Stores In-Charge</option>
                        <option value="pharmacy_technician" {{ old('role')=='pharmacy_technician'?'selected':'' }}>Pharmacy Technician</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                <div class="col-12 col-md-6"><label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"></div>
            </div>
            <hr class="my-3">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-kiswa"><i class="bi bi-check-circle me-1"></i>Create User</button>
            </div>
        </form>
    </div>
</div>
</div></div>
@endsection
