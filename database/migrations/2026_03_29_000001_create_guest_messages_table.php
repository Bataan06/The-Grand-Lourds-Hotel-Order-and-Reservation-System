<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_messages', function (Blueprint $table) {
            $table->id();
            $table->string('guest_name');
            $table->string('guest_phone')->nullable();
            $table->string('guest_email')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->text('staff_reply')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_messages');
    }
};