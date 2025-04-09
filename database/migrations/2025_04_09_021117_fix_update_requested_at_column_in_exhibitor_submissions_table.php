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
            // Drop the incorrectly named column if it exists
            if (Schema::hasColumn('exhibitor_submissions', 'record')) {
                $table->dropColumn('record');
            }

            // Add the correct column if it doesn't exist
            if (!Schema::hasColumn('exhibitor_submissions', 'update_requested_at')) {
                $table->timestamp('update_requested_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitor_submissions', function (Blueprint $table) {
            // In down method, we'll drop the update_requested_at column if it exists
            if (Schema::hasColumn('exhibitor_submissions', 'update_requested_at')) {
                $table->dropColumn('update_requested_at');
            }
        });
    }
};
