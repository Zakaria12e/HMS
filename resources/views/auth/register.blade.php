<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registration Page</title>

@vite(['resources/css/app.css','resources/css/font-awesome.min.css','resources/css/signup.css'])

</head>

<body>
    <section class="form-08">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <div class="_form-08-main">
                <div class="_form-08-head">
                  <h2>REGISTER</h2><br>

                </div>

                @if(session('error'))
                <div class="alert alert-danger text-light" role="alert">
                  {{ session('error') }}
                </div>
                @endif


                <form action="{{ route('register.store') }}" method="POST" role="form text-left">
                @csrf

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Full name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" type="text" placeholder="Enter Email" required="" aria-required="true">
                  </div>

                  <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" type="text" placeholder="Enter Password" required="" aria-required="true">
                  </div>


                  <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" placeholder="212xxxxxxxx" pattern="212[0-9]{9}" title="Phone number must start with 212 and have 9 additional digits" required>
                </div>




                  <div class="form-group">

                      <button class="_btn_04" type="submit">Sign Up</button>
                  </div>
            </form>

            <a href="{{ route('login') }}" class="text-center">I already have a membership</a>



                <div class="sub-01">
                  <img src="storage/img/shap-02.png">
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>


</body>
</html>
