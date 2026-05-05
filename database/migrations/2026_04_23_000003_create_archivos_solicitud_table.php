<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('archivos_solicitud', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_id');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->cascadeOnDelete();
            $table->unsignedBigInteger('gestion_id')->nullable();
            $table->foreign('gestion_id')->references('id')->on('gestiones')->nullOnDelete();
            $table->string('nombre_original');
            $table->string('ruta'); // ruta en storage
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('tamano')->nullable(); // bytes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('archivos_solicitud');
    }
};
