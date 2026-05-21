<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_accounts', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')->on('employees')
                ->onDelete('set null');

            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('user_accounts', function (Blueprint $table) {
            $table->dropForeign(['employee_id']);
            $table->dropForeign(['customer_id']);
        });
    }
};
