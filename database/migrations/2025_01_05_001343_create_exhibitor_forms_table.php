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
        Schema::create('exhibitor_forms', function (Blueprint $table) {
            $table->id();
            $table->json("title");
            $table->json("currencies");
            $table->foreignId('event_announcement_id')->constrained()->onDelete('cascade');
            $table->json('fields');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitor_forms');
    }
};
