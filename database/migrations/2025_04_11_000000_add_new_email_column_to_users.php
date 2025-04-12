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
        Schema::table('exhibitors', function (Blueprint $table) {
            $table->string('new_email')->nullable();
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->string('new_email')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('new_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exhibitors', function (Blueprint $table) {
            $table->dropColumn('new_email');
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('new_email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('new_email');
        });
    }
};
