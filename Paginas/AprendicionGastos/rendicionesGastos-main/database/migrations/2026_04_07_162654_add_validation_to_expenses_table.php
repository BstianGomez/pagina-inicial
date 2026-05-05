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
            $table->boolean('is_doc_valid')->default(false);
            $table->boolean('is_amount_valid')->default(false);
            $table->boolean('is_date_valid')->default(false);
            $table->boolean('is_duplicity_valid')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['is_doc_valid', 'is_amount_valid', 'is_date_valid', 'is_duplicity_valid']);
        });
    }
};
