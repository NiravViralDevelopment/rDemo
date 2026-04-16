@extends('layouts.admin.app')

@section('content')
    <style>
        .banks-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .banks-page .card-title {
            color: #0f172a;
            font-weight: 700;
        }

        #banksTable_filter {
            float: right;
            margin-bottom: 10px;
        }

        #banksTable_filter input {
            border: 1px solid #e9a28c;
            border-radius: 14px;
            padding: 10px 14px;
            min-width: 280px;
        }

        #banksTable_filter input:focus {
            border-color: #d96432;
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.14);
            outline: none;
        }
    </style>

    <section class="section banks-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Banks</h5>
                        </div>

                        <div class="table-responsive">
                            <table id="banksTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bank Name</th>
                                    <th>IFSC Code</th>
                                    <th>Branch</th>
                                    <th>Account Number</th>
                                    <th>Account Holder</th>
                                    <th>Credit</th>
                                    <th>Debit</th>
                                    <th>Balance</th>
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
                $('#banksTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('banks.data') }}",
                    pageLength: 10,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: '<img src="{{asset('admin')}}/img/plus-icon.png" class="iconbt img-fluid" alt="plus icon"> Add New',
                            className: 'btn-add-order greenBtn',
                            action: function() {
                                window.location.href = "{{ route('banks.create') }}";
                            }
                        }
                    ],
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'bank_name', name: 'bank_name' },
                        { data: 'ifsc_code', name: 'ifsc_code' },
                        { data: 'branch_name', name: 'branch_name' },
                        { data: 'account_number', name: 'account_number' },
                        { data: 'account_holder_name', name: 'account_holder_name' },
                        { data: 'total_credit_display', name: 'total_credit', orderable: false, searchable: false },
                        { data: 'total_debit_display', name: 'total_debit', orderable: false, searchable: false },
                        { data: 'current_balance_display', name: 'current_balance', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search banks'
                    }
                });
            });
        </script>
    @endpush
@endsection
