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
            // Add a nullable datetime column to track when a user requests to update their form
            $table->timestamp('record')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitor_submissions', function (Blueprint $table) {
            // Remove the column when rolling back the migration
            $table->dropColumn('update_requested_at');
        });
    }
};
