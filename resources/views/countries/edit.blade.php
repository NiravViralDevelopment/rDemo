@extends('layouts.admin.app')
<script src="https://code.jquery.com/jquery-1.11.3.js"></script>
@section('content')

<div class="row">
<div class="col-lg-12">
<div class="card cardPdd">
    <div class="card-body topText">
        <div class="col-lg-12 margin-tb">
            <div class="flexBdy">
            <div class="pull-left">
                <h5 class="card-title"> Edit</h5>
            </div>

            <div class="pull-right">
                <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('countries') }}"> Back</a>
            </div>
            </div>
        </div>
    </div>
    <section class="section frmGrp">
      <div class="row">
        <div class="col-lg-12">


              <!-- Horizontal Form -->
              <form action="{{ route('countries-update',$data->id) }}" method="POST" enctype="multipart/form-data" id="item"> @csrf
                

              <div class="row mb-3">
                  <label for="code" class="col-sm-2 col-form-label strng flexCntr"> Country Code <span class="text-danger">*</span></label>
                  <div class="col-sm-5">
                    <input type="text" name="code" value="{{ $data->code }}" class="form-control required" id="code">
                   </div>
                </div>


                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label strng flexCntr"> Country <span class="text-danger">*</span></label>
                  <div class="col-sm-5">
                    <input type="text" name="title" value="{{ $data->country_name }}" class="form-control" id="inputText">
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                  </div>
                </div>

                
                <div class="text-left mt50 pddLftStLow">
                  <button type="submit" class="btn btn-sm comnBtn whtTxt">Update</button>
                  
                </div>
              </form>

          </div>
        </div>

    </section> 
    </div> 
  </div>
</div>


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