@extends('layouts.admin.app')

@section('content')
    <style>
        .booking-form-page {
            --theme-primary: #d96432;
            --theme-primary-dark: #bf5225;
            --theme-border: #f3d5c7;
        }

        .booking-form-page .card {
            border: 1px solid var(--theme-border);
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(217, 100, 50, 0.08);
        }

        .booking-form-page .card-title {
            color: #243447;
            font-weight: 700;
        }

        .booking-form-page .form-label {
            font-weight: 600;
            color: #243447;
        }

        .booking-form-page .form-control, .booking-form-page .form-select {
            border-radius: 10px;
            min-height: 42px;
        }

        .booking-form-page .form-control:focus, .booking-form-page .form-select:focus {
            border-color: var(--theme-primary);
            box-shadow: 0 0 0 3px rgba(217, 100, 50, 0.18);
        }

        .booking-form-page .btn-theme {
            background: var(--theme-primary);
            border: 1px solid var(--theme-primary);
            color: #fff;
            border-radius: 10px;
        }

        .booking-form-page .btn-theme:hover {
            background: var(--theme-primary-dark);
            border-color: var(--theme-primary-dark);
            color: #fff;
        }
    </style>

    <section class="section booking-form-page">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Create Booking</h5>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('bookings.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Property (House / Shop)</label>
                                    <select name="property_id" class="form-select" required>
                                        <option value="">Select Property</option>
                                        @foreach($properties as $property)
                                            <option value="{{ $property->id }}" {{ (string) old('property_id') === (string) $property->id ? 'selected' : '' }}>
                                                {{ ucfirst($property->type) }} - {{ $property->title }} ({{ $property->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ old('status', 'confirmed') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="customer_email" class="form-control" value="{{ old('customer_email') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" name="customer_phone" class="form-control" value="{{ old('customer_phone') }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Booking Date</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Amount</label>
                                    <input type="text" name="total_amount" class="form-control amount-only" inputmode="decimal" autocomplete="off" value="{{ old('total_amount', 0) }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-theme">Save Booking</button>
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            document.querySelectorAll('.amount-only').forEach(function(input) {
                input.addEventListener('input', function() {
                    let value = this.value.replace(/[^0-9.]/g, '');
                    const parts = value.split('.');
                    if (parts.length > 2) {
                        value = parts.shift() + '.' + parts.join('');
                    }
                    this.value = value;
                });
            });
        </script>
    @endpush
@endsection
