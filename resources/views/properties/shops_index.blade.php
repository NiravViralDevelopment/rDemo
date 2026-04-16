@extends('layouts.admin.app')

@section('content')
    <style>
        .shops-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .shops-page .card-title {
            color: #0f172a;
            font-weight: 700;
        }

        .dt-buttons { float: right; margin-bottom: 10px; }
        #shopsTable_filter { float: right; margin-bottom: 10px; }

        #shopsTable_filter input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 10px;
            min-width: 230px;
        }

        #shopsTable_filter input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
        }

        #shopsTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #shopsTable thead th {
            background: linear-gradient(90deg, #eef2ff 0%, #f8fafc 100%);
            color: #1e293b;
            font-weight: 700;
            border-bottom: 1px solid #dbeafe;
            white-space: nowrap;
        }

        #shopsTable tbody td {
            border-top: 1px solid #f1f5f9;
            color: #334155;
        }

        #shopsTable.table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #fcfdff;
        }

        #shopsTable tbody tr:hover td {
            background: #f8fafc;
        }

        .property-pill {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3rem 0.65rem;
            border-radius: 999px;
            border: 1px solid transparent;
            letter-spacing: 0.2px;
        }

        .status-available {
            color: #065f46;
            background: #d1fae5;
            border-color: #a7f3d0;
        }

        .status-booked {
            color: #9f1239;
            background: #ffe4e6;
            border-color: #fecdd3;
        }

        .status-inactive {
            color: #334155;
            background: #e2e8f0;
            border-color: #cbd5e1;
        }

        .property-action-btn {
            border-radius: 8px;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 4px;
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

        .dataTables_wrapper .dataTables_info {
            color: #64748b;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #d96432 !important;
            color: #fff !important;
            border: 1px solid #d96432 !important;
            border-radius: 6px !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #d96432 !important;
            color: #fff !important;
            border: 1px solid #d96432 !important;
            border-radius: 6px !important;
        }
    </style>

    <section class="section shops-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Shop List</h5>
                        </div>

                        <div class="table-responsive">
                            <table id="shopsTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Area (sqft)</th>
                                    <th>Price/Day</th>
                                    <th>Status</th>
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
                $('#shopsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('shops.data') }}",
                    pageLength: 10,
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'title', name: 'title' },
                        { data: 'area_sqft', name: 'area_sqft' },
                        { data: 'price_per_day', name: 'price_per_day' },
                        { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search shops'
                    }
                });
            });
        </script>
    @endpush
@endsection

