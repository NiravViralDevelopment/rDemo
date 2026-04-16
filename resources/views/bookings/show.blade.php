@extends('layouts.admin.app')

@section('content')
    <style>
        .booking-show-page .summary-card {
            border: 1px solid #f1d2c3;
            border-radius: 14px;
            box-shadow: 0 6px 16px rgba(217, 100, 50, 0.08);
            background: #fff;
        }

        .booking-show-page .title {
            color: #0f172a;
            font-weight: 700;
        }

        .booking-show-page .meta-row {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px dashed #e2e8f0;
            padding: 8px 0;
        }

        .booking-show-page .meta-row:last-child {
            border-bottom: 0;
        }

        .booking-show-page .progress {
            height: 12px;
            border-radius: 999px;
            background: #fde3d7;
        }

        .booking-show-page .progress-bar {
            background: linear-gradient(90deg, #d96432, #f59e0b);
        }
    </style>

    <section class="section booking-show-page">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="summary-card p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="title mb-0">Booking Summary</h5>
                        <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                    </div>
                    <p class="text-muted mb-3">{{ $booking->property?->title ?? '-' }} - {{ $booking->full_name ?: $booking->customer_name }}</p>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="summary-card p-3 h-100">
                                <h6 class="title">Details</h6>
                                <div class="meta-row"><span>Phone</span><strong>{{ $booking->customer_phone ?: '-' }}</strong></div>
                                <div class="meta-row"><span>Booking Date</span><strong>{{ optional($booking->start_date)->format('Y-m-d') }}</strong></div>
                                <div class="meta-row"><span>Status</span><strong>{{ $booking->status === 'confirmed' ? 'Booked' : ucfirst($booking->status) }}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="summary-card p-3 h-100">
                                <h6 class="title">Amount Summary</h6>
                                <div class="meta-row"><span>House Price</span><strong>{{ number_format($housePrice, 2) }}</strong></div>
                                <div class="meta-row"><span>Deposit</span><strong>{{ number_format($deposit, 2) }}</strong></div>
                                <div class="meta-row"><span>Remaining</span><strong>{{ number_format($remaining, 2) }}</strong></div>
                                <div class="mt-2">
                                    <div class="d-flex justify-content-between mb-1"><span>Paid</span><strong>{{ number_format($paidPercentage, 2) }}%</strong></div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $paidPercentage }}%;" aria-valuenow="{{ $paidPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('bookings.payments.index', $booking->id) }}" class="btn btn-sm btn-warning text-white">
                            <i class="bi bi-cash-stack me-1"></i> Manage Payments
                        </a>
                        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-pencil-square me-1"></i> Edit Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
