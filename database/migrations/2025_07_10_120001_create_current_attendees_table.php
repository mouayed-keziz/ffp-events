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
        Schema::create('current_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_announcement_id')->constrained('event_announcements')->onDelete('cascade');
            $table->foreignId('badge_id')->constrained('badges')->onDelete('cascade');
            $table->timestamp('checked_in_at');
            $table->foreignId('checked_in_by_user_id')->constrained('users')->onDelete('cascade');
            $table->string('badge_code'); // Store badge code for reference
            $table->string('badge_name'); // Store badge holder name
            $table->string('badge_email')->nullable(); // Store badge holder email
            $table->string('badge_position')->nullable(); // Store badge holder position
            $table->string('badge_company')->nullable(); // Store badge holder company
            $table->timestamps();

            // Unique constraint to prevent duplicate check-ins
            $table->unique(['event_announcement_id', 'badge_id'], 'unique_attendee_per_event');

            // Indexes for better performance
            $table->index(['event_announcement_id', 'checked_in_at']);
            $table->index('badge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_attendees');
    }
};
