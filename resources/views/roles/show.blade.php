@extends('layouts.admin.app')

@section('content')
<div class="row">
<div class="col-lg-12">
<div class="card cardPdd">
    <div class="card-body topText">
    <div class="col-lg-12 margin-tb">
        <div class="flexBdy">
        <div class="pull-left">
            <h5 class="card-title"> Role Detail</h5>
        </div>
        <div class="pull-right">
            <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('roles.index') }}"> Back</a>
        </div>
        </div>
    </div>
    </div>

<div class="row frmGrp">
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="form-group flexClm">
            <strong class="strng dullColor">Name:</strong>
            {{ $role->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="form-group flexClm">
            <strong class="strng dullColor">Permissions:</strong>
            @if(!empty($rolePermissions))
                @foreach($rolePermissions as $key => $v)
                    <label class="label label-success">{{ $v->name }}{{ $key != count($rolePermissions) - 1 ? ',' : '.' }}</label>
            @endforeach
            @endif
        </div><br>
    </div>
</div>

</div>
</div>
</div>
@endsection