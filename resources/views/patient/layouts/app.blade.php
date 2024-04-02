<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
</head>

<body>
    <style>

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}

.badge-purple {
    background-color: #6f42c1;
    color: #fff;
}

.badge-secondary {
    background-color: #6c757d;
    color: #fff;
}

        section {
            padding-top: 70px;
        }

        .nav-link {
            font-weight: 600;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #007bff;
        }

</style>

    <div id="app" data-user-id="{{ Auth::id() }}">
        <div class="container-fluid">
            <header class="main_menu home_menu custom-bg-gray">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <a class="navbar-brand" href="{{ route('patient.home') }}">HMS</a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse main-menu-item justify-content-center" id="navbarSupportedContent">
                                <ul class="navbar-nav align-items-center">
                                    <li class="nav-item" :class="{ 'active': $route.name === 'home' }">
                                        <router-link to="/home" class="nav-link">Home</router-link>
                                    </li>
                                    <li class="nav-item" :class="{ 'active': $route.name === 'about' }">
                                        <router-link to="/about" class="nav-link">About</router-link>
                                    </li>
                                    <li class="nav-item" :class="{ 'active': $route.name === 'appointments' }">
                                        <router-link to="/appointments" class="nav-link">Appointments</router-link>
                                    </li>
                                    <li class="nav-item" :class="{ 'active': $route.name === 'MedicalReports' }">
                                        <router-link to="/Medical-Reports" class="nav-link">Medical Reports</router-link>
                                    </li>
                                </ul>
                            </div>
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
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }}
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                                            <a class="dropdown-item" href="{{ route('logout') }}">logout</a>

                                        </div>
                                    </li>
                                @endguest
                            </ul>
                        </nav>
                    </div>
                </div>
            </header>
        </div>

        <router-view></router-view>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
