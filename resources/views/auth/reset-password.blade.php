<!DOCTYPE html>
<html lang="id">
<head>
    <title>Reset Password - WeSave</title>
    <link href="{{ asset('bootstrap5.2/css/bootstrap.min.css') }}" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="min-height: 100vh;">

<div class="card border-0 shadow-sm p-4" style="max-width: 400px; width: 100%; border-radius: 16px;">
    <div class="text-center mb-4">
        <h4 class="fw-bold">Buat Password Baru</h4>
    </div>

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label small fw-bold">Email Address</label>
            <input type="email" name="email" class="form-control" value="{{ $email ?? old('email') }}" required readonly>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Password Baru</label>
            <input type="password" name="password" class="form-control" required>
            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label small fw-bold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success w-100 text-white fw-bold">Reset Password</button>
    </form>
</div>

</body>
</html>
