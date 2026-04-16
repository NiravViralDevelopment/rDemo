@extends('layouts.admin.app')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="card cardPdd">
    <div class="card-body topText">
    <div class="col-lg-12 margin-tb">
        <div class="flexBdy">
                <div class="pull-left">
                   <h5 class="card-title">Create New Role</h5>
                </div>
                <div class="pull-right">
                   <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('roles.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
    </div>
    </div>


@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
        </ul>
    </div>
@endif

<div class="frmGrp">
<form method="POST" action="{{ route('roles.store') }}" id="role">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong class="strng">Name:</strong>
                <input type="text" name="name" placeholder="Name" class="form-control required">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group flxfrmGp">
                <strong class="strng">Permission:</strong>
                <br/>
                <div class="flxLbl" style="max-height: 320px; overflow-y: auto;">
                @foreach($permission as $value)
                    <label><input type="checkbox" name="permission[{{$value->id}}]" value="{{$value->id}}" class="name">
                    {{ $value->name }}</label>
                <br/>
                @endforeach
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 mt50">
            <button type="submit" class="btn btn-sm comnBtn"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>
</div>

</div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        // alert("well done");
    
        $("#role").validate({
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