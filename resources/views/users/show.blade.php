@extends('layouts.admin.app')


@section('content')
<div class="row">
<div class="col-lg-12">
<div class="card cardPdd">
    <div class="card-body topText">
        <div class="col-lg-12 margin-tb">
            <div class="flexBdy">
            <div class="pull-left">
                <h5 class="card-title"> Show User</h5>
            </div>
            <div class="pull-right">
                <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('users.index') }}"> Back</a>
            </div>
            </div>
        </div>
    </div>


<div class="row frmGrp">
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="form-group flexClm">
            <strong class="strng dullColor">Name:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="form-group flexClm">
            <strong class="strng dullColor">Email:</strong>
            {{ $user->email }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-4">
        <div class="form-group flexClm">
            <strong class="strng dullColor">Roles:</strong>
            @if(!empty($user->getRoleNames()))
                @foreach($user->getRoleNames() as $v)
                    <label class="badge bg-success mxwdthMax">{{ $v }}</label>
                @endforeach
            @endif
        </div>
    </div>
</div>

</div>
</div>
</div>
@endsection
