@extends('layouts.guest')

@section('title', 'Daftar Akun Baru')

@section('content')
<nav class="navbar navbar-expand-lg fixed-top bg-white border-bottom py-3" style="background: rgba(255,255,255,0.9); backdrop-filter: blur(10px);">
    <div class="container-fluid px-4 px-md-5">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <div class="text-success">
                <svg width="32" height="32" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M42.1739 20.1739L27.8261 5.82609C29.1366 7.13663 28.3989 10.1876 26.2002 13.7654C24.8538 15.9564 22.9595 18.3449 20.6522 20.6522C18.3449 22.9595 15.9564 24.8538 13.7654 26.2002C10.1876 28.3989 7.13663 29.1366 5.82609 27.8261L20.1739 42.1739C21.4845 43.4845 24.5355 42.7467 28.1133 40.548C30.3042 39.2016 32.6927 37.3073 35 35C37.3073 32.6927 39.2016 30.3042 40.548 28.1133C42.7467 24.5355 43.4845 21.4845 42.1739 20.1739Z" fill="#13ec5b"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.24189 26.4066C7.31369 26.4411 7.64204 26.5637 8.52504 26.3738C9.59462 26.1438 11.0343 25.5311 12.7183 24.4963C14.7583 23.2426 17.0256 21.4503 19.238 19.238C21.4503 17.0256 23.2426 14.7583 24.4963 12.7183C25.5311 11.0343 26.1438 9.59463 26.3738 8.52504C26.5637 7.64204 26.4411 7.31369 26.4066 7.24189C26.345 7.21246 26.143 7.14535 25.6664 7.1918C24.9745 7.25925 23.9954 7.5498 22.7699 8.14278C20.3369 9.32007 17.3369 11.4915 14.4142 14.4142C11.4915 17.3369 9.32007 20.3369 8.14278 22.7699C7.5498 23.9954 7.25925 24.9745 7.1918 25.6664C7.14534 26.143 7.21246 26.345 7.24189 26.4066ZM29.9001 10.7285C29.4519 12.0322 28.7617 13.4172 27.9042 14.8126C26.465 17.1544 24.4686 19.6641 22.0664 22.0664C19.6641 24.4686 17.1544 26.465 14.8126 27.9042C13.4172 28.7617 12.0322 29.4519 10.7285 29.9001L21.5754 40.747C21.6001 40.7606 21.8995 40.931 22.8729 40.7217C23.9424 40.4916 25.3821 39.879 27.0661 38.8441C29.1062 37.5904 31.3734 35.7982 33.5858 33.5858C35.7982 31.3734 37.5904 29.1062 38.8441 27.0661C39.879 25.3821 40.4916 23.9425 40.7216 22.8729C40.931 21.8995 40.7606 21.6001 40.747 21.5754L29.9001 10.7285ZM29.2403 4.41187L43.5881 18.7597C44.9757 20.1473 44.9743 22.1235 44.6322 23.7139C44.2714 25.3919 43.4158 27.2666 42.252 29.1604C40.8128 31.5022 38.8165 34.012 36.4142 36.4142C34.012 38.8165 31.5022 40.8128 29.1604 42.252C27.2666 43.4158 25.3919 44.2714 23.7139 44.6322C22.1235 44.9743 20.1473 44.9757 18.7597 43.5881L4.41187 29.2403C3.29027 28.1187 3.08209 26.5973 3.21067 25.2783C3.34099 23.9415 3.8369 22.4852 4.54214 21.0277C5.96129 18.0948 8.43335 14.7382 11.5858 11.5858C14.7382 8.43335 18.0948 5.9613 21.0277 4.54214C22.4852 3.8369 23.9415 3.34099 25.2783 3.21067C26.5973 3.08209 28.1187 3.29028 29.2403 4.41187Z" fill="#13ec5b"/>
                </svg>
            </div>
            <span class="fs-4 fw-bold text-dark tracking-tight">WeSave</span>
        </a>

        <a href="{{ route('login') }}" class="btn btn-outline-light text-dark fw-bold border-1 border-secondary border-opacity-25 rounded-3 px-4 py-2" style="font-size: 0.9rem;">
            Login
        </a>
    </div>
</nav>

<div class="container-fluid min-vh-100 d-flex flex-column pt-5 mt-4">
    <div class="row flex-grow-1 align-items-stretch">

        <div class="col-lg-6 d-flex flex-column justify-content-center align-items-center bg-light py-5">
            <div class="w-100 px-4" style="max-width: 480px;">

                <div class="mb-4">
                    <h1 class="fw-bolder display-5 mb-2 text-dark">Create Account</h1>
                    <p class="text-muted fs-5">Start your financial journey today.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger rounded-3 mb-4 small">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small ms-1">Full Name</label>
                        <div class="input-group">
                            <input type="text" name="name" class="form-control py-3 px-4 rounded-3 border-secondary border-opacity-25 bg-white shadow-none"
                                   placeholder="e.g. John Doe" value="{{ old('name') }}" required
                                   style="border-radius: 12px;">
                            <span class="material-symbols-outlined position-absolute top-50 end-0 translate-middle-y me-3 text-muted pe-none">person</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small ms-1">Email Address</label>
                        <div class="input-group">
                            <input type="email" name="email" class="form-control py-3 px-4 rounded-3 border-secondary border-opacity-25 bg-white shadow-none"
                                   placeholder="name@example.com" value="{{ old('email') }}" required
                                   style="border-radius: 12px;">
                            <span class="material-symbols-outlined position-absolute top-50 end-0 translate-middle-y me-3 text-muted pe-none">mail</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-dark small ms-1">Password</label>
                        <div class="input-group position-relative">
                            <input type="password" name="password" id="password" class="form-control py-3 px-4 rounded-3 border-secondary border-opacity-25 bg-white shadow-none"
                                   placeholder="Create a password" required
                                   style="border-radius: 12px; padding-right: 50px;">
                            <button class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2 text-muted" type="button" onclick="togglePassword('password', 'icon-pass-1')" style="z-index: 5;">
                                <span class="material-symbols-outlined fs-5" id="icon-pass-1">visibility</span>
                            </button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small ms-1">Confirm Password</label>
                        <div class="input-group position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control py-3 px-4 rounded-3 border-secondary border-opacity-25 bg-white shadow-none"
                                   placeholder="Repeat password" required
                                   style="border-radius: 12px; padding-right: 50px;">
                            <button class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-2 text-muted" type="button" onclick="togglePassword('password_confirmation', 'icon-pass-2')" style="z-index: 5;">
                                <span class="material-symbols-outlined fs-5" id="icon-pass-2">visibility</span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn w-100 py-3 fw-bold text-dark rounded-3 mb-3 shadow-sm"
                            style="background-color: #13ec5b; border: none; font-size: 1rem; border-radius: 12px;">
                        Create Account
                    </button>

                    <div class="text-center mt-4">
                        <p class="small text-muted fw-bold">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-success text-decoration-none fw-bolder">Login Here</a>
                        </p>
                    </div>

                    <div class="mt-4 text-center opacity-50 d-flex justify-content-center align-items-center gap-2">
                        <span class="material-symbols-outlined fs-6">lock</span>
                        <small class="fw-bold" style="font-size: 0.75rem;">Secured by WeSave AI</small>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-6 d-none d-lg-block p-4">
            <div class="h-100 w-100 rounded-5 position-relative overflow-hidden"
                 style="background-image: url('https://images.unsplash.com/photo-1579621970795-87facc2f976d?q=80&w=2070&auto=format&fit=crop');
                        background-size: cover; background-position: center; border-radius: 32px;">

                <div class="position-absolute top-0 start-0 w-100 h-100"
                     style="background: linear-gradient(to bottom, rgba(16,34,22,0.1), rgba(16,34,22,0.9));"></div>

                <div class="position-absolute bottom-0 start-0 p-5 w-100">
                    <div class="mb-4 bg-success" style="width: 60px; height: 4px; border-radius: 10px;"></div>

                    <h2 class="text-white display-5 fw-bold mb-5" style="max-width: 450px; line-height: 1.2;">
                        Join thousands managing their wealth smarter.
                    </h2>

                    <div class="d-flex gap-3">
                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-10 rounded-3 px-3 py-2">
                            <div class="bg-warning bg-opacity-25 p-1 rounded text-warning d-flex">
                                <span class="material-symbols-outlined fs-5">bolt</span>
                            </div>
                            <span class="text-white small fw-bold">Fast Setup</span>
                        </div>

                        <div class="d-flex align-items-center gap-3 bg-white bg-opacity-10 backdrop-blur border border-white border-opacity-10 rounded-3 px-3 py-2">
                            <div class="bg-info bg-opacity-25 p-1 rounded text-info d-flex">
                                <span class="material-symbols-outlined fs-5">insights</span>
                            </div>
                            <span class="text-white small fw-bold">Real-time Analytics</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .form-control:focus {
        border-color: #13ec5b;
        box-shadow: 0 0 0 0.25rem rgba(19, 236, 91, 0.25);
    }
    body { background-color: #f8fcf9; }
</style>
@endsection
