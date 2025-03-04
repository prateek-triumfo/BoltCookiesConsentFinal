<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom styling for the user name button */
        .user-dropdown-btn {
            font-size: 1.1rem;
            font-weight: 600;
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
        }

        .user-dropdown-btn:hover {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        /* Tile layout for links */
        .tile-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .tile {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            transition: transform 0.3s ease-in-out;
        }

        .tile:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .tile .tile-link {
            display: block;
            font-size: 1.2rem;
            color: #007bff;
            text-decoration: none;
        }

        .tile .tile-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @if(!Request::is('admin/*') && !Request::is('dashboard'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('consent.manage') }}">Manage Cookies</a>
                        </li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                        <li class="nav-item dropdown">
    <!-- User Name Button -->
    <button class="user-dropdown-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        {{ Auth::user()->name }}
    </button>

    <div class="dropdown-menu dropdown-menu-end">
        <!-- Logout Button -->
        <a href="{{ route('logout') }}" class="dropdown-item" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Logout
        </a>
        
        <!-- Logout form (hidden) to trigger the logout route) -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</li>

                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Tiles Layout for Links -->
        @if(Auth::check())
            <div class="container tile-container">
                @if (Auth::user())
                    <div class="tile">
                        <a href="{{ route('admin.banner.edit') }}" class="tile-link">
                            <strong>Banner Settings</strong>
                        </a>
                    </div>
                    <div class="tile">
                        <a href="{{ route('admin.categories.index') }}" class="tile-link">
                            <strong>Manage Consent Categories</strong>
                        </a>
                    </div>
                    <div class="tile">
                        <a href="{{ route('admin.domains.index') }}" class="tile-link">
                            <strong>Manage Domains</strong>
                        </a>
                    </div>
                    <div class="tile">
                        <a href="{{ route('admin.consent.logs.index') }}" class="tile-link">
                            <strong>View Consent Logs</strong>
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <main class="py-4">
            @yield('content')
        </main>
        @php
            $cookieConsent = request()->cookie('consent_preferences');
            $hasConsent = !empty($cookieConsent);
        @endphp
        @if(!$hasConsent)
            @include('consent.banner')
        @endif
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
