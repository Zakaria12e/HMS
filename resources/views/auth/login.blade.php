<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HMS</title>

    @vite(['resources/css/app.css','resources/css/font-awesome.min.css','resources/css/style.css'])
</head>

<body>
    <section class="form-08">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="_form-08-main">
                <div class="_form-08-head">
                  <h2>Welcome Back</h2>
                </div>
                @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger text-light" role="alert">
                  {{ session('error') }}
                </div>
            @endif


            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Enter Your Email</label>
                    <input type="email" name="email" class="form-control" type="text" placeholder="Enter Email" required="" aria-required="true">
                  </div>

                  <div class="form-group">
                    <label>Enter Password</label>
                    <input type="password" name="password" class="form-control" type="text" placeholder="Enter Password" required="" aria-required="true">
                  </div>



                  <div class="form-group">

                      <button class="_btn_04" type="submit">Log In</button>
                  </div>
            </form>

            <a href="{{ route('register') }}" class="text-center">Register a new membership</a>



                <div class="sub-01">
                  <img src="img/shap-02.png">
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>


</body>

</html>
