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
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->decimal('monto_estimado', 15, 2)->nullable()->after('pv');
            $table->enum('estado', ['pendiente', 'en_aprobacion', 'aprobado', 'rechazado', 'gestionado'])->default('pendiente')->change();
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn('monto_estimado');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'gestionado'])->default('pendiente')->change();
        });
    }
};
