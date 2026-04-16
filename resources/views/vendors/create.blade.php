@extends('layouts.admin.app')

@section('content')
    <style>
        .vendor-form-wrap .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .vendor-form-wrap .card-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .vendor-form-wrap .form-subtitle {
            margin: 0 0 18px;
            color: #64748b;
            font-size: 13px;
        }

        .vendor-form-wrap .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .vendor-form-wrap .required-star {
            color: #dc2626;
            margin-left: 3px;
        }

        .vendor-form-wrap .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            min-height: 42px;
            padding: 8px 12px;
            color: #0f172a;
        }

        .vendor-form-wrap .form-control:focus {
            border-color: #d96432;
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.14);
        }

        .vendor-form-wrap textarea.form-control {
            min-height: 92px;
            resize: vertical;
        }

        .vendor-form-wrap .form-actions {
            border-top: 1px solid #e2e8f0;
            margin-top: 16px;
            padding-top: 14px;
            display: flex;
            gap: 8px;
        }

        .vendor-form-wrap .btn-back {
            border-radius: 8px;
            padding: 8px 16px;
        }
    </style>

    <section class="section vendor-form-wrap">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body p-4 p-lg-4">
                        <h5 class="card-title">Create Vendor</h5>
                        <p class="form-subtitle">Add vendor details for your project and keep procurement records organized.</p>

                        <form method="POST" action="{{ route('vendors.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Project</label>
                                    <select name="project_id" class="form-control">
                                        <option value="">GLOBAL</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ (string) old('project_id') === (string) $project->id ? 'selected' : '' }}>
                                                {{ $project->name }} ({{ $project->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Vendor Name<span class="required-star">*</span></label>
                                    <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GST Number</label>
                                    <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number') }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-sm comnBtn">Save Vendor</button>
                                <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-secondary btn-back">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

