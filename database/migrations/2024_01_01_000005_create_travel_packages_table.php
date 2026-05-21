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
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('restrict');
            $table->string('package_name', 255);
            $table->string('package_type', 100);
            $table->text('description')->nullable();
            $table->longText('itinerary')->nullable();
            $table->text('inclusions')->nullable();
            $table->text('exclusions')->nullable();
            $table->decimal('package_cost', 12, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->smallInteger('slots_available')->unsigned()->default(0);
            $table->string('image', 255)->nullable();
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_packages');
    }
};
