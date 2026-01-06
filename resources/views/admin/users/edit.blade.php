@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Edit User: {{ $user->name }}</h5>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-select">
                        <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User Biasa</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password Baru <small class="text-muted fw-normal">(Kosongkan jika tidak ingin mengubah)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">Update</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
