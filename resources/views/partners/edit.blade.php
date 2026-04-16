@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Partner</h5>

                        <form method="POST" action="{{ route('partners.update', $partner->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Project</label>
                                <select name="project_id" class="form-control">
                                    <option value="">GLOBAL</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ (string) old('project_id', $partner->project_id) === (string) $project->id ? 'selected' : '' }}>
                                            {{ $project->name }} ({{ $project->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $partner->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $partner->phone) }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Balance</label>
                                    <input type="number" step="0.01" min="0" name="total_balance" class="form-control" value="{{ old('total_balance', $partner->total_balance) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Percentage</label>
                                    <input type="number" step="0.01" min="0" max="100" name="percentage" class="form-control" value="{{ old('percentage', $partner->percentage) }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Details</label>
                                <textarea name="details" class="form-control" rows="3">{{ old('details', $partner->details) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Update</button>
                            <a href="{{ route('partners.index') }}" class="btn btn-sm btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

