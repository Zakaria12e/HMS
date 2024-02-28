<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">




</head>

<body>
    <style>


section{

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






<div id="app">

        <div class="container-fluid">
            <header class="main_menu home_menu custom-bg-gray">
        <div class="row align-items-center">
        <div class="col-lg-12">
        <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="{{ route('patient.home')}}"> HMS </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse main-menu-item justify-content-center" id="navbarSupportedContent">

        <ul class="navbar-nav align-items-center">
        <li class="nav-item active">
            <li class="nav-item" :class="{ 'active': $route.name === 'home' }">
                <router-link to="/home" class="nav-link">
                    Home
                </router-link>
            </li>
        </li>
        <li class="nav-item" :class="{ 'active': $route.name === 'about' }">
            <router-link to="/about" class="nav-link">
                About
            </router-link>
        </li>
        <li class="nav-item" :class="{ 'active': $route.name === 'appointments' }">
            <router-link to="/appointments" class="nav-link">
                Appointments
            </router-link>
        </li>
        <li class="nav-item" :class="{ 'active': $route.name === 'contact' }">
            <router-link to="/contact" class="nav-link">
                Contact
            </router-link>
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



    <footer class="footer-area mt-5" style="background-color: #f8f9fa; padding-top: 30px; padding-bottom: 30px;">
        <div class="footer section_padding">
            <div class="container">
                <div class="row justify-content-between" style=" padding-top: 30px;">
                    <div class="col-md-4 col-sm-6 single-footer-widget">
                        <a href="{{route('patient.home')}}" class="footer_logo">HMS</a>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor </p>
                    </div>
                    <div class="col-md-2 col-sm-6 single-footer-widget">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="#">About us</a></li>
                            <li><a href="#">Department</a></li>
                            <li><a href="#">Online payment</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Department</a></li>
                        </ul>
                    </div>
                    <div class="col-md-2 col-sm-6 single-footer-widget">
                        <h4>Explore</h4>
                        <ul>
                            <li><a href="#">In the community</a></li>
                            <li><a href="#">IU health foundation</a></li>
                            <li><a href="#">Family support</a></li>
                            <li><a href="#">Business solution</a></li>
                            <li><a href="#">Community clinic</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright_part" style=" padding-bottom: 30px;">
            <div class="container">
                <div class="row align-items-center">
                    <p class="footer-text m-0 col-lg-8 col-md-12">
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | by ZAKARIA EL BIDALI
                    </p>
                    <div class="col-lg-4 col-md-12 text-center text-lg-right footer-social">
                        <a href="#"><i class="ti-facebook"></i></a>
                        <a href="#"> <i class="ti-twitter"></i> </a>
                        <a href="#"><i class="ti-instagram"></i></a>
                        <a href="#"><i class="ti-skype"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
