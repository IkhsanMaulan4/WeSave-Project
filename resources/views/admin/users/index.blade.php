@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Kelola Pengguna</h2>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary rounded-pill px-4">
            <span class="material-symbols-outlined align-middle fs-6 me-1">add</span> Tambah User
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">User</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Email</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted">Bergabung</th>
                            <th class="py-3 text-uppercase small fw-bold text-muted text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <span class="fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                        @if($user->role == 'admin')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill mt-1" style="font-size: 0.65rem;">Admin</span>
                                        @else
                                            <span class="small text-muted">User ID: #{{ $user->id }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="text-muted">{{ $user->email }}</td>

                            <td class="text-muted">
                                <span class="d-block">{{ $user->created_at->format('d M Y') }}</span>
                                <small>{{ $user->created_at->diffForHumans() }}</small>
                            </td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning text-white rounded-3 px-3">
                                        <span class="material-symbols-outlined align-middle fs-6">edit</span> Edit
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus user ini? Semua data dompet dan transaksi mereka akan hilang permanen!');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger rounded-3 px-3">
                                            <span class="material-symbols-outlined align-middle fs-6">delete</span> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="material-symbols-outlined fs-1 mb-2">sentiment_dissatisfied</span>
                                    <p class="mb-0">Tidak ada user ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-0 py-3 rounded-bottom-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
