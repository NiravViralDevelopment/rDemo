@extends('layouts.admin.app')

@section('content')
    <style>
        .booking-payments-page .card {
            border: 1px solid #f1d2c3;
            border-radius: 14px;
            box-shadow: 0 6px 16px rgba(217, 100, 50, 0.08);
        }

        .booking-payments-page .btn-theme {
            background: #d96432;
            border: 1px solid #d96432;
            color: #fff;
        }

        .booking-payments-page .btn-theme:hover {
            background: #bf5225;
            border-color: #bf5225;
            color: #fff;
        }
    </style>

    <section class="section booking-payments-page">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">Customer Paid Amount History</h5>
                                <p class="text-muted mb-0">{{ $booking->property?->title ?? '-' }} - {{ $booking->full_name ?: $booking->customer_name }}</p>
                            </div>
                            <div>
                                <a href="{{ route('bookings.payments.create', $booking->id) }}" class="btn btn-sm btn-theme">
                                    <i class="bi bi-plus-circle me-1"></i>Add Payment
                                </a>
                                <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                            </div>
                        </div>

                        <div class="table-responsive mt-3">
                            <table id="paymentsTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#paymentsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('bookings.payments.data', $booking->id) }}",
                    pageLength: 10,
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'paid_on_display', name: 'paid_on' },
                        { data: 'amount_display', name: 'amount' },
                        { data: 'description_display', name: 'description' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search payments'
                    }
                });
            });
        </script>
    @endpush
@endsection
