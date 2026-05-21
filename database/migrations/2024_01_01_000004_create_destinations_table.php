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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('city_name', 150);
            $table->string('country', 100);
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->timestamps();
            
            // Unique constraint on city_name + country combination
            $table->unique(['city_name', 'country']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
