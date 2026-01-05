<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WeSave - @yield('title', 'Dashboard')</title>

    <link href="{{ asset('bootstrap5.2/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>
        body { font-family: 'Manrope', sans-serif; background-color: #f6f8f6; }

        .sidebar {
            min-height: 100vh;
            background: #ffffff;
            border-right: 1px solid #e9ecef;
        }
        .nav-link {
            color: #64748b;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #eefdf3;
            color: #13ec5b;
        }
        .nav-link .material-symbols-outlined { font-size: 22px; }

        .top-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e9ecef;
        }

        .card-custom {
            border: 1px solid #f1f5f9;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border-radius: 16px;
            background: white;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <aside class="sidebar d-none d-lg-block p-4" style="width: 280px; position: fixed;">
        <a href="{{ route('dashboard') }}" class="text-decoration-none">
            <div class="d-flex align-items-center gap-3 mb-5 px-2">
                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success">
                    <span class="material-symbols-outlined fs-2">savings</span>
                </div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark">WeSave</h4>
                    <small class="text-muted">Financial AI</small>
                </div>
            </div>
        </a>

        <nav class="nav flex-column">
            <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="material-symbols-outlined">home</span> {{ __('messages.dashboard') }}
            </a>
            <a class="nav-link {{ Request::routeIs('wallets.index') ? 'active' : '' }}" href="{{ route('wallets.index') }}">
                <span class="material-symbols-outlined">account_balance_wallet</span> {{ __('messages.wallets') }}
            </a>
            <a class="nav-link {{ Request::routeIs('transactions.index') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                <span class="material-symbols-outlined">receipt_long</span> {{ __('messages.transactions') }}
            </a>
            <a class="nav-link {{ Request::routeIs('goals.index') ? 'active' : '' }}" href="{{ route('goals.index') }}">
                <span class="material-symbols-outlined">track_changes</span> {{ __('messages.goals') }}
            </a>
            <a class="nav-link {{ Request::routeIs('reports.index') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <span class="material-symbols-outlined">insights</span> {{ __('messages.reports') }}
            </a>
        </nav>

        <div class="mt-auto pt-5 border-top">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-danger w-100 bg-transparent border-0">
                    <span class="material-symbols-outlined">logout</span> {{ __('messages.logout') }}
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-grow-1" style="margin-left: 280px; min-height: 100vh;">

        <nav class="navbar top-navbar sticky-top px-4 py-3">
            <div class="d-flex justify-content-between w-100 align-items-center">

                <div class="d-none d-md-block w-50">
                    <form action="{{ route('transactions.index') }}" method="GET">
                        <div class="input-group">
                            <button type="submit" class="input-group-text bg-white border-end-0 rounded-start-4 ps-3" style="cursor: pointer;">
                                <span class="material-symbols-outlined text-muted fs-5">search</span>
                            </button>
                            <input type="text" name="search" class="form-control border-start-0 rounded-end-4 shadow-none py-2"
                                   placeholder="{{ __('messages.search_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                            <span class="material-symbols-outlined text-muted">language</span>
                            <span class="text-uppercase small fw-bold">{{ LaravelLocalization::getCurrentLocale() }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-4 mt-2">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                <li>
                                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="dropdown-item small">
                                        {{ $properties['native'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle p-2 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="material-symbols-outlined text-muted">notifications</span>

                            @if(Auth::user()->unreadNotifications->count() > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                                {{ Auth::user()->unreadNotifications->count() }}
                                <span class="visually-hidden">unread messages</span>
                            </span>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end p-0 border-0 shadow-lg rounded-4" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <li class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top-4">
                                <h6 class="fw-bold m-0 text-dark">Notifikasi</h6>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.readAll') }}" class="small text-decoration-none fw-bold text-success">Tandai Sudah Baca</a>
                                @endif
                            </li>

                            @forelse(Auth::user()->notifications as $notification)
                            <li>
                                <a class="dropdown-item p-3 border-bottom d-flex gap-3 align-items-start {{ $notification->read_at ? '' : 'bg-success bg-opacity-10' }}" href="#">
                                    <div class="rounded-circle p-2 d-flex align-items-center justify-content-center bg-white border" style="width: 35px; height: 35px;">
                                        <span class="material-symbols-outlined fs-6 {{ $notification->data['color'] ?? 'text-dark' }}">
                                            {{ $notification->data['icon'] ?? 'notifications' }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="mb-0 small fw-bold text-dark text-wrap" style="line-height: 1.4;">
                                            {{ $notification->data['message'] }}
                                        </p>
                                        <small class="text-muted" style="font-size: 10px;">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </a>
                            </li>
                            @empty
                            <li class="p-4 text-center text-muted">
                                <span class="material-symbols-outlined fs-1 opacity-25">notifications_off</span>
                                <p class="small mb-0 mt-2">Belum ada notifikasi.</p>
                            </li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="text-end d-none d-sm-block">
                                <p class="mb-0 fw-bold small text-dark">{{ Auth::user()->name ?? 'Guest' }}</p>
                                <small class="text-muted" style="font-size: 11px;">User</small>
                            </div>
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=13ec5b&color=fff' }}"
                                class="rounded-circle border border-2 border-white shadow-sm object-fit-cover"
                                width="40" height="40" alt="Avatar">
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2 p-2" style="width: 220px;">
                            <li>
                                <h6 class="dropdown-header text-muted small fw-bold text-uppercase my-1">Manage Account</h6>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 rounded-3 py-2 my-1" href="{{ route('profile.edit') }}">
                                    <span class="material-symbols-outlined fs-5 text-muted">settings</span>
                                    <span class="fw-bold small">Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    </div>
            </div>
        </nav>

        <div class="p-4 p-md-5">
            @yield('content')
        </div>
    </main>
</div>

<script src="{{ asset('bootstrap5.2/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
