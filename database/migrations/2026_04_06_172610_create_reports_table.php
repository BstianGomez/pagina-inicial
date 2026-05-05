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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', [
                'Borrador', 'Enviado', 'Pendiente aprobación jefatura', 
                'Observada por jefatura', 'Subsanada por solicitante (jefatura)', 
                'Aprobada por jefatura', 'Rechazada por jefatura', 'En revisión', 
                'Observada por gestor', 'Subsanada por solicitante (Gestor)', 
                'Aprobada por gestor', 'Rechazada por gestor', 'Pendiente pago', 
                'Reembolsada', 'Cerrada', 'Anulada'
            ])->default('Borrador');
            $table->text('observation')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
