@extends('layouts.app')

@section('title', __('messages.goals_title'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">{{ __('messages.goals_title') }}</h2>
        <p class="text-muted">{{ __('messages.goals_subtitle') }}</p>
    </div>
    <button class="btn btn-success fw-bold px-4 py-2 rounded-4 d-flex align-items-center gap-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#addGoalModal" style="background-color: #13ec5b; border: none; color: #000;">
        <span class="material-symbols-outlined">add_task</span>
        {{ __('messages.create_goal_btn') }}
    </button>
</div>

@if(session('success'))
<div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 mb-4">
    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 mb-4">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row g-4">
    @forelse($goals as $goal)
    @php
        $percentage = $goal->target_amount > 0 ? ($goal->current_amount / $goal->target_amount) * 100 : 0;
        $percentage = min($percentage, 100); // Mentok di 100%
    @endphp

    <div class="col-md-6 col-xl-4">
        <div class="card card-custom h-100 border-0 p-4 position-relative">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="bg-info bg-opacity-10 p-3 rounded-3 text-info">
                    <span class="material-symbols-outlined fs-3">flag</span>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-light text-primary rounded-circle p-2"
                            data-bs-toggle="modal" data-bs-target="#editGoalModal{{ $goal->id }}"
                            title="{{ __('messages.edit_goal_tooltip') }}">
                        <span class="material-symbols-outlined fs-6">edit</span>
                    </button>

                    <form action="{{ route('goals.destroy', $goal->id) }}" method="POST" onsubmit="return confirm('{{ __('messages.delete_goal_confirm') }}');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-light text-danger rounded-circle p-2" title="{{ __('messages.delete') }}">
                            <span class="material-symbols-outlined fs-6">delete</span>
                        </button>
                    </form>
                </div>
            </div>

            <h5 class="fw-bold text-dark mb-1">{{ $goal->name }}</h5>
            <p class="text-muted small mb-3">{{ __('messages.goal_target_label') }} Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</p>

            <div class="d-flex justify-content-between align-items-end mb-1">
                <span class="fw-bold text-dark">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</span>
                <span class="small fw-bold {{ $percentage >= 100 ? 'text-success' : 'text-muted' }}">{{ number_format($percentage, 0) }}%</span>
            </div>
            <div class="progress rounded-pill mb-4" style="height: 10px;">
                <div class="progress-bar rounded-pill {{ $percentage >= 100 ? 'bg-success' : 'bg-info' }}" role="progressbar" style="width: {{ $percentage }}%"></div>
            </div>

            @if($percentage < 100)
            <button class="btn btn-light w-100 fw-bold d-flex justify-content-center align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#allocateModal{{ $goal->id }}">
                <span class="material-symbols-outlined fs-6">savings</span>
                {{ __('messages.fill_savings_btn') }}
            </button>
            @else
            <div class="btn btn-success bg-opacity-10 text-success w-100 fw-bold border-0">
                <span class="material-symbols-outlined align-middle me-1">check_circle</span> {{ __('messages.goal_achieved_badge') }}
            </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="allocateModal{{ $goal->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">{{ __('messages.allocate_title') }} "{{ $goal->name }}"</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('goals.allocate', $goal->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info small">
                            {{ __('messages.allocate_alert') }}
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">{{ __('messages.source_wallet_label') }}</label>
                            <select name="wallet_id" class="form-select rounded-3 py-2" required>
                                @foreach($wallets as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }} ({{ __('messages.wallet_balance_info') }} Rp {{ number_format($w->balance,0,',','.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">{{ __('messages.saving_amount_label') }}</label>
                            <input type="number" name="amount" class="form-control rounded-3 py-2" placeholder="0" required>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-3 fw-bold text-muted" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-info text-white rounded-3 fw-bold px-4">{{ __('messages.save_now_btn') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editGoalModal{{ $goal->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">{{ __('messages.edit_goal_title') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('goals.update', $goal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">{{ __('messages.goal_name_label') }}</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" value="{{ $goal->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">{{ __('messages.target_amount_label') }}</label>
                            <input type="number" name="target_amount" class="form-control rounded-3 py-2" value="{{ $goal->target_amount }}" required>
                            <small class="text-muted d-block mt-1" style="font-size: 11px;">{{ __('messages.edit_goal_warning') }}</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">{{ __('messages.deadline_label') }}</label>
                            <input type="date" name="deadline" class="form-control rounded-3 py-2" value="{{ $goal->deadline ? \Carbon\Carbon::parse($goal->deadline)->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-3 fw-bold text-muted" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-primary rounded-3 fw-bold px-4">{{ __('messages.save_changes_btn') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @empty
    <div class="col-12 text-center py-5">
        <div class="bg-light d-inline-block p-4 rounded-circle mb-3">
            <span class="material-symbols-outlined fs-1 text-muted">track_changes</span>
        </div>
        <h5 class="fw-bold text-muted">{{ __('messages.empty_goals_title') }}</h5>
        <p class="text-muted small">{{ __('messages.empty_goals_desc') }}</p>
    </div>
    @endforelse
</div>

<div class="modal fade" id="addGoalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">{{ __('messages.create_goal_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('goals.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('messages.goal_name_label') }}</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2" placeholder="{{ __('messages.goal_placeholder') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('messages.target_amount_label') }}</label>
                        <input type="number" name="target_amount" class="form-control rounded-3 py-2" placeholder="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('messages.target_date_label') }}</label>
                        <input type="date" name="deadline" class="form-control rounded-3 py-2">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3 fw-bold text-muted" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-success rounded-3 fw-bold px-4" style="background-color: #13ec5b; border: none; color: #000;">{{ __('messages.save_goal_btn') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
