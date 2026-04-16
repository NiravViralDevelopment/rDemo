@extends('layouts.admin.app')

@section('content')
    <style>
        .properties-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .properties-page .card-title {
            color: #0f172a;
            font-weight: 700;
        }

        .dt-buttons {
            float: right;
            margin-bottom: 10px;
        }
        #propertiesTable_filter {
            float: left;
            margin-bottom: 10px;
        }

        #propertiesTable_filter input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 10px;
            min-width: 230px;
        }

        #propertiesTable_filter input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
        }

        #propertiesTable {
            border-collapse: separate;
            border-spacing: 0;
        }

        #propertiesTable thead th {
            background: linear-gradient(90deg, #eef2ff 0%, #f8fafc 100%);
            color: #1e293b;
            font-weight: 700;
            border-bottom: 1px solid #dbeafe;
            white-space: nowrap;
        }

        #propertiesTable tbody td {
            border-top: 1px solid #f1f5f9;
            color: #334155;
        }

        #propertiesTable.table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #fcfdff;
        }

        #propertiesTable tbody tr:hover td {
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

        .type-house {
            color: #3730a3;
            background: #e0e7ff;
            border-color: #c7d2fe;
        }

        .type-shop {
            color: #9a3412;
            background: #ffedd5;
            border-color: #fed7aa;
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

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #4f46e5 !important;
            color: #fff !important;
            border: 1px solid #4f46e5 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #4338ca !important;
            color: #fff !important;
            border: 1px solid #4338ca !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            margin: 0 2px;
        }
    </style>

    <section class="section properties-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Properties (Houses & Shops)</h5>
                        </div>

                        <div class="table-responsive">
                            <table id="propertiesTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Bedrooms</th>
                                    <th>Area (sqft)</th>
                                    <th>Price/Day</th>
                                    <th>Status</th>
                                    <th>Created</th>
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
                $('#propertiesTable').DataTable({
                    dom: 'Bfrtip',
                    pageLength: 10,
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('properties.data') }}",
                    buttons: [],
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'code', name: 'code' },
                        { data: 'type_badge', name: 'type', orderable: false, searchable: false },
                        { data: 'title', name: 'title' },
                        { data: 'bedrooms', name: 'bedrooms' },
                        { data: 'area_sqft', name: 'area_sqft' },
                        { data: 'price_per_day', name: 'price_per_day' },
                        { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                        { data: 'created_on', name: 'created_at' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search properties'
                    }
                });
            });
        </script>
    @endpush
@endsection

