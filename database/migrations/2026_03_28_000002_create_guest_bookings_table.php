<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no')->unique();
            $table->string('guest_name');
            $table->string('guest_phone');
            $table->string('guest_email');
            $table->foreignId('event_id')->constrained('events');
            $table->foreignId('package_id')->constrained('packages');
            $table->foreignId('venue_id')->constrained('venues');
            $table->string('celebrant_name');
            $table->integer('pax_count');
            $table->date('event_date');
            $table->time('event_time_start')->nullable();
            $table->string('food_set');
            $table->decimal('price_per_pax', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->text('additional_charges')->nullable();
            $table->decimal('additional_total', 10, 2)->default(0);
            $table->text('special_requests')->nullable();
            $table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_bookings');
    }
};