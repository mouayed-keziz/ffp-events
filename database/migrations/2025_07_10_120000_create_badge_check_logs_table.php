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
        Schema::create('badge_check_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_announcement_id')->constrained('event_announcements')->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('badges')->onDelete('cascade');
            $table->foreignId('checked_by_user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // 'check_in' or 'check_out'
            $table->timestamp('action_time');
            $table->text('note')->nullable();
            $table->string('badge_code'); // Store badge code for reference
            $table->string('badge_name'); // Store badge holder name
            $table->string('badge_email')->nullable(); // Store badge holder email
            $table->string('badge_position')->nullable(); // Store badge holder position
            $table->string('badge_company')->nullable(); // Store badge holder company
            $table->timestamps();

            // Indexes for better performance
            $table->index(['event_announcement_id', 'action_time']);
            $table->index(['badge_id', 'action']);
            $table->index('action_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_check_logs');
    }
};
