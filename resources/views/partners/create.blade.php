@extends('layouts.admin.app')

@section('content')
    <style>
        .partner-form-wrap .card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .partner-form-wrap .card-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 2px;
        }

        .partner-form-wrap .form-subtitle {
            margin: 0 0 18px;
            color: #64748b;
            font-size: 13px;
        }

        .partner-form-wrap .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .partner-form-wrap .required-star {
            color: #dc2626;
            margin-left: 3px;
        }

        .partner-form-wrap .form-control {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            min-height: 42px;
            padding: 8px 12px;
            color: #0f172a;
        }

        .partner-form-wrap .form-control:focus {
            border-color: #d96432;
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.14);
        }

        .partner-form-wrap textarea.form-control {
            min-height: 92px;
            resize: vertical;
        }

        .partner-form-wrap .form-actions {
            border-top: 1px solid #e2e8f0;
            margin-top: 16px;
            padding-top: 14px;
            display: flex;
            gap: 8px;
        }

        .partner-form-wrap .btn-back {
            border-radius: 8px;
            padding: 8px 16px;
        }
    </style>

    <section class="section partner-form-wrap">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body p-4 p-lg-4">
                        <h5 class="card-title">Create Partner</h5>
                        <p class="form-subtitle">Add partner details and financial setup for the selected project.</p>

                        <form method="POST" action="{{ route('partners.store') }}">
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
                                    <label class="form-label">Name<span class="required-star">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Percentage</label>
                                    <input type="number" step="0.01" min="0" max="100" name="percentage" class="form-control" value="{{ old('percentage', 0) }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Balance</label>
                                    <input type="number" step="0.01" min="0" name="total_balance" class="form-control" value="{{ old('total_balance', 0) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Details</label>
                                    <textarea name="details" class="form-control">{{ old('details') }}</textarea>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-sm comnBtn">Save Partner</button>
                                <a href="{{ route('partners.index') }}" class="btn btn-sm btn-secondary btn-back">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

