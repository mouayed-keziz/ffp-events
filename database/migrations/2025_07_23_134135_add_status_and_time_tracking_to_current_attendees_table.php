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
        Schema::table('current_attendees', function (Blueprint $table) {
            $table->string('status')->default('inside')->after('badge_company');
            $table->integer('total_time_spent_inside')->default(0)->comment('Total time spent inside in minutes')->after('status');
            $table->timestamp('last_check_in_at')->nullable()->after('total_time_spent_inside');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_attendees', function (Blueprint $table) {
            $table->dropColumn(['status', 'total_time_spent_inside', 'last_check_in_at']);
        });
    }
};
