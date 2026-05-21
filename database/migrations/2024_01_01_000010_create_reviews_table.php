<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('booking_id')->unique()->constrained('bookings')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->text('review_text');
            $table->text('review_reply')->nullable();
            $table->timestamps();
        });
        
        // Add check constraint for rating between 1 and 5 (SQLite compatible)
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('CREATE TRIGGER check_rating_insert BEFORE INSERT ON reviews BEGIN SELECT RAISE(ABORT, \'Rating must be between 1 and 5\') WHERE NEW.rating < 1 OR NEW.rating > 5; END;');
            DB::statement('CREATE TRIGGER check_rating_update BEFORE UPDATE ON reviews BEGIN SELECT RAISE(ABORT, \'Rating must be between 1 and 5\') WHERE NEW.rating < 1 OR NEW.rating > 5; END;');
        } else {
            DB::statement('ALTER TABLE reviews ADD CONSTRAINT chk_rating CHECK (rating >= 1 AND rating <= 5)');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
