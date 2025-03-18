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
        Schema::table('exhibitor_submissions', function (Blueprint $table) {
            $table->json('total_prices')->nullable()->after('post_answers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitor_submissions', function (Blueprint $table) {
            $table->dropColumn('total_prices');
        });
    }
};
