@extends('layouts.admin.app')

@section('content')
    <style>
        .dt-buttons { float: right; margin-bottom: 10px; }
        #housesTable_filter { float: right; margin-bottom: 10px; }

        #housesTable_filter input {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 6px 10px;
            min-width: 230px;
        }

        #housesTable_filter input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
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
    </style>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">House List</h5>
                        </div>

                        <div class="table-responsive">
                            <table id="housesTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Bedrooms</th>
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
                $('#housesTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('houses.data') }}",
                    pageLength: 10,
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'title', name: 'title' },
                        { data: 'bedrooms', name: 'bedrooms' },
                        { data: 'area_sqft', name: 'area_sqft' },
                        { data: 'price_per_day', name: 'price_per_day' },
                        { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search houses'
                    }
                });
            });
        </script>
    @endpush
@endsection

