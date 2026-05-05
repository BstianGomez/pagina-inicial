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
            $table->string('codigo', 20)->nullable()->change();
            $table->string('nombre', 200)->nullable()->change();
            $table->string('tipo', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cecos', function (Blueprint $table) {
            $table->string('codigo', 20)->nullable(false)->change();
            $table->string('nombre', 200)->nullable(false)->change();
            $table->string('tipo', 50)->nullable(false)->change();
        });
    }
};
