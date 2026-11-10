@extends('layouts.app')
@section('title','User Management')
@section('page-title','User Management')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-muted fs-sm mb-0">System user accounts and role assignments.</p>
    <a href="{{ route('users.create') }}" class="btn btn-kiswa btn-sm"><i class="bi bi-person-plus me-1"></i>Add User</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Phone</th><th class="text-center">Status</th><th class="text-center">Actions</th></tr></thead>
                <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="fw-semibold fs-sm">{{ $user->name }}</td>
                    <td class="fs-sm text-muted">{{ $user->email }}</td>
                    <td><span class="badge bg-{{ ['administrator'=>'danger','stores_in_charge'=>'warning text-dark','pharmacy_technician'=>'info text-dark'][$user->role] ?? 'secondary' }}">{{ $user->role_label }}</span></td>
                    <td class="fs-sm text-muted">{{ $user->phone ?? '—' }}</td>
                    <td class="text-center"><span class="badge {{ $user->is_active?'bg-success':'bg-secondary' }}">{{ $user->is_active?'Active':'Inactive' }}</span></td>
                    <td class="text-center">
                        <a href="{{ route('users.edit',$user) }}" class="btn btn-xs btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">No users found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
