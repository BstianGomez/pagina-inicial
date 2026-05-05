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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('password');
            $table->string('assigned_app')->nullable()->after('role'); // 'oc', 'viajes', 'rendicion'
            $table->boolean('has_fixed_fund')->default(false)->after('assigned_app');
            $table->decimal('fixed_fund_amount', 10, 2)->default(0)->after('has_fixed_fund');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'assigned_app', 'has_fixed_fund', 'fixed_fund_amount']);
        });
    }
};
