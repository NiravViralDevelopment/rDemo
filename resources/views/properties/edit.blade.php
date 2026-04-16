@extends('layouts.admin.app')

@section('content')
    <style>
        .property-edit-page {
            --theme-primary: #d96432;
            --theme-primary-dark: #bf5225;
            --theme-soft: #fff4ee;
            --theme-border: #f3d5c7;
            --theme-text: #243447;
        }

        .property-edit-card {
            border: 1px solid var(--theme-border);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(217, 100, 50, 0.08);
        }
        .property-edit-header {
            background: linear-gradient(135deg, #d96432 0%, #bf5225 100%);
            border-radius: 14px 14px 0 0;
            color: #fff;
            padding: 16px 20px;
        }
        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--theme-text);
            margin-bottom: 12px;
            border-left: 3px solid var(--theme-primary);
            padding-left: 10px;
        }
        .form-label {
            font-weight: 600;
            color: var(--theme-text);
            margin-bottom: 6px;
        }
        .property-edit-page .form-control {
            border: 1px solid #e5d3c7;
            border-radius: 10px;
            min-height: 42px;
        }
        .property-edit-page textarea.form-control {
            min-height: 96px;
        }
        .property-edit-page .form-control:focus {
            border-color: var(--theme-primary);
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.18);
        }
        .property-edit-page .form-control[disabled] {
            background: #f8fafc;
            color: #64748b;
            cursor: not-allowed;
        }
        .btn-theme {
            background: var(--theme-primary);
            color: #fff;
            border: 1px solid var(--theme-primary);
            border-radius: 10px;
            padding: 0.5rem 1rem;
            font-weight: 600;
        }
        .btn-theme:hover {
            background: var(--theme-primary-dark);
            border-color: var(--theme-primary-dark);
            color: #fff;
        }
        .btn-outline-secondary {
            border-radius: 10px;
            padding: 0.5rem 1rem;
        }
    </style>

    <section class="section property-edit-page">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card property-edit-card">
                    <div class="property-edit-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white">
                            <i class="bi bi-pencil-square me-2"></i>Edit Property
                        </h5>
                        <span class="badge bg-light text-dark">ID: {{ $property->id }}</span>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3 mb-0">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('properties.update', $property->id) }}" class="mt-3">
                            @csrf
                            @method('PUT')

                            <div class="section-title">Basic Details</div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Project</label>
                                    <select class="form-control" disabled>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ (string) old('project_id', $property->project_id) === (string) $project->id ? 'selected' : '' }}>
                                                {{ $project->name }} ({{ $project->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="project_id" value="{{ old('project_id', $property->project_id) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Type</label>
                                    <select id="type" class="form-control" disabled>
                                        <option value="house" {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>House</option>
                                        <option value="shop" {{ old('type', $property->type) === 'shop' ? 'selected' : '' }}>Shop</option>
                                    </select>
                                    <input type="hidden" name="type" value="{{ old('type', $property->type) }}">
                                </div>
                            </div>

                            <div class="mb-3 mt-2">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $property->title) }}" required>
                            </div>

                            <div class="section-title mt-3">Property Metrics</div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Area (sqft)</label>
                                    <input type="number" name="area_sqft" class="form-control" value="{{ old('area_sqft', $property->area_sqft) }}">
                                </div>
                                <div class="col-md-6 mb-3" id="bedroomsRow">
                                    <label class="form-label">Bedrooms (House only)</label>
                                    <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms) }}">
                                </div>
                                <div class="col-md-6 mb-3 mt-md-0 mt-1">
                                    <label class="form-label">Price / Day</label>
                                    <input type="number" step="0.01" min="0" name="price_per_day" class="form-control" value="{{ old('price_per_day', $property->price_per_day) }}">
                                </div>
                            </div>

                            <div class="section-title mt-3">Status & Notes</div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="available" {{ old('status', $property->status) === 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="booked" {{ old('status', $property->status) === 'booked' ? 'selected' : '' }}>Booked</option>
                                        <option value="inactive" {{ old('status', $property->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $property->description) }}</textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-theme">
                                    <i class="bi bi-check-circle me-1"></i> Update
                                </button>
                                <a href="{{ route('houses.index') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            const typeEl = document.getElementById('type');
            const bedroomsRow = document.getElementById('bedroomsRow');
            const toggleBedrooms = () => {
                bedroomsRow.style.display = typeEl.value === 'house' ? '' : 'none';
            };
            typeEl.addEventListener('change', toggleBedrooms);
            toggleBedrooms();
        </script>
    @endpush
@endsection

