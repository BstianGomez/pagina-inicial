<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('tipo', ['interno', 'externo'])->default('interno');

            // Datos externos (solo cuando tipo=externo)
            $table->string('nombre_externo')->nullable();
            $table->string('correo_externo')->nullable();
            $table->string('rut')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('cargo_externo')->nullable();

            // Datos del viaje
            $table->string('ceco');
            $table->string('destino');
            $table->date('fecha_viaje');
            $table->date('fecha_retorno')->nullable();
            $table->text('motivo');
            $table->boolean('alojamiento')->default(false);
            $table->boolean('traslado')->default(false);
            $table->json('gastos')->nullable(); // Gastos extras

            // Estado del flujo
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'gestionado'])
                  ->default('pendiente');

            // Aprobación
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprobado_en')->nullable();
            $table->text('comentario_aprobador')->nullable();

            // Rechazo
            $table->foreignId('rechazado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rechazado_en')->nullable();
            $table->text('comentario_rechazo')->nullable();

            // Gestión completada
            $table->foreignId('gestionado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('gestionado_en')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
