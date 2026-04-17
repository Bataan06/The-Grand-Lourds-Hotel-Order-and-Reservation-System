<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('special_offers', function (Blueprint $table) {
            if (!Schema::hasColumn('special_offers', 'image')) {
                $table->string('image')->nullable()->after('emoji');
            }
        });
    }

    public function down(): void
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};