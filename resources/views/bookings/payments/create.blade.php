@extends('layouts.admin.app')

@section('content')
    <style>
        .booking-payment-form .card {
            border: 1px solid #f1d2c3;
            border-radius: 14px;
            box-shadow: 0 6px 16px rgba(217, 100, 50, 0.08);
        }

        .booking-payment-form .btn-theme {
            background: #d96432;
            border: 1px solid #d96432;
            color: #fff;
        }

        .booking-payment-form .btn-theme:hover {
            background: #bf5225;
            border-color: #bf5225;
            color: #fff;
        }
    </style>

    <section class="section booking-payment-form">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-1">Add Customer Paid Amount</h5>
                        <p class="text-muted">{{ $booking->property?->title ?? '-' }} - {{ $booking->full_name ?: $booking->customer_name }}</p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('bookings.payments.store', $booking->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Deposit Amount</label>
                                <input type="text" name="amount" class="form-control amount-only" inputmode="decimal" autocomplete="off" value="{{ old('amount') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deposit Date</label>
                                <input type="date" name="paid_on" class="form-control" value="{{ old('paid_on', now()->format('Y-m-d')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-theme">Save Payment</button>
                            <a href="{{ route('bookings.payments.index', $booking->id) }}" class="btn btn-outline-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.querySelectorAll('.amount-only').forEach(function(input) {
                input.addEventListener('input', function() {
                    let value = this.value.replace(/[^0-9.]/g, '');
                    const parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts.shift() + '.' + parts.join('');
                    }
                    this.value = value;
                });
            });
        </script>
    @endpush
@endsection
