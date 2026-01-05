@extends('layouts.app')

@section('title', __('messages.account_settings'))

@section('content')
<div class="container py-4">
    <div class="row g-4">

        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush rounded-4 overflow-hidden">
                        <a href="#" class="list-group-item list-group-item-action p-3 active border-0 d-flex align-items-center gap-3" style="background-color: #eefdf3; color: #13ec5b; font-weight: 600;">
                            <span class="material-symbols-outlined fs-5">person</span>
                            {{ __('messages.menu_general') }}
                        </a>

                        <button type="button" class="list-group-item list-group-item-action p-3 border-0 d-flex align-items-center gap-3 text-danger bg-transparent w-100" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <span class="material-symbols-outlined fs-5">delete</span>
                            {{ __('messages.menu_delete_account') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9">

            @if(session('success'))
            <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 mb-4 fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 mb-4">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">{{ __('messages.general_info') }}</h5>
                    <p class="text-muted small mb-4">{{ __('messages.general_info_desc') }}</p>

                    <div class="d-flex align-items-center gap-4 mb-4 p-3 bg-light rounded-4 border border-light position-relative">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=13ec5b&color=fff&size=128' }}"
                                class="rounded-circle shadow-sm object-fit-cover"
                                width="80" height="80"
                                alt="Avatar"
                                id="avatarPreview">

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="flex-grow-1 d-flex align-items-center gap-3">
                            @csrf @method('PUT')
                            <input type="hidden" name="name" value="{{ $user->name }}">

                            <div class="flex-grow-1">
                                <label class="btn btn-white border shadow-sm fw-bold small mb-1 bg-white text-dark" for="avatarUpload" style="cursor: pointer;">
                                    <span class="material-symbols-outlined align-middle fs-6 me-1">upload</span> {{ __('messages.change_photo') }}
                                </label>
                                <input type="file" id="avatarUpload" name="avatar" class="d-none" accept="image/*" onchange="this.form.submit()">
                                <div class="small text-muted" style="font-size: 11px;">{{ __('messages.photo_help_text') }}</div>
                            </div>
                        </form>

                        @if($user->avatar)
                        <form action="{{ route('profile.avatar.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger border-0 p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;" onclick="return confirm('{{ __('messages.delete_photo_confirm') }}')">
                                <span class="material-symbols-outlined fs-5">delete</span>
                            </button>
                        </form>
                        @endif
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">{{ __('messages.full_name_label') }}</label>
                                <input type="text" name="name" class="form-control py-2 rounded-3" value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">{{ __('messages.email_address_label') }}</label>
                                <input type="email" class="form-control py-2 rounded-3 bg-light" value="{{ $user->email }}" readonly style="cursor: not-allowed;">
                            </div>
                        </div>

                        <hr class="my-4 border-light">
                        <h5 class="fw-bold mb-1">{{ __('messages.security_title') }}</h5>
                        <p class="text-muted small mb-4">{{ __('messages.security_desc') }}</p>

                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">{{ __('messages.current_password') }}</label>
                                <input type="password" name="current_password" class="form-control py-2 rounded-3">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">{{ __('messages.new_password') }}</label>
                                <input type="password" name="new_password" class="form-control py-2 rounded-3">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">{{ __('messages.confirm_new_password') }}</label>
                                <input type="password" name="new_password_confirmation" class="form-control py-2 rounded-3">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-success rounded-3 fw-bold px-4 text-dark" style="background-color: #13ec5b; border: none;">
                                {{ __('messages.save_changes_btn') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger">{{ __('messages.delete_account_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="text-muted small">
                        {{ __('messages.delete_account_warn') }}
                    </p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">{{ __('messages.confirm_pass_label') }}</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="{{ __('messages.password_placeholder') }}" required>
                        @error('password', 'userDeletion')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3 fw-bold text-muted" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-danger rounded-3 fw-bold px-4">{{ __('messages.btn_delete_account') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('avatarPreview');
            output.src = reader.result;
        };
        if(event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection
