<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->date('booking_date');
            $table->time('eta');
            $table->integer('total_person')->nullable();
            $table->boolean('need_dm')->default(false);
            $table->decimal('total_price', 12, 2);
            $table->string('payment_status')->default('unpaid');
            $table->string('status')->default('pending');
            $table->string('payment_proof')->nullable();
            $table->text('notes')->nullable();
            $table->unique(['room_id', 'booking_date']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
