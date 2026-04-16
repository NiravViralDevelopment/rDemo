@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create Property (House / Shop)</h5>

                        <form method="POST" action="{{ route('properties.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Project</label>
                                <select name="project_id" class="form-control" required>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ (string) old('project_id', $user->project_id) === (string) $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="house" {{ old('type') === 'house' ? 'selected' : '' }}>House</option>
                                    <option value="shop" {{ old('type') === 'shop' ? 'selected' : '' }}>Shop</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Area (sqft)</label>
                                <input type="number" name="area_sqft" class="form-control" value="{{ old('area_sqft') }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3" id="bedroomsRow">
                                    <label class="form-label">Bedrooms (House only)</label>
                                    <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', 2) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Price / Day</label>
                                    <input type="number" step="0.01" min="0" name="price_per_day" class="form-control" value="{{ old('price_per_day', 0) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="booked" {{ old('status') === 'booked' ? 'selected' : '' }}>Booked</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Save</button>
                            <a href="{{ route('houses.index') }}" class="btn btn-sm btn-secondary">Back</a>
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

