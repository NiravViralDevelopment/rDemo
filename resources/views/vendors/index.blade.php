@extends('layouts.admin.app')

@section('content')
    <style>
        .vendors-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .vendors-page .card-title {
            color: #0f172a;
            font-weight: 700;
        }

        .vendors-page .dt-buttons {
            float: right;
            margin-bottom: 10px;
            display: flex;
            gap: 8px;
        }

        #vendorsTable_filter {
            float: right;
            margin-bottom: 10px;
            margin-right: 8px;
        }

        #vendorsTable_filter input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 10px;
            min-width: 230px;
        }

        #vendorsTable_filter input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
        }

        #vendorsTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #vendorsTable thead th {
            background: linear-gradient(90deg, #eef2ff 0%, #f8fafc 100%);
            color: #1e293b;
            font-weight: 700;
            border-bottom: 1px solid #dbeafe;
            white-space: nowrap;
        }

        #vendorsTable tbody td {
            border-top: 1px solid #f1f5f9;
            color: #334155;
        }

        #vendorsTable.table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #fcfdff;
        }

        #vendorsTable tbody tr:hover td {
            background: #f8fafc;
        }

        .vendor-action-btn {
            border-radius: 8px;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.86rem;
        }

        .action-material {
            color: #0f766e;
            border: 1px solid #99f6e4;
            background: #f0fdfa;
        }

        .action-material:hover {
            color: #fff;
            border-color: #0f766e;
            background: #0f766e;
        }

        .action-transaction {
            color: #92400e;
            border: 1px solid #fcd34d;
            background: #fffbeb;
        }

        .action-transaction:hover {
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

        #vendorsTable td:last-child,
        #vendorsTable th:last-child {
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

    <section class="section vendors-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Vendors</h5>
                        </div>

                        <div class="table-responsive">
                            <table id="vendorsTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Vendor Name</th>
                                    <th>Phone</th>
                                    <th>GST Number</th>
                                    <th>Total Credit</th>
                                    <th>Total Debit</th>
                                    <th>Balance</th>
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
                $('#vendorsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('vendors.data') }}",
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
                                window.location.href = "{{ route('vendors.create') }}";
                            }
                        }
                    ],
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'vendor_name', name: 'vendor_name' },
                        { data: 'phone', name: 'phone' },
                        { data: 'gst_number', name: 'gst_number' },
                        { data: 'total_credit', name: 'total_credit', orderable: false, searchable: false },
                        { data: 'total_debit', name: 'total_debit', orderable: false, searchable: false },
                        { data: 'balance', name: 'balance', orderable: false, searchable: false },
                        { data: 'description', name: 'description' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search vendors'
                    }
                });
            });
        </script>
    @endpush
@endsection

