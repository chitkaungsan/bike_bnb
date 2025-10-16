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
            $table->unsignedBigInteger('bike_id');
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->enum('status', ['pending', 'confirmed', 'in_use', 'completed', 'cancelled'])->default('pending');
            $table->string('name');
            $table->string('phone', 20);
            $table->integer('days');
            $table->decimal('daily_rate', 10, 2); // changed from integer to decimal
            $table->decimal('total_price', 10, 2); // changed from integer to decimal
            $table->string('payment_type')->nullable();
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
