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
        Schema::table('cecos', function (Blueprint $table) {
            if (!Schema::hasColumn('cecos', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('cecos', 'code')) {
                $table->string('code')->nullable()->unique();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cecos', function (Blueprint $table) {
            $table->dropColumn(['name', 'code']);
        });
    }
};
