@extends('layouts.admin.app')

@section('content')
    <!-- <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}">Home</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div> -->

    <section class="section profile">
      <div class="row">
        <div class="col-xl-12">
          <div class="card cardPdd">
            <div class="card-body topText profile-card d-flex flex-column">
                <div class="col-lg-12 margin-tb">
        <div class="flexBdy">
        <div class="pull-left">
            <h2><i class="bi bi-person"></i> {{ $user->name }}</h2>
        </div>
        <div class="pull-right">
            <h3 class="mb-0"><i class="bi bi-envelope"></i> {{ $user->email }}</h3> 
        </div>
        </div>
    </div>    
    </div>     

        <div class="col-xl-12">
                <div class="frmGrp">
                    <ul class="nav nav-tabs navTbs">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                        </li>
                    </ul>
                    <div class="tab-content pt-2 tbCnt">
                        <div class="tab-pane fade show active" id="profile-change-password">                           
                            <form action="{{ route('profile.change-password') }}" method="POST">
                                @csrf
                                <div class="row mb-3">
                                    <label for="currentPassword" class="col-md-4 col-lg-5 col-form-label">Current Password<span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-lg-7 position-relative">
                                        <input name="password" type="password" class="form-control" id="currentPassword" required>
                                        <i class="toggle-password bi bi-eye-slash position-absolute end-0 me-3 top-50 translate-middle-y" style="cursor: pointer;"></i>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="new_password" class="col-md-4 col-lg-5 col-form-label">New Password<span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-lg-7 position-relative">
                                        <input name="new_password" type="password" class="form-control" id="new_password" required>
                                        <i class="toggle-password bi bi-eye-slash position-absolute end-0 me-3 top-50 translate-middle-y" style="cursor: pointer;"></i>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="confirm_password" class="col-md-4 col-lg-5 col-form-label">Confirm New Password<span class="text-danger">*</span></label>
                                    <div class="col-md-8 col-lg-7 position-relative">
                                        <input name="confirm_password" type="password" class="form-control" id="confirm_password" required>
                                        <i class="toggle-password bi bi-eye-slash position-absolute end-0 me-3 top-50 translate-middle-y" style="cursor: pointer;"></i>
                                    </div>
                                </div>

                                <div class="mt50 txtLft pddLftStHigh">
                                    <button type="submit" class="btn comnBtn">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>  
        </div>
        </div>
      </div>
    </section>

    <style>
        .profile-card {
            text-align: center;
            background: #f8f9fa;
            padding: 20px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.05);
            border-radius: 10px;
        }

        /* .nav-tabs-bordered .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .nav-tabs .nav-link {
            color: #007bff;
        } */

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
            padding-right: 2.5rem;
        }

        .toggle-password {
            font-size: 1.2rem;
        }

        .toggle-password:hover {
            color: #007bff;
        }

        .breadcrumb-item.active {
            font-weight: bold;
        }
    </style>

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
@endsection
