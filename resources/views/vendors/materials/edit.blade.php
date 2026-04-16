@extends('layouts.admin.app')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Edit Material - {{ $vendor->vendor_name }}</h5>

                        <form method="POST" action="{{ route('vendors.materials.update', [$vendor->id, $material->id]) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', optional($material->date)->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Challan No</label>
                                    <input type="text" name="challan_no" class="form-control" value="{{ old('challan_no', $material->challan_no) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">QTY</label>
                                    <input type="number" step="0.01" min="0.01" name="qty" class="form-control" value="{{ old('qty', $material->qty) }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Material Name</label>
                                <input type="text" name="material_name" class="form-control" value="{{ old('material_name', $material->material_name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Remark</label>
                                <textarea name="remark" class="form-control" rows="3">{{ old('remark', $material->remark) }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-sm comnBtn">Update</button>
                            <a href="{{ route('vendors.materials.index', $vendor->id) }}" class="btn btn-sm btn-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

