<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_fn', 100);
            $table->string('emp_mn', 100)->nullable();
            $table->string('emp_ln', 100);
            $table->enum('emp_gender', ['male', 'female', 'other']);
            $table->date('emp_birthdate');
            $table->text('emp_address');
            $table->string('emp_contact_num', 30);
            $table->string('emp_email', 255)->unique();
            $table->date('emp_hiredate');
            $table->decimal('commission_rate', 5, 4)->default(0.0000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
