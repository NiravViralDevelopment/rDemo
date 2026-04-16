@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card cardPdd">
                    <div class="card-body topText">
                        <div class="flexBdy">
                            <div class="pull-left">
                                <h5 class="card-title">Dummy Data CSV Import</h5>
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
                            <form action="{{ route('dummy-data.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                <div class="row mb-3">
                                    <label for="file" class="col-sm-2 col-form-label strng flexCntr">
                                        Upload CSV <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-sm-6">
                                        <input type="file" name="file" id="file" class="form-control" accept=".csv,text/csv" required>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        <small>
                                            CSV header/order must match:
                                            <code>id,name,mobile,email,city,state,age,salary,gender,status</code>
                                        </small>
                                    </div>
                                </div>

                                <div class="mt50 pddLftStLow">
                                    <button type="submit" class="btn btn-sm comnBtn">Import</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

