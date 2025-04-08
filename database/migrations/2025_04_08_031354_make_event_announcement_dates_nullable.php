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
            // Make start_date and end_date nullable if not already
            $table->dateTime('start_date')->nullable()->change();
            $table->dateTime('end_date')->nullable()->change();

            // Make visitor registration dates nullable
            $table->dateTime('visitor_registration_start_date')->nullable()->change();
            $table->dateTime('visitor_registration_end_date')->nullable()->change();

            // Make exhibitor registration dates nullable
            $table->dateTime('exhibitor_registration_start_date')->nullable()->change();
            $table->dateTime('exhibitor_registration_end_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_announcements', function (Blueprint $table) {
            // Revert back to required fields
            $table->dateTime('visitor_registration_start_date')->nullable(false)->change();
            $table->dateTime('visitor_registration_end_date')->nullable(false)->change();
            $table->dateTime('exhibitor_registration_start_date')->nullable(false)->change();
            $table->dateTime('exhibitor_registration_end_date')->nullable(false)->change();
        });
    }
};
