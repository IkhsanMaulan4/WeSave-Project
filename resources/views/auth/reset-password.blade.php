<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>{{ __('messages.reset_password_title') }} - WeSave</title>
    <link href="{{ asset('bootstrap5.2/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card border-0 shadow-sm p-4" style="max-width: 400px; width: 100%; border-radius: 16px;">
    <div class="text-center mb-4">
        <h4 class="fw-bold">{{ __('messages.create_new_password_header') }}</h4>
    </div>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label small fw-bold">{{ __('messages.email_address_label') }}</label>
            <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">{{ __('messages.new_password') }}</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">{{ __('messages.confirm_password_label') }}</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100 text-white fw-bold">{{ __('messages.reset_password_title') }}</button>
    </form>
</div>

</body>
</html>
