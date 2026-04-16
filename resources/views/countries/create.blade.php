@extends('layouts.admin.app')
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
@section('content')
    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card cardPdd">
            <div class="card-body topText">
            <div class="flexBdy">
            <div class="pull-left">
                <h5 class="card-title"> Create</h5>
            </div>
            <div class="pull-right">
                <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('countries') }}"> Back</a>
           </div>
        </div>
    </div>

              <!-- Horizontal Form -->
    <div class="frmGrp antherGrp">
              <form action="{{ route('countries-store') }}" method="POST" enctype="multipart/form-data" id="item"> @csrf
                
                <div class="row mb-3">
                  <label for="code" class="col-sm-2 col-form-label strng flexCntr"> Country Code <span class="text-danger">*</span></label>
                  <div class="col-sm-5">
                    <input type="text" name="code" value="{{old('code')}}" class="form-control required" id="code">
                   </div>
                </div>

                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label strng flexCntr"> Country <span class="text-danger">*</span></label>
                  <div class="col-sm-5">
                    <input type="text" name="title" value="{{old('title')}}" class="form-control required" id="inputText">
                   </div>
                </div>


                <!-- <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">content <span class="text-danger">*</span></label>
                  <div class="col-sm-10">
                    <textarea name="content" class="form-control" >{{old('content')}}</textarea>
                    
                    @error('content')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div> -->

                <!-- <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label"> Image</label>
                  <div class="col-sm-10">
                    <input type="file" name="image" class="form-control" >
                    @error('image')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div> -->
                <div class="mt50 pddLftStLow">
                  <button type="submit" class="btn btn-sm comnBtn">Submit</button>
                  <!-- <a href="{{ route('order-item')}}" class="btn btn-secondary btn-sm">Back</a> -->
                </div>
              </form>

            </div>
          </div>

        </div>

      </div>
    </section>  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $(document).ready(function() {
            // alert("well done");
        
            $("#item").validate({
                errorClass: "error",
                rules: {
                    code: {
                        required: true,
                       
                    }
                },
            });
        
        });
        </script>
        <style>
            .error {
                color: red;
               
            }
        </style>

@endsection
