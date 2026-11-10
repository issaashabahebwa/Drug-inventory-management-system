@extends('layouts.app')
@section('title','Edit User')
@section('page-title','Edit User')
@section('content')
<div class="row justify-content-center"><div class="col-12 col-lg-6">
<div class="mb-3"><a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a></div>
<div class="card mb-3">
    <div class="card-header"><i class="bi bi-pencil me-2 text-kiswa"></i>Edit: {{ $user->name }}</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.update',$user) }}" class="needs-validation" novalidate>
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name',$user->name) }}" required></div>
                <div class="col-12"><label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="{{ old('email',$user->email) }}" required></div>
                <div class="col-12 col-md-6"><label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role" class="form-select" required>
                        <option value="administrator" {{ old('role',$user->role)=='administrator'?'selected':'' }}>Administrator</option>
                        <option value="stores_in_charge" {{ old('role',$user->role)=='stores_in_charge'?'selected':'' }}>Stores In-Charge</option>
                        <option value="pharmacy_technician" {{ old('role',$user->role)=='pharmacy_technician'?'selected':'' }}>Pharmacy Technician</option>
                    </select></div>
                <div class="col-12 col-md-6"><label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone',$user->phone) }}"></div>
                <div class="col-12"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active',$user->is_active)?'checked':'' }}>
                    <label class="form-check-label">Account is active</label>
                </div></div>
            </div>
            <hr class="my-3">
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-kiswa"><i class="bi bi-check-circle me-1"></i>Save Changes</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header"><i class="bi bi-key me-2 text-muted"></i>Reset Password</div>
    <div class="card-body">
        <form method="POST" action="{{ route('users.reset-password',$user) }}" class="needs-validation" novalidate>
            @csrf @method('PATCH')
            <div class="row g-3">
                <div class="col-12 col-md-6"><label class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" required minlength="8"></div>
                <div class="col-12 col-md-6"><label class="form-label">Confirm</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="8"></div>
            </div>
            <hr class="my-3">
            <button type="submit" class="btn btn-outline-warning btn-sm"
                    data-confirm="Reset password for {{ $user->name }}?">
                <i class="bi bi-key me-1"></i>Reset Password
            </button>
        </form>
    </div>
</div>
</div></div>
@endsection
