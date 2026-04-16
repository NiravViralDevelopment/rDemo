<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>HARIHAR ERP</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('admin/img/cropped_favicon.png') }}" rel="icon" type="image/png">
  <link href="{{ asset('admin/img/cropped_favicon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('admin/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('admin/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('admin/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{asset('admin/vendor/quill/quill.snow.css')}}" rel="stylesheet">
  <link href="{{asset('admin/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
  <link href="{{asset('admin/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
  <link href="{{asset('admin/vendor/simple-datatables/style.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{asset('admin/css/style.css')}}" rel="stylesheet">

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
                  <span class="d-none d-lg-block">Vasu Healthcare</span>
                </a>
              </div> --><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">
                <a href="{{ url('login') }}" class="logo d-flex align-items-center justify-content-center w-100 healthcare-logo">
                  <span>HARIHAR ERP</span>
                </a>
                  <div class="TitleTop">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                  </div>

                  <form method="POST" action="{{ route('login') }}" id="login" class="lognFrm row g-3 needs-validation">
                    @csrf
                    <div class="col-12">
                        <label for="yourUsername" class="form-label">Email</label>
                        <div class="input-group has-validation inPtGp">
                            <!-- <span class="input-group-text" id="inputGroupPrepend"><i class="bi bi-person"></i></span> -->
                            <span class="icInpt"></span>
                            <input id="email" type="email" name="email" class="form-control setPd" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <div class="input-group has-validation">
                        <input type="password" id="password" class="form-control setPd" name="password" required>
                        <span class="icInpt icInptPass"></span>
                        <!-- <span class="input-group-text" onclick="togglePassword()"><i class="bi bi-eye-slash" id="toggleIcon"></i></span> -->
                      </div>
                      <div class="invalid-feedback">Please enter your password!</div>
                      @error('password')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label rmbr" for="rememberMe">Remember me</label>
                      </div>
                    </div>
                    
                    <div class="col-12">
                      <button class="btn btn-primary w-100 comnBtn" type="submit">Login</button>
                    </div>

                    <!-- <div class="col-12 text-center">
                      <a href="{{ route('password.request') }}" class="small txtBtn">Forgot your password?</a>
                    </div> -->

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
    function togglePassword() {
      const passwordField = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      toggleIcon.classList.toggle('bi-eye');
      toggleIcon.classList.toggle('bi-eye-slash');
    }
  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
  <script>
      $(document).ready(function() {
          // alert("well done");
      
          $("#login").validate({
              errorClass: "error"
          });
      
      });
      </script>
      <style>
          .lognPanel {
              background-image: linear-gradient(135deg, #fff8ef 0%, #f8e2bd 100%) !important;
              background-color: #fff8ef;
          }

          .lognPanel .card {
              border: 1px solid #e8b679;
              box-shadow: 0 16px 35px rgba(217, 100, 50, 0.18);
          }

          .healthcare-logo {
              color: #d96432;
              font-size: 1.35rem;
              font-weight: 800;
              margin: 10px 0 16px;
              text-decoration: none;
              letter-spacing: 0.4px;
          }

          .lognFrm .comnBtn {
              background: #d96432 !important;
              border-color: #d96432 !important;
              color: #fff !important;
              font-weight: 700;
              box-shadow: 0 8px 18px rgba(217, 100, 50, 0.3);
          }

          .lognFrm .comnBtn:hover {
              background: #bf4f22 !important;
              border-color: #bf4f22 !important;
          }

          .error {
              color: #c0392b;
          }
      </style>

</body>

</html>
