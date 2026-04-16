@extends('layouts.admin.app')

@section('content')
    <style>
        .vendor-material-page .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .vendor-material-page .card-title {
            color: #0f172a;
            font-weight: 700;
        }

        .material-form-wrap {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 14px 14px 8px;
            background: #fafcff;
            margin-bottom: 16px;
        }

        .vendor-material-page .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .vendor-material-page .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            min-height: 42px;
            padding: 8px 12px;
            color: #0f172a;
        }

        .vendor-material-page .form-control:focus {
            border-color: #d96432;
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.14);
            outline: none;
        }

        .material-form-actions {
            border-top: 1px solid #e2e8f0;
            margin-top: 8px;
            padding-top: 12px;
            display: flex;
            gap: 8px;
        }

        .btn-theme {
            background: #fff3df;
            color: #a04925;
            border: 1px solid #e8b679;
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            font-weight: 600;
        }
        .btn-theme:hover {
            background: #fff;
            color: #d96432;
            border-color: #d96432;
        }

    </style>

    <section class="section vendor-material-page">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Vendor Materials - {{ $vendor->vendor_name }}</h5>

                        <form method="POST" action="{{ route('vendors.materials.store', $vendor->id) }}" class="material-form-wrap">
                            @csrf
                            <div class="row">
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" required>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">Challan No</label>
                                    <input type="text" name="challan_no" class="form-control" value="{{ old('challan_no') }}">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label class="form-label">QTY</label>
                                    <input type="number" step="0.01" min="0.01" name="qty" class="form-control" value="{{ old('qty') }}" required>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Material Name</label>
                                    <input type="text" name="material_name" class="form-control" value="{{ old('material_name') }}" required>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label class="form-label">Remark</label>
                                    <input type="text" name="remark" class="form-control" value="{{ old('remark') }}">
                                </div>
                            </div>
                            <div class="material-form-actions">
                                <button type="submit" class="btn btn-sm comnBtn">Add Material</button>
                                <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-secondary">Back</a>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table id="vendorMaterialsTable" class="table table-striped align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Challan No</th>
                                    <th>QTY</th>
                                    <th>Material Name</th>
                                    <th>Remark</th>
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
                $('#vendorMaterialsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('vendors.materials.data', $vendor->id) }}",
                    pageLength: 10,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            text: '<i class="bi bi-arrow-left-circle me-1"></i> Back to Vendors',
                            className: 'btn-theme',
                            action: function() {
                                window.location.href = "{{ route('vendors.index') }}";
                            }
                        }
                    ],
                    columns: [
                        { data: 'id', name: 'id' },
                        { data: 'date_display', name: 'date' },
                        { data: 'challan_display', name: 'challan_no' },
                        { data: 'qty_display', name: 'qty' },
                        { data: 'material_name', name: 'material_name' },
                        { data: 'remark_display', name: 'remark' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ],
                    language: {
                        search: '',
                        searchPlaceholder: 'Search materials'
                    }
                });
            });
        </script>
    @endpush
@endsection

