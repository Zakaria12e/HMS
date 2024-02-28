<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registration Page</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class="hold-transition register-page">
<div class="register-box">
<div class="register-logo">

</div>
<div class="card">
    <div class="card-header text-center">
        <a href="#" class="h1"><b>REGISTER</b></a>
    </div>
<div class="card-body register-card-body">
<p class="login-box-msg">Register a new membership</p>

@if(session('error'))
<div class="alert alert-danger text-light" role="alert">
  {{ session('error') }}
</div>
@endif

<form action="{{ route('register.store') }}" method="POST" role="form text-left">
    @csrf
<div class="input-group mb-3">
<input type="text" name="name" class="form-control" placeholder="Full name" required>
<div class="input-group-append">
<div class="input-group-text">
<span class="fas fa-user"></span>
</div>
</div>
</div>
<div class="input-group mb-3">
<input type="email" name="email" class="form-control" placeholder="Email" required>
<div class="input-group-append">
<div class="input-group-text">
<span class="fas fa-envelope"></span>
</div>
</div>
</div>
<div class="input-group mb-3">
    <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required>
    <div class="input-group-append">
    <div class="input-group-text">
        <span class="fas fa-phone"></span>
    </div>
    </div>
    </div>
<div class="input-group mb-3">
<input type="password" name="password" class="form-control" placeholder="Password" required>
<div class="input-group-append">
<div class="input-group-text">
<span class="fas fa-lock"></span>
</div>
</div>
</div>
<div class="row">
<div class="col-8">
<div class="icheck-primary">
<input type="checkbox" id="agreeTerms" name="terms" value="agree">
<label for="agreeTerms">
I agree to the <a href="#">terms</a>
</label>
</div>
</div>

<div class="col-4">
<button type="submit" class="btn btn-primary btn-block">Register</button>
</div>

</div>
</form>

<a href="{{ route('login') }}" class="text-center">I already have a membership</a>
</div>

</div>
</div>

</body>
</html>
