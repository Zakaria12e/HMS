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

</nav>


<aside class="main-sidebar sidebar-dark-primary elevation-4">

<a href="index3.html" class="brand-link">
<span class="sidebar-title">ADMIN PANEL</span>
</a>

<div class="sidebar">

<div class="user-panel mt-3 pb-3 mb-3 d-flex">
<div class="info">
<a href="/admin/profile" class="d-block">WELCOM  {{ Auth::user()->name }}</a>
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
            <router-link to="/admin/invoices" active-class="active" class="nav-link">
                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                <p>Invoices</p>
            </router-link>
        </li>

        <li class="nav-item">
            <router-link to="/admin/profile" active-class="active" class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>Profile</p>
            </router-link>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout')}}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
                </a>
                </li>
</ul>
</nav>

</div>

</aside>








<div class="content-wrapper">

    <router-view></router-view>


</div>


<aside class="control-sidebar control-sidebar-dark">

<div class="p-3">
<h5>Title</h5>
<p>Sidebar content</p>
</div>
</aside>

</div>

</body>
</html>
