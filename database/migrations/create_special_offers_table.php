<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('badge')->default('Special');
            $table->string('emoji')->default('🎉');
            $table->string('gradient')->default('linear-gradient(135deg,#7c3aed,#a21caf)');
            $table->text('description');
            $table->string('highlight');
            $table->string('highlight_color')->default('#e65100');
            $table->string('highlight_bg')->default('#fff3e0');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('special_offers');
    }
};