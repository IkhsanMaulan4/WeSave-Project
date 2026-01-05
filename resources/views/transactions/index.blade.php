@extends('layouts.app')

@section('title', __('messages.history_transaction'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">{{ __(('messages.history_transaction')) }}</h2>
        @if(request('search'))
            <p class="text-muted mb-0">
                {{ __(('messages.search_result')) }} <strong>"{{ request('search') }}"</strong>
                <a href="{{ route('transactions.index') }}" class="text-danger text-decoration-none ms-2 small fw-bold">(Reset)</a>
            </p>
        @else
            <p class="text-muted">{{ __(('messages.onboarding_text')) }}</p>
        @endif
    </div>
    <button class="btn btn-success fw-bold px-4 py-2 rounded-4 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTransactionModal" style="background-color: #13ec5b; border: none; color: #000;">
        <span class="material-symbols-outlined">add</span>
        {{ __(('messages.add_transaction')) }}
    </button>
</div>

@if(session('success'))
<div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 mb-4">
    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
</div>
@endif

<div class="card card-custom border-0 p-0 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="p-4 text-muted small fw-bold border-0">{{ __(('messages.transaction_bold')) }}</th>
                    <th class="p-4 text-muted small fw-bold border-0">{{ __(('messages.wallet_bold')) }}</th>
                    <th class="p-4 text-muted small fw-bold border-0">{{ __(('messages.category_bold')) }}</th>
                    <th class="p-4 text-muted small fw-bold border-0">{{ __(('messages.date_bold')) }}</th>
                    <th class="p-4 text-muted small fw-bold border-0 text-end">{{ __(('messages.amount_bold')) }}</th>
                    <th class="p-4 text-muted small fw-bold border-0 text-end">{{ __(('messages.action_bold')) }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                <tr>
                    <td class="p-4 border-bottom-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle p-2 d-flex align-items-center justify-content-center
                                {{ $trx->type == 'income' ? 'bg-success bg-opacity-10 text-success' : ($trx->type == 'expense' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary') }}"
                                style="width: 40px; height: 40px;">
                                <span class="material-symbols-outlined fs-5">
                                    {{ $trx->type == 'income' ? 'arrow_downward' : ($trx->type == 'expense' ? 'arrow_upward' : 'swap_horiz') }}
                                </span>
                            </div>
                            <div>
                                <p class="fw-bold mb-0 text-dark">{{ Str::limit($trx->description, 30) ?: 'Tanpa Keterangan' }}</p>
                                <small class="text-muted">{{ ucfirst($trx->type) }}</small>
                            </div>
                        </div>
                    </td>
                    <td class="p-4 border-bottom-0">
                        <span class="badge bg-light text-dark border">{{ $trx->wallet->name }}</span>
                    </td>
                    <td class="p-4 border-bottom-0">
                        @if($trx->category)
                            <div class="d-flex align-items-center gap-2">
                                <span class="material-symbols-outlined fs-6 text-muted">{{ $trx->category->icon }}</span>
                                <span>{{ $trx->category->name }}</span>
                            </div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="p-4 border-bottom-0 text-muted">
                        {{ $trx->transaction_date->format('d M Y') }}
                    </td>
                    <td class="p-4 border-bottom-0 text-end fw-bold {{ $trx->type == 'income' ? 'text-success' : ($trx->type == 'expense' ? 'text-danger' : 'text-primary') }}">
                        {{ $trx->type == 'expense' ? '-' : '+' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                    </td>
                    <td class="p-4 border-bottom-0 text-end">
                        <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini? Saldo akan dikembalikan.');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-light text-danger rounded-circle p-2">
                                <span class="material-symbols-outlined fs-6">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center p-5 text-muted">
                        <div class="mb-2"><span class="material-symbols-outlined fs-1">receipt_long</span></div>
                        {{ __(('messages.empty_transactions_msg')) }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">{{ __(('messages.add_transaction')) }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('transactions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body pt-4">

                    <div class="d-flex justify-content-center mb-4 bg-light p-1 rounded-3">
                        <input type="radio" class="btn-check" name="type" id="type-expense" value="expense" checked onclick="toggleTransfer(false)">
                        <label class="btn btn-sm w-100 rounded-3 fw-bold" for="type-expense" id="label-expense">{{ __(('messages.expense')) }}</label>

                        <input type="radio" class="btn-check" name="type" id="type-income" value="income" onclick="toggleTransfer(false)">
                        <label class="btn btn-sm w-100 rounded-3 fw-bold" for="type-income" id="label-income">{{ __(('messages.income')) }}</label>

                        <input type="radio" class="btn-check" name="type" id="type-transfer" value="transfer" onclick="toggleTransfer(true)">
                        <label class="btn btn-sm w-100 rounded-3 fw-bold" for="type-transfer" id="label-transfer">{{ __(('messages.transfer')) }}</label>
                    </div>

                    <div class="mb-4 text-center">
                        <label class="small text-muted fw-bold mb-1">{{ __(('messages.nominal_bold')) }} (IDR)</label>
                        <input type="number" name="amount" class="form-control form-control-lg text-center border-0 fw-bolder display-6 shadow-none" placeholder="0" style="font-size: 2rem;" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">{{ __(('messages.wallet')) }}</label>
                            <select name="wallet_id" class="form-select rounded-3 py-2" required>
                                @foreach($wallets as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} (Rp {{ number_format($w->balance,0,',','.') }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6" id="category-wrapper">
                            <label class="form-label small fw-bold text-muted">{{ __(('messages.category')) }}</label>
                            <select name="category_id" class="form-select rounded-3 py-2">
                                <option value="">{{ __(('messages.choose_category')) }}</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 d-none" id="destination-wrapper">
                            <label class="form-label small fw-bold text-muted">{{ __(('messages.to_wallet')) }}</label>
                            <select name="destination_wallet_id" class="form-select rounded-3 py-2">
                                <option value="">{{ __(('messages.select_destination')) }}</option>
                                @foreach($wallets as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="form-label small fw-bold text-muted">{{ __(('messages.date')) }}</label>
                        <input type="date" name="transaction_date" class="form-control rounded-3 py-2" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="mt-3">
                        <label class="form-label small fw-bold text-muted">{{ __(('messages.notes')) }}</label>
                        <textarea name="description" class="form-control rounded-3" rows="2" placeholder="{{ __(('messages.transaction_desc_placeholder')) }}"></textarea>
                    </div>

                    <div class="mt-3">
                        <label class="form-label small fw-bold text-muted">{{ __(('messages.photo_proof')) }} (Opsional)</label>
                        <input type="file" name="proof_image" class="form-control rounded-3">
                    </div>

                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 fw-bold text-muted" data-bs-dismiss="modal">{{ __(('messages.cancel')) }}</button>
                    <button type="submit" class="btn btn-success rounded-3 fw-bold px-4 w-100" style="background-color: #13ec5b; border: none; color: #000;">{{ __(('messages.save_transaction')) }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleTransfer(isTransfer) {
        const catWrapper = document.getElementById('category-wrapper');
        const destWrapper = document.getElementById('destination-wrapper');

        if (isTransfer) {
            catWrapper.classList.add('d-none');
            destWrapper.classList.remove('d-none');
        } else {
            catWrapper.classList.remove('d-none');
            destWrapper.classList.add('d-none');
        }
    }

    const radios = document.querySelectorAll('input[name="type"]');
    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('label.btn').forEach(lbl => {
                lbl.classList.remove('bg-success', 'text-white');
                lbl.classList.add('bg-light', 'text-dark');
            });
            const activeLabel = document.querySelector(`label[for="${this.id}"]`);
            activeLabel.classList.remove('bg-light', 'text-dark');
            activeLabel.classList.add('bg-success', 'text-white');
        });
    });
</script>

<style>

    .btn-check:checked + .btn {
        background-color: #13ec5b !important;
        color: #000 !important;
        border: none;
    }
</style>
@endsection
