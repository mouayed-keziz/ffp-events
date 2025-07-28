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
        Schema::table('event_announcements', function (Blueprint $table) {
            $table->json('extra_links')->default('[]')->after('currencies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_announcements', function (Blueprint $table) {
            $table->dropColumn('extra_links');
        });
    }
};
