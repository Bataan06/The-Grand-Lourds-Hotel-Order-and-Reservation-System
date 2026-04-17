<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_messages', function (Blueprint $table) {
            // Stores full conversation as JSON array
            // Each item: { role: 'guest'|'staff', message: '...', sent_at: '...' }
            $table->json('conversation')->nullable()->after('replied_at');
        });
    }

    public function down(): void
    {
        Schema::table('guest_messages', function (Blueprint $table) {
            $table->dropColumn('conversation');
        });
    }
};