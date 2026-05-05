<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gestiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_id');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes')->cascadeOnDelete();
            $table->foreignId('gestionado_por')->constrained('users')->cascadeOnDelete();

            // Vuelo
            $table->string('nro_reserva')->nullable();
            $table->string('linea_aerea')->nullable();
            $table->string('nro_boleto')->nullable();

            // Hotel
            $table->string('hotel')->nullable();
            $table->string('nro_confirmacion')->nullable();
            $table->date('checkin')->nullable();
            $table->date('checkout')->nullable();

            // Montos
            $table->decimal('monto_pasaje', 12, 2)->nullable();
            $table->decimal('monto_hotel', 12, 2)->nullable();
            $table->decimal('monto_traslado', 12, 2)->nullable();

            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestiones');
    }
};
