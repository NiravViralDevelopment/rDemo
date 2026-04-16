@extends('layouts.admin.app')

@section('content')
    <style>
        .property-grid-header {
            background: linear-gradient(135deg, #4154f1 0%, #6f42c1 100%);
            color: #fff;
            border-radius: 12px;
            padding: 14px 18px;
        }
        .property-card {
            border: 0;
            border-radius: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .property-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        .property-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 1.2rem;
            color: #fff;
        }
        .property-icon.house { background: #198754; }
        .property-icon.shop { background: #fd7e14; }
        .property-meta {
            font-size: 0.84rem;
            color: #6c757d;
            margin-bottom: 4px;
        }
        .price-chip {
            background: #eef2ff;
            color: #4154f1;
            border-radius: 999px;
            padding: 5px 10px;
            font-weight: 600;
            font-size: 0.82rem;
        }
    </style>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="property-grid-header d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 text-white">
                        <i class="bi {{ $type === 'house' ? 'bi-house-door-fill' : 'bi-shop-window' }} me-2"></i>
                        {{ $title }} (Grid View)
                    </h5>
                    <a href="{{ route('properties.create') }}" class="btn btn-sm btn-light">
                        <i class="bi bi-plus-circle me-1"></i> Add {{ ucfirst($type) }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-3">
            @forelse($items as $item)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm property-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="property-icon {{ $type }}">
                                        <i class="bi {{ $type === 'house' ? 'bi-house-fill' : 'bi-shop' }}"></i>
                                    </span>
                                    <h6 class="mb-0">{{ $item->title }}</h6>
                                </div>
                                <span class="badge bg-{{ $item->status === 'available' ? 'success' : ($item->status === 'booked' ? 'danger' : 'secondary') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            <p class="property-meta"><strong>Code:</strong> {{ $item->code }}</p>
                            <p class="property-meta"><strong>Project:</strong> {{ $item->project?->name ?? 'GLOBAL' }}</p>
                            <p class="property-meta"><strong>Area:</strong> {{ $item->area_sqft ?? '-' }} sqft</p>
                            @if($type === 'house')
                                <p class="property-meta"><strong>Bedrooms:</strong> {{ $item->bedrooms ?? '-' }}</p>
                            @endif
                            <div class="mb-3">
                                <span class="price-chip">Price/Day: {{ number_format((float) $item->price_per_day, 2) }}</span>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('properties.edit', $item->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('properties.destroy', $item->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this {{ $type }}?')">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info mb-0">No {{ $type }} records found.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-3">
            {{ $items->links() }}
        </div>
    </section>
@endsection

