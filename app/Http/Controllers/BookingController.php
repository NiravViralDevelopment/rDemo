<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $summaryQuery = Booking::query()
            ->with('property:id,price_per_day')
            ->withSum('payments as payments_total_amount', 'amount')
            ->when(! empty($user->project_id), fn ($q) => $q->where('project_id', $user->project_id));

        $summaryBookings = (clone $summaryQuery)->get(['id', 'property_id', 'total_amount']);
        $totalPrice = (float) $summaryBookings->sum(fn (Booking $booking) => (float) ($booking->property?->price_per_day ?? 0));
        $depositPrice = (float) $summaryBookings->sum(function (Booking $booking) {
            $paymentsTotal = (float) ($booking->payments_total_amount ?? 0);
            return $paymentsTotal > 0 ? $paymentsTotal : (float) $booking->total_amount;
        });
        $pendingPrice = max($totalPrice - $depositPrice, 0);
        $depositPercentage = $totalPrice > 0 ? round(($depositPrice / $totalPrice) * 100, 2) : 0;

        $bookings = Booking::query()
            ->with('property')
            ->withSum('payments as payments_total_amount', 'amount')
            ->when(! empty($user->project_id), fn ($q) => $q->where('project_id', $user->project_id))
            ->latest('id')
            ->paginate(12);

        return view('bookings.index', compact(
            'totalPrice',
            'depositPrice',
            'pendingPrice',
            'depositPercentage',
            'bookings'
        ));
    }

    public function create()
    {
        $user = Auth::user();

        $properties = Property::query()
            ->when(! empty($user->project_id), fn ($q) => $q->where('project_id', $user->project_id))
            ->whereIn('type', ['house', 'shop'])
            ->where('status', 'available')
            ->orderBy('type')
            ->orderBy('title')
            ->get(['id', 'project_id', 'type', 'title', 'code', 'price_per_day']);

        return view('bookings.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'full_name' => ['required', 'string', 'max:150'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $user = Auth::user();
        $property = Property::query()->findOrFail((int) $validated['property_id']);
        $this->authorizeProperty($property);
        if ($property->status !== 'available') {
            return back()
                ->withErrors(['property_id' => 'Selected property is not available for booking.'])
                ->withInput();
        }

        $validated['project_id'] = $property->project_id;
        $validated['user_id'] = $user->id;
        $validated['total_amount'] = $this->normalizeMoneyAmount($validated['total_amount'] ?? 0);
        // Keep legacy columns filled without showing extra fields in UI.
        $validated['customer_name'] = $validated['full_name'];
        $validated['end_date'] = $validated['start_date'];

        $booking = Booking::query()->create($this->filterPersistableBookingData($validated));
        $this->syncPropertyStatusAfterBookingChange($property, $validated['status'], $booking->id);

        return redirect()->route('bookings.index')->with('message', 'Booking created successfully.');
    }

    public function edit(int $id)
    {
        $booking = Booking::query()->with('property')->findOrFail($id);
        $this->authorizeBooking($booking);

        $user = Auth::user();
        $properties = Property::query()
            ->when(! empty($user->project_id), fn ($q) => $q->where('project_id', $user->project_id))
            ->whereIn('type', ['house', 'shop'])
            ->orderBy('type')
            ->orderBy('title')
            ->get(['id', 'project_id', 'type', 'title', 'code', 'price_per_day']);

        return view('bookings.edit', compact('booking', 'properties'));
    }

    public function show(int $id)
    {
        $booking = Booking::query()
            ->with(['property', 'payments'])
            ->withSum('payments as payments_total_amount', 'amount')
            ->findOrFail($id);
        $this->authorizeBooking($booking);

        $housePrice = (float) ($booking->property?->price_per_day ?? 0);
        $paymentsTotal = (float) ($booking->payments_total_amount ?? 0);
        $deposit = $paymentsTotal > 0 ? $paymentsTotal : (float) $booking->total_amount;
        $remaining = max($housePrice - $deposit, 0);
        $paidPercentage = $housePrice > 0 ? min(100, round(($deposit / $housePrice) * 100, 2)) : 0;

        return view('bookings.show', compact(
            'booking',
            'housePrice',
            'deposit',
            'remaining',
            'paidPercentage'
        ));
    }

    public function update(Request $request, int $id)
    {
        $booking = Booking::query()->findOrFail($id);
        $this->authorizeBooking($booking);
        $originalPropertyId = (int) $booking->property_id;
        $originalStatus = (string) $booking->status;

        $validated = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'full_name' => ['required', 'string', 'max:150'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,confirmed,cancelled'],
        ]);

        $property = Property::query()->findOrFail((int) $validated['property_id']);
        $this->authorizeProperty($property);

        $validated['project_id'] = $property->project_id;
        $validated['total_amount'] = $this->normalizeMoneyAmount($validated['total_amount'] ?? 0);
        // Keep legacy columns filled without showing extra fields in UI.
        $validated['customer_name'] = $validated['full_name'];
        $validated['end_date'] = $validated['start_date'];

        $booking->update($this->filterPersistableBookingData($validated));
        $this->syncOldPropertyStatusIfNeeded($originalPropertyId, $booking->id, $originalStatus, (int) $validated['property_id']);
        $this->syncPropertyStatusAfterBookingChange($property, $validated['status'], $booking->id);

        return redirect()->route('bookings.index')->with('message', 'Booking updated successfully.');
    }

    public function destroy(int $id)
    {
        $booking = Booking::query()->findOrFail($id);
        $this->authorizeBooking($booking);

        $booking->delete();

        return redirect()->route('bookings.index')->with('message', 'Booking deleted successfully.');
    }

    public function data()
    {
        $query = Booking::query()
            ->with(['property', 'project'])
            ->latest('id');

        $user = Auth::user();
        if (! empty($user->project_id)) {
            $query->where('project_id', $user->project_id);
        }

        return DataTables::eloquent($query)
            ->addColumn('project_name', fn (Booking $booking) => $booking->project?->name ?? 'GLOBAL')
            ->addColumn('property_title', function (Booking $booking) {
                $title = $booking->property?->title ?? '-';
                return $title;
            })
            ->addColumn('full_name_display', fn (Booking $booking) => $booking->full_name ?: $booking->customer_name)
            ->addColumn('customer_email_display', fn (Booking $booking) => $booking->customer_email ?: '-')
            ->addColumn('customer_phone_display', fn (Booking $booking) => $booking->customer_phone ?: '-')
            ->addColumn('booking_date_display', fn (Booking $booking) => optional($booking->start_date)->format('Y-m-d'))
            ->addColumn('house_price_display', function (Booking $booking) {
                $housePrice = (float) ($booking->property?->price_per_day ?? 0);
                return number_format($housePrice, 2);
            })
            ->addColumn('deposit_amount_display', function (Booking $booking) {
                $deposit = (float) $booking->total_amount;
                return number_format($deposit, 2);
            })
            ->addColumn('remaining_amount_display', function (Booking $booking) {
                $housePrice = (float) ($booking->property?->price_per_day ?? 0);
                $deposit = (float) $booking->total_amount;
                $remaining = max($housePrice - $deposit, 0);

                return number_format($remaining, 2);
            })
            ->addColumn('description_display', fn (Booking $booking) => $booking->description ?: '-')
            ->addColumn('status_badge', function (Booking $booking) {
                $statusClass = match ($booking->status) {
                    'confirmed' => 'status-confirmed',
                    'pending' => 'status-pending',
                    default => 'status-other',
                };
                $statusText = $booking->status === 'confirmed' ? 'Booked' : ucfirst($booking->status);

                return '<span class="booking-pill ' . $statusClass . '">' . $statusText . '</span>';
            })
            ->addColumn('action', function (Booking $booking) {
                $editUrl = route('bookings.edit', $booking->id);
                $deleteUrl = route('bookings.destroy', $booking->id);
                $token = csrf_token();

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary" title="Edit Booking">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete Booking" onclick="return confirm(\'Delete this booking?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->addColumn('created_on', fn (Booking $booking) => optional($booking->created_at)->format('Y-m-d'))
            ->rawColumns(['property_title', 'status_badge', 'action'])
            ->toJson();
    }

    private function authorizeBooking(Booking $booking): void
    {
        $user = Auth::user();
        if (! empty($user->project_id) && (int) $booking->project_id !== (int) $user->project_id) {
            abort(403, 'Not allowed.');
        }
    }

    private function authorizeProperty(Property $property): void
    {
        $user = Auth::user();
        if (! empty($user->project_id) && (int) $property->project_id !== (int) $user->project_id) {
            abort(403, 'Not allowed.');
        }
    }

    private function filterPersistableBookingData(array $validated): array
    {
        $columns = Schema::getColumnListing('bookings');
        $allowed = array_flip($columns);

        return array_intersect_key($validated, $allowed);
    }

    private function normalizeMoneyAmount(mixed $amount): string
    {
        $raw = is_string($amount) ? trim($amount) : (string) $amount;
        $raw = str_replace(',', '', $raw);
        $numeric = is_numeric($raw) ? (float) $raw : 0;

        return number_format($numeric, 2, '.', '');
    }

    private function syncPropertyStatusAfterBookingChange(Property $property, string $bookingStatus, int $bookingId): void
    {
        if ($bookingStatus === 'confirmed') {
            if ($property->status !== 'booked') {
                $property->update(['status' => 'booked']);
            }
            return;
        }

        $hasAnyConfirmed = Booking::query()
            ->where('property_id', $property->id)
            ->where('status', 'confirmed')
            ->where('id', '!=', $bookingId)
            ->exists();

        if (! $hasAnyConfirmed && $property->status === 'booked') {
            $property->update(['status' => 'available']);
        }
    }

    private function syncOldPropertyStatusIfNeeded(int $oldPropertyId, int $bookingId, string $oldStatus, int $newPropertyId): void
    {
        if ($oldStatus !== 'confirmed' || $oldPropertyId === $newPropertyId) {
            return;
        }

        $oldProperty = Property::query()->find($oldPropertyId);
        if (! $oldProperty) {
            return;
        }

        $hasAnyConfirmed = Booking::query()
            ->where('property_id', $oldPropertyId)
            ->where('status', 'confirmed')
            ->where('id', '!=', $bookingId)
            ->exists();

        if (! $hasAnyConfirmed && $oldProperty->status === 'booked') {
            $oldProperty->update(['status' => 'available']);
        }
    }
}
