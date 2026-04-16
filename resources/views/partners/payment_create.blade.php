@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Partner Payment</h5>

                        <div class="alert alert-info">
                            <div><strong>Partner:</strong> {{ $partner->name }}</div>
                            <div><strong>Total Balance:</strong> {{ number_format((float) $partner->total_balance, 2) }}</div>
                            <div><strong>Total Paid:</strong> {{ number_format((float) $paid, 2) }}</div>
                            <div><strong>Pending Amount:</strong> {{ number_format((float) $pendingAmount, 2) }}</div>
                        </div>

                        <form method="POST" action="{{ route('partners.payments.store', $partner->id) }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Payment Amount</label>
                                <input type="number" step="0.01" min="0.01" name="amount" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Date</label>
                                <input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <textarea name="note" class="form-control" rows="3"></textarea>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Save Payment</button>
                            <a href="{{ route('partners.index') }}" class="btn btn-sm btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

