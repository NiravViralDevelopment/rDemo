@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Vendor</h5>

                        <form method="POST" action="{{ route('vendors.update', $vendor->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Project</label>
                                <select name="project_id" class="form-control">
                                    <option value="">GLOBAL</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ (string) old('project_id', $vendor->project_id) === (string) $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Vendor Name</label>
                                <input type="text" name="vendor_name" class="form-control" value="{{ old('vendor_name', $vendor->vendor_name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $vendor->phone) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">GST Number</label>
                                <input type="text" name="gst_number" class="form-control" value="{{ old('gst_number', $vendor->gst_number) }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $vendor->description) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Update</button>
                            <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

