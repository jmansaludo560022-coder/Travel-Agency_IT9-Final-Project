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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('restrict');
            $table->decimal('amount_paid', 12, 2);
            $table->enum('payment_method', ['cash', 'credit_card', 'bank_transfer', 'installment']);
            $table->date('payment_date');
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->string('payment_ref_no', 100)->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
