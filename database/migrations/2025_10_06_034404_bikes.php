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
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->striCng('photo')->nullable();
            $table->string('license_image')->nullable();
            $table->string('model')->nullable();
            $table->smallInteger('year')->nullable();
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->enum('type', ['auto', 'manual', 'ev'])->default('auto');
            $table->foreignId('cat_id')->constrained('categories')->cascadeOnDelete();
            $table->string('bike_no')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
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
