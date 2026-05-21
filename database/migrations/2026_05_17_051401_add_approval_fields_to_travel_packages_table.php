<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('travel_packages', function (Blueprint $table) {
            // approval_status: null = admin-created (no approval needed)
            // pending_approval = agent submitted, awaiting admin review
            // approved = admin approved, visible allowed
            // rejected = admin rejected
            $table->string('approval_status')->nullable()->after('is_visible');

            // Stores pending edit data as JSON until admin approves
            $table->json('pending_changes')->nullable()->after('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('travel_packages', function (Blueprint $table) {
            $table->dropColumn(['approval_status', 'pending_changes']);
        });
    }
};
