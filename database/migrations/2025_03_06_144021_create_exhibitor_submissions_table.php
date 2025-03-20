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
        Schema::create('exhibitor_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exhibitor_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_announcement_id')->constrained()->onDelete('cascade');
            $table->json('answers')->nullable();
            $table->string('status')->default('submitted');
            $table->boolean("can_update")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitor_submissions');
    }
};
