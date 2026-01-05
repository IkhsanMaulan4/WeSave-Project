@extends('layouts.app')

@section('title', __('messages.dashboard'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Welcome back, {{ Auth::user()->name }}! 👋</h2>
        <p class="text-muted">{{ __(('messages.summary')) }}</p>
    </div>

</div>

<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card card-custom h-100 p-3 border-0">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success">
                        <span class="material-symbols-outlined">account_balance</span>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                        Active
                    </span>
                </div>
                <div class="mt-3">
                    <p class="text-muted small fw-bold mb-1">{{ __('messages.total_balance') }}</p>
                    <h3 class="fw-bolder mb-0">Rp {{ number_format($totalBalance, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 p-3 border-0">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 text-primary">
                        <span class="material-symbols-outlined">arrow_downward</span>
                    </div>
                    <span class="badge bg-light text-muted rounded-pill px-2 py-1">{{ __(('messages.this_month')) }}</span>
                </div>
                <div class="mt-3">
                    <p class="text-muted small fw-bold mb-1">{{ __('messages.income')}}</p>
                    <h3 class="fw-bolder mb-0 text-success">+ Rp {{ number_format($incomeThisMonth, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom h-100 p-3 border-0">
            <div class="card-body d-flex flex-column justify-content-between">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="bg-warning bg-opacity-10 p-2 rounded-3 text-warning">
                        <span class="material-symbols-outlined">arrow_upward</span>
                    </div>
                    <span class="badge bg-light text-muted rounded-pill px-2 py-1">{{ __(('messages.this_month')) }}</span>
                </div>
                <div class="mt-3">
                    <p class="text-muted small fw-bold mb-1">{{ __('messages.expense') }}</p>
                    <h3 class="fw-bolder mb-0 text-danger">- Rp {{ number_format($expenseThisMonth, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-custom border-0 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">{{ __('messages.recent_transactions') }}</h5>
                <a href="{{ route('transactions.index') }}" class="btn btn-sm btn-light rounded-pill px-3">{{ __('messages.view_all') }}</a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <tbody>
                        @forelse($recentTransactions as $trx)
                        <tr>
                            <td style="width: 50px;">
                                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center
                                    {{ $trx->type == 'income' ? 'bg-success bg-opacity-10 text-success' : ($trx->type == 'expense' ? 'bg-danger bg-opacity-10 text-danger' : 'bg-primary bg-opacity-10 text-primary') }}"
                                    style="width: 40px; height: 40px;">
                                    <span class="material-symbols-outlined fs-5">
                                        {{ $trx->type == 'income' ? 'arrow_downward' : ($trx->type == 'expense' ? 'arrow_upward' : 'swap_horiz') }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <p class="fw-bold mb-0 small text-dark">{{ Str::limit($trx->description, 25) ?: 'Tanpa Keterangan' }}</p>
                                <small class="text-muted" style="font-size: 11px;">
                                    {{ $trx->category->name ?? 'Umum' }} • {{ $trx->transaction_date->format('d M') }}
                                </small>
                            </td>
                            <td class="text-end fw-bold small {{ $trx->type == 'income' ? 'text-success' : ($trx->type == 'expense' ? 'text-danger' : 'text-primary') }}">
                                {{ $trx->type == 'expense' ? '-' : '+' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                <small>{{ __('messages.no_transaction') }}</small>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-custom border-0 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">{{ __(('messages.goals')) }}</h5>
                <a href="{{ route('goals.index') }}" class="btn btn-sm btn-light rounded-circle"><span class="material-symbols-outlined">add</span></a>
            </div>

            <div class="d-flex flex-column gap-4">
                @forelse($goals as $goal)
                @php
                    $percent = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
                @endphp
                <div>
                    <div class="d-flex justify-content-between align-items-end mb-1">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded-3 text-info">
                                <span class="material-symbols-outlined fs-6">flag</span>
                            </div>
                            <div>
                                <p class="fw-bold mb-0 small">{{ $goal->name }}</p>
                                <small class="text-muted" style="font-size: 11px;">Terkumpul: Rp {{ number_format($goal->current_amount/1000, 0) }}k</small>
                            </div>
                        </div>
                        <span class="fw-bold small">{{ number_format($percent, 0) }}%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <span class="material-symbols-outlined fs-1 opacity-25">track_changes</span>
                    <p class="small mb-0">{{ __(('messages.no_goals')) }}</p>
                </div>
                @endforelse

                <div class="alert alert-success d-flex gap-3 mt-2 border-0 bg-opacity-10 align-items-start mb-0" role="alert">
                    <span class="material-symbols-outlined text-success fs-5">psychology</span>
                    <div>
                        <p class="fw-bold small mb-1 text-dark">{{ __(('messages.saving_tips')) }}</p>
                        <small class="text-muted lh-1" style="font-size: 11px;">{{ __(('messages.check_ai')) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
