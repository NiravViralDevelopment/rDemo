<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Vasu Healthcare</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{asset('admin')}}/img/favicon.png" rel="icon">
  <link href="{{asset('admin')}}/img/apple-touch-icon.png" rel="apple-touch-icon">

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

              <!-- <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="{{asset('admin')}}/img/logo.png" alt="">
                  <span class="d-none d-lg-block">{{ __('Reset Password') }}</span>
                </a>
              </div> -->

              <div class="card mb-3">

                <div class="card-body">
                  <a href="{{ url('login') }}" class="logo d-flex align-items-center w-auto">
                  <img src="{{asset('admin')}}/img/logo.png" alt="">
                  <!-- <span class="d-none d-lg-block">Vasu Healthcare</span> -->
                </a>

                  <div class="TitleTop">
                    <h5 class="card-title text-center pb-0 fs-4">Reset Password Your Account</h5>
                  </div>

                  <form method="POST" action="{{ route('password.update') }}" class="lognFrm row g-3 needs-validation">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">{{ __('Password') }}</label>
                      <div class="input-group inPtGp has-validation">
                        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                        <!-- <span class="input-group-text" onclick="togglePassword('password')"><i class="bi bi-eye-slash" id="toggleIconPassword"></i></span> -->
                        <i class="toggle-password bi bi-eye-slash position-absolute end-0 me-3 meZro top-50 translate-middle-y" style="cursor: pointer;"></i>
                      </div>
                      <div class="invalid-feedback">Please enter your password!</div>
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>

                    <div class="col-12">
                      <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                      <div class="input-group inPtGp">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <!-- <span class="input-group-text" onclick="togglePassword('password-confirm')"><i class="bi bi-eye-slash" id="toggleIconConfirmPassword"></i></span> -->
                        <i class="toggle-password bi bi-eye-slash position-absolute end-0 me-3 meZro top-50 translate-middle-y" style="cursor: pointer;"></i>
                      </div>
                      <div class="invalid-feedback">Please enter your password!</div>
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="col-12">
                      <button class="btn comnBtn w-100" type="submit">{{ __('Reset Password') }}</button>
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

  <!-- Password Toggle Script -->
  <script>
    function togglePassword(id) {
        const passwordField = document.getElementById(id);
        const toggleIcon = document.getElementById('toggleIcon' + (id === 'password' ? 'Password' : 'ConfirmPassword'));
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        toggleIcon.classList.toggle('bi-eye');
        toggleIcon.classList.toggle('bi-eye-slash');
    }
  </script>

  <script>
        document.querySelectorAll('.toggle-password').forEach(item => {
            item.addEventListener('click', function () {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('bi-eye');
                this.classList.toggle('bi-eye-slash');
            });
        });
    </script>

</body>

</html>
