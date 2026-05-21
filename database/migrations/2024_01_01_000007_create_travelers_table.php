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
        Schema::create('travelers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('trav_fn', 100);
            $table->string('trav_mn', 100)->nullable();
            $table->string('trav_ln', 100);
            $table->date('trav_birthdate');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('passport_no', 50)->nullable();
            $table->string('nationality', 100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travelers');
    }
};
