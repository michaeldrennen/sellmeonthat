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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('want_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_profile_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->text('message')->nullable();
            $table->string('status')->default('pending'); // e.g., pending, accepted, rejected
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
