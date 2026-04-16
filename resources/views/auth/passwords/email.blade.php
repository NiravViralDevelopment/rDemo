<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Vasu Healthcare</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('admin/img/cropped_favicon.png') }}" rel="icon" type="image/png">
  <link href="{{ asset('admin/img/cropped_favicon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('admin')}}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{asset('admin')}}/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="{{asset('admin')}}/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="{{asset('admin')}}/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="{{asset('admin')}}/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="{{asset('admin')}}/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="{{asset('admin')}}/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('admin')}}/css/style.css" rel="stylesheet">

</head>

<body>

  <main>

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4 lognPanel">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 col-sm-9 mxWD450 d-flex flex-column align-items-center justify-content-center">

             <!--  <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="{{asset('admin')}}/img/logo.png" alt="">
                  <span class="d-none d-lg-block">{{ __('Reset Password') }}</span>
                </a>
              </div> --><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">
                  <a href="{{ url('login') }}" class="logo d-flex align-items-center w-auto">
                  <img src="{{asset('admin')}}/img/logo.png" alt="">
                  <!-- <span class="d-none d-lg-block">Vasu Healthcare</span> -->
                </a>
                 <div class="TitleTop">
                    <h5 class="card-title text-center pb-0 fs-4">Reset Your Password</h5>
                  </div>
                  <!-- <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5> 
                  </div> -->
                    @if (session('status'))
                        <div class="alert alert-success verfy" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                  <form method="POST" action="{{ route('password.email') }}" class="lognFrm row g-3 needs-validation">
                    @csrf
                    <div class="col-12">
                        <label for="yourUsername" class="form-label">{{ __('Email Address') }}</label>
                        <div class="input-group has-validation inPtGp">
                            <!-- <span class="input-group-text" id="inputGroupPrepend">@</span> -->
                            <span class="icInpt"></span>
                            <input id="email" type="email" name="email"  class="form-control setPd @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            <div class="invalid-feedback">Please enter your username.</div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100 comnBtn" type="submit">{{ __('Send Password Reset Link') }}</button>
                    </div>
                </form>

                </div>
              </div>
            </div>
          </div>
        </div>

      </section>

  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{asset('admin')}}/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="{{asset('admin')}}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="{{asset('admin')}}/vendor/chart.js/chart.umd.js"></script>
  <script src="{{asset('admin')}}/vendor/echarts/echarts.min.js"></script>
  <script src="{{asset('admin')}}/vendor/quill/quill.js"></script>
  <script src="{{asset('admin')}}/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="{{asset('admin')}}/vendor/tinymce/tinymce.min.js"></script>
  <script src="{{asset('admin')}}/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="{{asset('admin')}}/js/main.js"></script>

</body>

</html>
