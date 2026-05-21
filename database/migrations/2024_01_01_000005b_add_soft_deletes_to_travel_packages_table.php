<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->softDeletes(); // adds deleted_at TIMESTAMP NULLABLE
        });
    }

    public function down(): void
    {
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
