@extends('layouts.admin.app')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card cardPdd">
            <div class="card-body topText">
                <div class="col-lg-12 margin-tb">
                    <div class="flexBdy">
                        <div class="pull-left">
                            <h5 class="card-title">Create Permission</h5>
                        </div>
                        <div class="pull-right">
                            <a class="btn btn-sm mb-2 comnBtn whtTxt borderBtn" href="{{ route('permissions.index') }}">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mx-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="frmGrp">
                <form method="POST" action="{{ route('permissions.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <strong class="strng">Module Name:</strong>
                                <input type="text" name="module_name" class="form-control" value="{{ old('module_name') }}" placeholder="Example: customer">
                                <small class="text-muted">This will create `list`, `create`, `edit`, `delete` permissions automatically.</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <strong class="strng">Single Permission Name:</strong>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Example: customer-export">
                                <small class="text-muted">Optional. Use this for a custom permission outside the standard 4 actions.</small>
                            </div>
                        </div>

                        <div class="col-md-12 mt50">
                            <button type="submit" class="btn btn-sm comnBtn">
                                <i class="fa-solid fa-floppy-disk"></i> Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
