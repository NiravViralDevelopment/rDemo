@extends('layouts.admin.app')

@section('content')
    <style>
        .bookings-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .bookings-page .card-title {
            color: #0f172a;
            font-weight: 700;
        }

        .bookings-page .btn-theme {
            background: #d96432;
            border: 1px solid #d96432;
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
        }

        .bookings-page .btn-theme:hover {
            background: #bf5225;
            border-color: #bf5225;
            color: #fff;
        }
        
        .bookings-page .booking-grid-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.05);
            height: 100%;
            overflow: hidden;
        }

        .bookings-page .booking-grid-card .card-body {
            padding: 14px;
        }

        .bookings-page .booking-grid-card .card-top-band {
            height: 6px;
            background: linear-gradient(90deg, #d96432, #f59e0b, #16a34a);
        }

        .bookings-page .booking-title {
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .bookings-page .booking-sub {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .bookings-page .meta-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 1px dashed #e2e8f0;
            padding: 6px 0;
            font-size: 0.9rem;
        }

        .bookings-page .meta-row:last-child {
            border-bottom: 0;
        }

        .bookings-page .meta-label {
            color: #64748b;
            font-weight: 600;
        }

        .bookings-page .meta-value {
            color: #0f172a;
            font-weight: 600;
            text-align: right;
        }

        .bookings-page .pay-progress-wrap {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #fde3d7;
            border-radius: 10px;
            background: #fff9f5;
        }

        .bookings-page .pay-progress-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
            font-size: 0.82rem;
            font-weight: 700;
            color: #7c2d12;
        }

        .bookings-page .pay-progress {
            height: 10px;
            border-radius: 999px;
            background: #fde3d7;
            overflow: hidden;
        }

        .bookings-page .pay-progress .bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #d96432, #f59e0b);
        }

        .bookings-page .summary-card {
            border: 1px solid #f1d2c3;
            border-radius: 12px;
            background: #fff;
            padding: 14px 16px;
            box-shadow: 0 6px 16px rgba(217, 100, 50, 0.08);
        }

        .bookings-page .summary-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: #64748b;
            margin: 0;
            font-weight: 700;
        }

        .bookings-page .summary-value {
            margin: 4px 0 0;
            color: #0f172a;
            font-size: 1.35rem;
            font-weight: 700;
        }


        .booking-pill {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            border: 1px solid transparent;
            letter-spacing: 0.2px;
        }

        .status-confirmed {
            color: #065f46;
            background: #d1fae5;
            border-color: #a7f3d0;
        }

        .status-pending {
            color: #92400e;
            background: #ffedd5;
            border-color: #fed7aa;
        }

        .status-other {
            color: #334155;
            background: #e2e8f0;
            border-color: #cbd5e1;
        }

    </style>

    <section class="section bookings-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Bookings</h5>
                        <div class="row g-3 mb-3">
                            
                        </div>
                        <div class="mb-3">
                            <a href="{{ route('bookings.create') }}" class="btn btn-sm btn-theme">
                                <i class="bi bi-plus-circle me-1"></i> Add Booking
                            </a>
                        </div>

                        <div class="row g-3">
                            @forelse($bookings as $booking)
                                @php
                                    $housePrice = (float) ($booking->property?->price_per_day ?? 0);
                                    $paymentsTotal = (float) ($booking->payments_total_amount ?? 0);
                                    $deposit = $paymentsTotal > 0 ? $paymentsTotal : (float) $booking->total_amount;
                                    $remaining = max($housePrice - $deposit, 0);
                                    $paidPercentage = $housePrice > 0 ? min(100, round(($deposit / $housePrice) * 100, 2)) : 0;
                                    $statusText = $booking->status === 'confirmed' ? 'Booked' : ucfirst($booking->status);
                                @endphp
                                <div class="col-xl-4 col-md-6">
                                    <div class="booking-grid-card">
                                        <div class="card-top-band"></div>
                                        <div class="card-body">
                                            <h6 class="booking-title">{{ $booking->property?->title ?? '-' }}</h6>
                                            <p class="booking-sub mb-2">{{ $booking->full_name ?: $booking->customer_name }}</p>

                                            <div class="meta-row">
                                                <span class="meta-label">Phone</span>
                                                <span class="meta-value">{{ $booking->customer_phone ?: '-' }}</span>
                                            </div>
                                            <div class="meta-row">
                                                <span class="meta-label">Booking Date</span>
                                                <span class="meta-value">{{ optional($booking->start_date)->format('Y-m-d') }}</span>
                                            </div>
                                            <div class="meta-row">
                                                <span class="meta-label">House Price</span>
                                                <span class="meta-value">{{ number_format($housePrice, 2) }}</span>
                                            </div>
                                            <div class="meta-row">
                                                <span class="meta-label">Deposit</span>
                                                <span class="meta-value">{{ number_format($deposit, 2) }}</span>
                                            </div>
                                            <div class="meta-row">
                                                <span class="meta-label">Remaining</span>
                                                <span class="meta-value">{{ number_format($remaining, 2) }}</span>
                                            </div>
                                            <div class="pay-progress-wrap">
                                                <div class="pay-progress-head">
                                                    <span>Amount Paid</span>
                                                    <span>{{ number_format($paidPercentage, 2) }}%</span>
                                                </div>
                                                <div class="pay-progress">
                                                    <div class="bar" style="width: {{ $paidPercentage }}%;"></div>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <span class="booking-pill {{ $booking->status === 'confirmed' ? 'status-confirmed' : ($booking->status === 'pending' ? 'status-pending' : 'status-other') }}">
                                                    {{ $statusText }}
                                                </span>
                                                <div>
                                                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-outline-dark" title="View Summary">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('bookings.payments.index', $booking->id) }}" class="btn btn-sm btn-theme" title="Customer Paid Amount">
                                                        <i class="bi bi-cash-stack"></i>
                                                    </a>
                                                    <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('bookings.destroy', $booking->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this booking?')">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info mb-0">No bookings found.</div>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-3">
                            {{ $bookings->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

