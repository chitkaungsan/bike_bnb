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
        Schema::create('bike_price_overrides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bike_id');
            $table->date('date');
            $table->decimal('price_override', 10, 2)->nullable(); // nullable if not always overridden
            $table->timestamps();

            // Optional: foreign key if you have bikes table
            $table->foreign('bike_id')->references('id')->on('bikes')->onDelete('cascade');
            
            // Optional: unique constraint for bike + date
            $table->unique(['bike_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
