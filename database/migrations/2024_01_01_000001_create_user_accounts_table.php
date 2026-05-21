<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100)->unique();
            $table->string('password');
            $table->timestamp('last_login')->nullable();
            $table->enum('role', ['admin', 'agent', 'customer']);
            // FKs to employee/customer added after those tables exist (see migration 000003b)
            $table->unsignedBigInteger('employee_id')->nullable()->unique();
            $table->unsignedBigInteger('customer_id')->nullable()->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_accounts');
    }
};
