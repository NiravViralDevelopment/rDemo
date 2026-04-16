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
                <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('cities') }}"> Back</a>
            </div>
            </div>
        </div>
    </div>
    <section class="section frmGrp">
      <div class="row">
        <div class="col-lg-12">


              <!-- Horizontal Form -->
              <form action="{{ route('cities-update',$data->id) }}" method="POST" enctype="multipart/form-data"> @csrf
                
                <div class="row mb-3">
                    <label for="inputEmail3" class="col-sm-1 col-form-label strng flexCntr"> Country <span class="text-danger">*</span></label>
                    <div class="col-sm-5">
                        <select name="country_id" id="country_id" class="form-control required">
                            <option value="">Select Country</option>
                            @foreach ($countries as $row)
                                <option value="{{ $row->id }}" @if($data->countries_id == $row->id) selected  @endif >{{ $row->country_name }}</option>
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
                    <input type="text" name="title" value="{{ $data->city_name }}" class="form-control" id="inputText">
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

@endsection
