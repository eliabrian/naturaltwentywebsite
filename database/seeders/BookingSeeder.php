<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vipRoom = Room::where('slug', 'vip')->first();
        $dndRoom = Room::where('slug', 'dnd')->first();

        if (! $vipRoom || ! $dndRoom) {
            $this->command->error("Rooms not found! Please run 'php artisan db:seed' to create rooms first.");

            return;
        }

        Booking::create([
            'room_id' => $vipRoom->id,
            'customer_name' => 'Budi Santoso',
            'customer_phone' => '081234567890',
            'booking_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
            'eta' => '14:00',
            'total_person' => null,
            'need_dm' => false,
            'total_price' => $vipRoom->base_cost,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_proof' => 'receipts/sample_transfer_1.jpg',
            'notes' => 'Prepare extra chairs.',
        ]);

        Booking::create([
            'room_id' => $dndRoom->id,
            'customer_name' => 'Sarah Wijaya',
            'customer_phone' => '081987654321',
            'booking_date' => Carbon::now()->addDays(5)->format('Y-m-d'), // 5 days from now
            'eta' => '10:00',
            'total_person' => 6,
            'need_dm' => true,
            'total_price' => 6 * $dndRoom->person_cost,
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'payment_proof' => 'receipts/sample_transfer_2.jpg',
        ]);

        Booking::create([
            'room_id' => $dndRoom->id,
            'customer_name' => 'Kevin Pratama',
            'customer_phone' => '081333444555',
            'booking_date' => Carbon::now()->addMonth()->format('Y-m-d'),
            'eta' => '13:00',
            'total_person' => 4,
            'need_dm' => false,
            'total_price' => 4 * $dndRoom->person_cost,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_proof' => null,
        ]);
    }
}
