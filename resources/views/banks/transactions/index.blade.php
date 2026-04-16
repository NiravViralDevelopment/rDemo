@extends('layouts.admin.app')

@section('content')
    <style>
        .bank-transactions-page .summary-card {
            border: 1px solid #f1d2c3;
            border-radius: 12px;
            background: #fff;
            padding: 14px 16px;
            box-shadow: 0 6px 16px rgba(217, 100, 50, 0.08);
        }

        .bank-transactions-page .summary-label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .bank-transactions-page .summary-value {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
    </style>

    <section class="section bank-transactions-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title mb-1">Bank Transactions</h5>
                                <p class="text-muted mb-0">{{ $bank->bank_name }} ({{ $bank->account_number }})</p>
                            </div>
                            <div>
                                <a href="{{ route('banks.transactions.create', $bank->id) }}" class="btn btn-sm btn-warning text-white">
                                    <i class="bi bi-plus-circle me-1"></i>Add Payment
                                </a>
                                <a href="{{ route('banks.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
                            </div>
                        </div>

                        <div class="table-responsive mt-3">
                            <table id="bankTransactionsTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>GST Number</th>
                                    <th>GST %</th>
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

        <div class="row mt-2">
            <div class="col-md-4 mb-2">
                <div class="summary-card">
                    <div class="summary-label">Total Credit</div>
                    <p class="summary-value">{{ number_format($creditTotal, 2) }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="summary-card">
                    <div class="summary-label">Total Debit</div>
                    <p class="summary-value">{{ number_format($debitTotal, 2) }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="summary-card">
                    <div class="summary-label">Current Balance</div>
                    <p class="summary-value">{{ number_format($currentBalance, 2) }}</p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#bankTransactionsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('banks.transactions.data', $bank->id) }}",
                    pageLength: 10,
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'date_display', name: 'transaction_date' },
                        { data: 'type_badge', name: 'type', orderable: false, searchable: false },
                        { data: 'amount_display', name: 'amount' },
                        { data: 'gst_number_display', name: 'gst_number' },
                        { data: 'gst_percent_display', name: 'gst_percent', orderable: false, searchable: false },
                        { data: 'description', name: 'description' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search transactions'
                    }
                });
            });
        </script>
    @endpush
@endsection
