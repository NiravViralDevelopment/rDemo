@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Add Bank Payment</h5>
                        <p class="text-muted">{{ $bank->bank_name }} ({{ $bank->account_number }})</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('banks.transactions.store', $bank->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="credit" {{ old('type') === 'credit' ? 'selected' : '' }}>Credit</option>
                                    <option value="debit" {{ old('type') === 'debit' ? 'selected' : '' }}>Debit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="transaction_date" class="form-control" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">GST Number</label>
                                <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">GST %</label>
                                <input type="number" step="0.01" min="0" max="100" name="gst_percent" class="form-control" value="{{ old('gst_percent') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Save</button>
                            <a href="{{ route('banks.transactions.index', $bank->id) }}" class="btn btn-sm btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
