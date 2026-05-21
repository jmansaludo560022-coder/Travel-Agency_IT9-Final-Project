<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('cus_fn', 100);
            $table->string('cus_mn', 100)->nullable();
            $table->string('cus_ln', 100);
            $table->string('cus_email', 255)->unique();
            $table->string('phone_num', 30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
