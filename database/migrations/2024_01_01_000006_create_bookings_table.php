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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('restrict');
            $table->foreignId('package_id')->constrained('travel_packages')->onDelete('restrict');
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->date('booking_date');
            $table->date('travel_date');
            $table->decimal('total_amount', 12, 2);
            $table->enum('booking_status', ['pending', 'confirmed', 'cancelled', 'completed', 'fully_paid'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
