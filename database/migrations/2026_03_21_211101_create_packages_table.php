<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->string('pax_range'); // e.g. "50-90", "100", "150", "200-300"
            $table->integer('pax_min');
            $table->integer('pax_max');
            $table->text('amenities'); // JSON list of inclusions
            $table->decimal('price_per_pax_set_a', 10, 2)->default(0);
            $table->decimal('price_per_pax_set_b', 10, 2)->default(0);
            $table->decimal('price_per_pax_set_c', 10, 2)->default(0);
            $table->decimal('price_per_pax_set_d', 10, 2)->default(0);
            $table->text('menu_set_a')->nullable(); // JSON
            $table->text('menu_set_b')->nullable(); // JSON
            $table->text('menu_set_c')->nullable(); // JSON
            $table->text('menu_set_d')->nullable(); // JSON
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};