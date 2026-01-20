<?php

namespace App\Http\Controllers;

use App\Events\BookingCreated;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::select(['id', 'name', 'slug', 'deposit', 'base_cost', 'person_cost'])->get();

        $bookedDates = Booking::where('booking_date', '>=', now()->format('Y-m-d'))
            ->whereIn('status', ['confirmed', 'pending'])
            ->select(['room_id', 'booking_date'])
            ->get()
            ->groupBy('room_id')
            ->map(function ($events) {
                return $events->pluck('booking_date')->toArray();
            })
            ->toArray();

        $bank = [
            'name' => env('BANK_NAME'),
            'number' => env('BANK_ACCOUNT_NUMBER'),
            'holder' => env('BANK_ACCOUNT_NAME'),
        ];

        return view('bookings.create', compact('rooms', 'bank', 'bookedDates'));
    }

    public function store(StoreBookingRequest $request)
    {
        $room = Room::findOrFail($request->room_id);
        $totalPrice = 0;

        if ($room->slug === 'vip') {
            $totalPrice = $room->base_cost; // 700,000
            $request->merge(['total_person' => null, 'need_dm' => false]);
        } elseif ($room->slug === 'dnd') {
            // Safety check: if they hacked the HTML to remove "required"
            if (! $request->total_person) {
                return back()->withErrors(['total_person' => 'Total person is required for D&D Room'])->withInput();
            }
            $totalPrice = $request->total_person * $room->person_cost; // Count * 85,000
        }

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            // Stores in storage/app/public/receipts
            $proofPath = $request->file('payment_proof')->store('receipts', 'public');
        }

        try {
            $booking = Booking::create([
                'room_id' => $request->room_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'booking_date' => $request->booking_date,
                'eta' => $request->eta,
                'notes' => $request->notes ?? "",

                'total_person' => $request->total_person,
                'need_dm' => $request->has('need_dm'),

                'total_price' => $totalPrice,
                'payment_proof' => $proofPath,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            BookingCreated::dispatch($booking);

            $users = User::all();

            Notification::make()
                ->title("New {$booking->room->name} Booking!")
                ->body("{$booking->customer_name} just booked.")
                ->success()
                ->broadcast($users)
                ->save();

            return redirect()->back()->with('success', 'Booking requested! We will check your payment and confirm via WhatsApp.');

        } catch (QueryException $e) {
            // Error 1062 is MySQL "Duplicate Entry"
            if ($e->errorInfo[1] == 1062) {
                // If we uploaded a file but the booking failed, we should delete the file
                if ($proofPath) {
                    Storage::disk('public')->delete($proofPath);
                }

                return redirect()->back()
                    ->withInput()
                    ->withErrors(['booking_date' => 'We apologize! Someone else booked this date just seconds ago. Please choose another date.']);
            }

            throw $e; // Throw other errors
        }
    }
}
