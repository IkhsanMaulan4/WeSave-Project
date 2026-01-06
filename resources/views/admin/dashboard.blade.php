@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Admin Dashboard</h2>
            <p class="text-muted mb-0">Overview laporan dan aktivitas user.</p>
        </div>
        <div>
            <span class="badge bg-primary rounded-pill px-3 py-2">
                <i class="bi bi-calendar-event me-1"></i> {{ date('d M Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3 text-primary">
                        <span class="material-symbols-outlined fs-2">group</span>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Total Users</h6>
                        <h3 class="fw-bold mb-0">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3 text-success">
                        <span class="material-symbols-outlined fs-2">receipt_long</span>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Total Transaksi</h6>
                        <h3 class="fw-bold mb-0">{{ $totalTransactions }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3 text-warning">
                        <span class="material-symbols-outlined fs-2">payments</span>
                    </div>
                    <div>
                        <h6 class="text-muted text-uppercase fw-bold small mb-1">Volume Uang</h6>
                        <h3 class="fw-bold mb-0">Rp {{ number_format($totalVolume, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 py-3 rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Pengguna Terbaru</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-muted text-uppercase small fw-bold">User</th>
                        <th class="py-3 text-muted text-uppercase small fw-bold">Email</th>
                        <th class="py-3 text-muted text-uppercase small fw-bold">Role</th>
                        <th class="pe-4 py-3 text-muted text-uppercase small fw-bold text-end">Bergabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <span class="fw-bold">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <span class="fw-semibold text-dark">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 rounded-pill">
                                    <span class="material-symbols-outlined align-middle fs-6 me-1">shield_person</span> Admin
                                </span>
                            @else
                                <span class="badge bg-info bg-opacity-10 text-info border border-info px-3 rounded-pill">User</span>
                            @endif  
                        </td>
                        <td class="pe-4 text-muted text-end">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">Belum ada user yang mendaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
