<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ __('messages.forgot_password_title') }} - WeSave</title>
    <link href="{{ asset('bootstrap5.2/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card border-0 shadow-sm p-4" style="max-width: 400px; width: 100%; border-radius: 16px;">
    <div class="text-center mb-4">
        <h4 class="fw-bold">{{ __('messages.forgot_password_title') }}?</h4>
        <p class="text-muted small">{{ __('messages.forgot_password_desc') }}</p>
    </div>

    @if (session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-bold">{{ __('messages.email_address_label') }}</label>
            <input type="email" name="email" class="form-control" required>
            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="btn btn-success w-100 text-white fw-bold mb-3">{{ __('messages.send_reset_link_btn') }}</button>
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-decoration-none small text-muted">{{ __('messages.back_to_login') }}</a>
        </div>
    </form>
</div>

</body>
</html>
