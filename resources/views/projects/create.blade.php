@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create Project</h5>

                        <form method="POST" action="{{ route('projects.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Project Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Project Code</label>
                                <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                                <small class="text-muted">Use uppercase code like: HARIHAR, HARMONY, SWASTIC</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Auto Create Houses</label>
                                    <input type="number" min="0" name="house_count" class="form-control" value="{{ old('house_count', 0) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Auto Create Shops</label>
                                    <input type="number" min="0" name="shop_count" class="form-control" value="{{ old('shop_count', 0) }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Save Project</button>
                            <a href="{{ route('projects.index') }}" class="btn btn-sm btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

