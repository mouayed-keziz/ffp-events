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
        Schema::create('visitor_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_announcement_id')->constrained()->onDelete('cascade');
            $table->json('answers')->nullable();
            $table->string('status')->default('submitted');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_submissions');
    }
};
