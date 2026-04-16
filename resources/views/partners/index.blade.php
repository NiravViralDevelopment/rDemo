@extends('layouts.admin.app')

@section('content')
    <style>
        .partners-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .partners-page .card-title {
            color: #0f172a;
            font-weight: 700;
        }

        .dt-buttons {
            float: right;
            margin-bottom: 10px;
            display: flex;
            gap: 8px;
        }
        #partnersTable_filter {
            float: right;
            margin-bottom: 10px;
            margin-right: 8px;
        }

        #partnersTable_filter input {
            border: 1px solid #e9a28c;
            border-radius: 14px;
            padding: 10px 14px 10px 54px;
            min-width: 290px;
            height: 48px;
            font-size: 16px;
            background: #fff url("{{ asset('admin/img/search.png') }}") no-repeat 16px center;
            background-size: 26px;
            color: #6b7280;
        }

        #partnersTable_filter input:focus {
            border-color: #d96432;
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.14);
            outline: none;
        }

        #partnersTable_wrapper .dataTables_filter label {
            margin: 0;
            font-size: 0;
        }

        #partnersTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #partnersTable thead th {
            background: linear-gradient(90deg, #eef2ff 0%, #f8fafc 100%);
            color: #1e293b;
            font-weight: 700;
            border-bottom: 1px solid #dbeafe;
            white-space: nowrap;
        }

        #partnersTable tbody td {
            border-top: 1px solid #f1f5f9;
            color: #334155;
        }

        #partnersTable.table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #fcfdff;
        }

        #partnersTable tbody tr:hover td {
            background: #f8fafc;
        }

        .action-group {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
            white-space: nowrap;
            align-items: center;
        }

        .partner-action-btn {
            border-radius: 8px;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 4px;
            font-size: 0.86rem;
        }

        .action-payment {
            color: #92400e;
            border: 1px solid #fcd34d;
            background: #fffbeb;
        }

        .action-payment:hover {
            color: #fff;
            border-color: #d97706;
            background: #d97706;
        }

        .action-edit {
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
            background: #eff6ff;
        }

        .action-edit:hover {
            color: #fff;
            border-color: #2563eb;
            background: #2563eb;
        }

        .action-delete {
            color: #be123c;
            border: 1px solid #fecdd3;
            background: #fff1f2;
        }

        .action-delete:hover {
            color: #fff;
            border-color: #e11d48;
            background: #e11d48;
        }

        .summary-card {
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 14px 16px;
            background: linear-gradient(140deg, #f8fafc 0%, #eef2ff 100%);
        }
        .summary-label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
        }
        .summary-value {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        #partnersTable td:last-child,
        #partnersTable th:last-child {
            white-space: nowrap;
        }

        .dataTables_wrapper .dataTables_info {
            color: #64748b;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #4f46e5 !important;
            color: #fff !important;
            border: 1px solid #4f46e5 !important;
            border-radius: 6px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #4338ca !important;
            color: #fff !important;
            border: 1px solid #4338ca !important;
            border-radius: 6px !important;
        }
    </style>

    <section class="section partners-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Partners</h5>
                        </div>

                        <div class="table-responsive">
                            <table id="partnersTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Total Balance</th>
                                    <th>Total Paid</th>
                                    <th>Pending Amount</th>
                                    <th>Percentage</th>
                                    <th>Details</th>
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
                    <div class="summary-label">Total Balance</div>
                    <p class="summary-value">{{ number_format((float) ($totalBalance ?? 0), 2) }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="summary-card">
                    <div class="summary-label">Total Paid</div>
                    <p class="summary-value">{{ number_format((float) ($totalPaid ?? 0), 2) }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-2">
                <div class="summary-card">
                    <div class="summary-label">Pending Amount</div>
                    <p class="summary-value">{{ number_format((float) ($pendingAmount ?? 0), 2) }}</p>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#partnersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('partners.data') }}",
                    pageLength: 10,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: '<img src="{{asset('admin')}}/img/export.png" class="iconbt img-fluid" alt="export icon"> Export',
                            className: 'btn-export',
                            exportOptions: {
                                columns: ':not(:last-child)'
                            }
                        },
                        {
                            text: '<img src="{{asset('admin')}}/img/plus-icon.png" class="iconbt img-fluid" alt="plus icon"> Add New',
                            className: 'btn-add-order greenBtn',
                            action: function() {
                                window.location.href = "{{ route('partners.create') }}";
                            }
                        }
                    ],
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'name', name: 'name' },
                        { data: 'phone', name: 'phone' },
                        { data: 'total_balance', name: 'total_balance' },
                        { data: 'total_paid', name: 'total_paid', orderable: false, searchable: false },
                        { data: 'pending_amount', name: 'pending_amount', orderable: false, searchable: false },
                        { data: 'percentage', name: 'percentage' },
                        { data: 'details', name: 'details' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search partners'
                    }
                });
            });
        </script>
    @endpush
@endsection

