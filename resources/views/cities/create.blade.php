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
                <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('cities') }}"> Back</a>
           </div>
        </div>
    </div>

              <!-- Horizontal Form -->
        <div class="frmGrp antherGrp">
              <form action="{{ route('cities-store') }}" method="POST" enctype="multipart/form-data" id="item"> @csrf
                
              
                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-1 col-form-label strng flexCntr"> Country <span class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <select name="country_id" id="country_id" class="form-control required">
                            <option value="">Select Country</option>
                            @foreach ($data as $row)
                                <option value="{{ $row->id }}">{{ $row->country_name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="text-danger">{{ $country_id }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-1 col-form-label strng flexCntr"> State <span class="text-danger">*</span></label>
                  <div class="col-sm-5">
                    <input type="text" name="title" value="{{old('title')}}" class="form-control required" id="inputText">
                   </div>
                </div>
                <div class="mt50 pddLftStLow">
                  <button type="submit" class="btn btn-sm comnBtn">Submit</button>
                  
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
                errorClass: "error"
            });
        
        });
        </script>
        <style>
            .error {
                color: red;
               
            }
        </style>

@endsection
