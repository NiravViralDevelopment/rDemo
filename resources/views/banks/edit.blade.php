@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Bank</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('banks.update', $bank->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bank Name</label>
                                    <input type="text" name="bank_name" class="form-control" value="{{ old('bank_name', $bank->bank_name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">IFSC Code</label>
                                    <input type="text" name="ifsc_code" class="form-control" value="{{ old('ifsc_code', $bank->ifsc_code) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Branch Name</label>
                                    <input type="text" name="branch_name" class="form-control" value="{{ old('branch_name', $bank->branch_name) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Number</label>
                                    <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $bank->account_number) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Account Holder Name</label>
                                    <input type="text" name="account_holder_name" class="form-control" value="{{ old('account_holder_name', $bank->account_holder_name) }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Opening Balance</label>
                                    <input type="number" step="0.01" min="0" name="opening_balance" class="form-control" value="{{ old('opening_balance', $bank->opening_balance ?? 0) }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description', $bank->description) }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Update</button>
                            <a href="{{ route('banks.index') }}" class="btn btn-sm btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
