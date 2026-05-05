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
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->string('status')->default('Borrador')->after('user_id');
        });

        // Populate user_id from reports for existing expenses
        \Illuminate\Support\Facades\DB::statement('UPDATE expenses e JOIN reports r ON e.report_id = r.id SET e.user_id = r.user_id');

        Schema::table('expenses', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('report_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'status']);
            $table->unsignedBigInteger('report_id')->nullable(false)->change();
        });
    }
};
