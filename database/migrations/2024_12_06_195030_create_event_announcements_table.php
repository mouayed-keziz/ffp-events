<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_announcements', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('terms'); // new column for translatable terms (html)
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('location')->nullable();
            $table->dateTime('visitor_registration_start_date');
            $table->dateTime('visitor_registration_end_date');
            $table->dateTime('exhibitor_registration_start_date');
            $table->dateTime('exhibitor_registration_end_date');
            $table->string('website_url')->nullable();
            $table->json('contact'); // {name, email, phone_number}
            $table->json('content')->nullable(); // translatable html content
            $table->json('currencies'); // json array e.g. ['eur', 'usd']
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_announcements');
    }
};
