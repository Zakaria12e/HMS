<!DOCTYPE html>

<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>HMS Admin panel</title>

@vite(['resources/css/app.css','resources/js/app.js'])



</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper" id="app">

<nav class="main-header navbar navbar-expand navbar-white navbar-light">

<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
</li>

</ul>



<ul class="navbar-nav ml-auto">
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
               <router-link to="/admin/profile" class="dropdown-item">Profile</router-link>
               <a class="dropdown-item" href="{{ route('logout') }}">logout</a>


            </div>
        </li>
    @endguest
</ul>

</nav>


<aside class="main-sidebar sidebar-dark-primary elevation-4">

<div class="brand-link" style="text-align: center;">
<span class="sidebar-title" style="font-weight: 600;">ADMIN PANEL</span>
</div>

<div class="sidebar">

<div class="mt-3 pb-3 mb-3">
<div class="info">

</div>
</div>



<nav class="mt-2">
<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


<li class="nav-item">
<router-link to="/admin" active-class="active" class="nav-link">
<i class="nav-icon fas fa-th"></i>
<p>Dashboard</p>
</router-link>
</li>
<li class="nav-item">
    <router-link to="/admin/appointments" active-class="active" class="nav-link">
    <i class="nav-icon fas fa-calendar-alt"></i>
    <p>Appointments</p>
    </router-link>
    </li>
<li class="nav-item">
    <router-link to="/admin/patients" active-class="active" class="nav-link">
    <i class="nav-icon fas fa-users"></i>
    <p>Patients</p>
    </router-link>
    </li>
    <li class="nav-item">
        <router-link to="/admin/doctors" active-class="active" class="nav-link">
            <i class="nav-icon fas fa-user-md"></i>
            <p>Doctors</p>
        </router-link>
    </li>
    <li class="nav-item">
        <router-link to="/admin/departments" active-class="active" class="nav-link">
            <i class="nav-icon fas fa-building"></i>
            <p>Departments</p>
        </router-link>
    </li>
        <li class="nav-item">
            <router-link to="/admin/invoices" active-class="active" class="nav-link">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>Invoices</p>
            </router-link>
        </li>
</ul>
</nav>

</div>

</aside>








<div class="content-wrapper">

    <router-view></router-view>


</div>




</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
