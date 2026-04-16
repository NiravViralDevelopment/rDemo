<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BookingPaymentController extends Controller
{
    public function index(int $bookingId)
    {
        $booking = Booking::query()->with('property')->findOrFail($bookingId);
        $this->authorizeBooking($booking);

        return view('bookings.payments.index', compact('booking'));
    }

    public function data(int $bookingId)
    {
        $booking = Booking::query()->findOrFail($bookingId);
        $this->authorizeBooking($booking);

        $query = $booking->payments()->latest('paid_on')->latest('id');

        return DataTables::eloquent($query)
            ->addColumn('paid_on_display', fn ($payment) => optional($payment->paid_on)->format('Y-m-d'))
            ->addColumn('amount_display', fn ($payment) => number_format((float) $payment->amount, 2))
            ->addColumn('description_display', fn ($payment) => $payment->description ?: '-')
            ->addColumn('action', function ($payment) use ($booking) {
                $editUrl = route('bookings.payments.edit', [$booking->id, $payment->id]);
                $deleteUrl = route('bookings.payments.destroy', [$booking->id, $payment->id]);
                $token = csrf_token();

                return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form method="POST" action="' . $deleteUrl . '" class="d-inline">
                        <input type="hidden" name="_token" value="' . $token . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm(\'Delete this payment?\')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create(int $bookingId)
    {
        $booking = Booking::query()->with('property')->findOrFail($bookingId);
        $this->authorizeBooking($booking);

        return view('bookings.payments.create', compact('booking'));
    }

    public function store(Request $request, int $bookingId)
    {
        $booking = Booking::query()->with('property')->findOrFail($bookingId);
        $this->authorizeBooking($booking);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $booking->payments()->create([
            'amount' => $this->normalizeMoneyAmount($validated['amount']),
            'paid_on' => $validated['paid_on'],
            'description' => $validated['description'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('bookings.payments.index', $booking->id)
            ->with('message', 'Payment added successfully.');
    }

    public function edit(int $bookingId, int $paymentId)
    {
        $booking = Booking::query()->with('property')->findOrFail($bookingId);
        $this->authorizeBooking($booking);

        $payment = $booking->payments()->findOrFail($paymentId);

        return view('bookings.payments.edit', compact('booking', 'payment'));
    }

    public function update(Request $request, int $bookingId, int $paymentId)
    {
        $booking = Booking::query()->findOrFail($bookingId);
        $this->authorizeBooking($booking);

        $payment = $booking->payments()->findOrFail($paymentId);
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_on' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $payment->update([
            'amount' => $this->normalizeMoneyAmount($validated['amount']),
            'paid_on' => $validated['paid_on'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('bookings.payments.index', $booking->id)
            ->with('message', 'Payment updated successfully.');
    }

    public function destroy(int $bookingId, int $paymentId)
    {
        $booking = Booking::query()->findOrFail($bookingId);
        $this->authorizeBooking($booking);

        $payment = $booking->payments()->findOrFail($paymentId);
        $payment->delete();

        return redirect()->route('bookings.payments.index', $booking->id)
            ->with('message', 'Payment deleted successfully.');
    }

    private function authorizeBooking(Booking $booking): void
    {
        $user = Auth::user();
        if (! empty($user->project_id) && (int) $booking->project_id !== (int) $user->project_id) {
            abort(403, 'Not allowed.');
        }
    }

    private function normalizeMoneyAmount(mixed $amount): string
    {
        $raw = is_string($amount) ? trim($amount) : (string) $amount;
        $raw = str_replace(',', '', $raw);
        $numeric = is_numeric($raw) ? (float) $raw : 0;

        return number_format($numeric, 2, '.', '');
    }
}
