@extends('layouts.app')

@section('title', __('messages.my_wallets'))

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
    <div>
        <h2 class="fw-bold text-dark mb-1">{{ __(('messages.my_wallets')) }}</h2>
        <p class="text-muted">{{ __(('messages.manage')) }}</p>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 mb-4 d-flex align-items-center gap-2">
    <span class="material-symbols-outlined">check_circle</span>
    {{ session('success') }}
</div>
@endif

<div class="card card-custom border-0 p-4 mb-5 bg-white position-relative overflow-hidden">
    <div class="position-absolute top-0 end-0 bg-success opacity-10 rounded-circle" style="width: 200px; height: 200px; filter: blur(60px); transform: translate(30%, -30%);"></div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-end position-relative" style="z-index: 1;">
        <div>
            <div class="d-flex align-items-center gap-2 text-muted mb-2">
                <span class="material-symbols-outlined fs-5">account_balance</span>
                <span class="fw-bold small">{{ __(('messages.initial_balance')) }}</span>
            </div>
            <h1 class="display-5 fw-bolder text-dark mb-0">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h1>
        </div>
        <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mt-3 mt-md-0">
            <span class="material-symbols-outlined fs-6 align-middle me-1">trending_up</span>
            0% {{ __(('messages.this_month')) }}
        </div>
    </div>
</div>

<div class="d-flex align-items-center gap-2 mb-4">
    <h5 class="fw-bold text-dark mb-0">{{ __(('messages.my_wallets')) }}</h5>
    <span class="badge bg-light text-muted border rounded-pill">{{ $wallets->count() }} {{ __(('messages.account')) }}</span>
</div>

<div class="row g-4">
    @foreach($wallets as $wallet)
    <div class="col-md-6 col-xl-4">
        <div class="card card-custom h-100 border-0 p-3 position-relative group-hover">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
                            <span class="material-symbols-outlined fs-3">{{ $wallet->icon ?? 'account_balance_wallet' }}</span>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-0">{{ $wallet->name }}</h5>
                            <small class="text-muted">ID: {{ $wallet->id }}</small>
                        </div>
                    </div>
                    <form action="{{ route('wallets.destroy', $wallet->id) }}" method="POST" onsubmit="return confirm('{{ __(('messages.delete_wallet_ask')) }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle p-2" title="Hapus">
                            <span class="material-symbols-outlined fs-5">delete</span>
                        </button>
                    </form>
                </div>

                <div class="mb-3">
                    <small class="text-muted fw-bold">{{ __(('messages.Current Balance')) }}</small>
                    <h3 class="fw-bold text-dark mt-1">Rp {{ number_format($wallet->balance, 0, ',', '.') }}</h3>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <small class="text-muted d-flex align-items-center gap-1">
                        <span class="material-symbols-outlined fs-6">sync</span> Updated Now
                    </small>
                    <span class="badge bg-success rounded-circle p-1" style="width: 10px; height: 10px;"></span>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="col-md-6 col-xl-4">
        <button class="btn btn-outline-secondary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-5 rounded-4 border-2 border-dashed" data-bs-toggle="modal" data-bs-target="#addWalletModal" style="border-style: dashed !important; min-height: 250px;">
            <div class="bg-light p-3 rounded-circle mb-3 text-muted">
                <span class="material-symbols-outlined fs-2">add</span>
            </div>
            <h5 class="fw-bold text-dark">{{ __(('messages.add_new_wallets')) }}</h5>
            <small class="text-muted">{{ __(('messages.details')) }}</small>
        </button>
    </div>
</div>

<div class="modal fade" id="addWalletModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">{{ __(('messages.add_wallets')) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('wallets.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __(('messages.wallets_name')) }}</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2" placeholder="{{ __(('messages.wallet_placeholder')) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __(('messages.initial_balance')) }} (Rp)</label>
                        <input type="number" name="balance" class="form-control rounded-3 py-2" placeholder="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3 fw-bold text-muted" data-bs-dismiss="modal">{{ __(('messages.cancel')) }}</button>
                    <button type="submit" class="btn btn-success rounded-3 fw-bold px-4" style="background-color: #13ec5b; border: none; color: #000;">{{ __(('messages.save')) }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
