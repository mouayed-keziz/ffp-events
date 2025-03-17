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
        Schema::create('exhibitor_payment_slices', function (Blueprint $table) {
            $table->id();
            $table->foreignId("exhibitor_submission_id")->constrained()->cascadeOnDelete();
            $table->unsignedInteger("sort")->default(0);

            $table->unsignedInteger("price");
            $table->enum("currency", ["DZD", "EUR", "USD"])->default("DZD");
            $table->enum("status", ["not_payed", "proof_attached", "valid"])->default("not_payed");

            $table->timestamp("due_to")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitor_payment_slices');
    }
};
