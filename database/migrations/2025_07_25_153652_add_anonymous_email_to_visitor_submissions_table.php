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
        Schema::table('visitor_submissions', function (Blueprint $table) {
            $table->string('anonymous_email')->nullable()->after('visitor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitor_submissions', function (Blueprint $table) {
            $table->dropColumn('anonymous_email');
        });
    }
};
