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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained();
            $table->foreignId('ceco_id')->constrained();
            $table->string('rendition_type'); // 자동, fondo fijo, etc.
            $table->string('reason');
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->decimal('amount', 15, 2);
            $table->string('provider_name');
            $table->string('provider_rut');
            $table->string('document_type'); // boleta, factura, etc.
            $table->string('attachment_path');
            $table->string('comanda_path')->nullable(); // Required for food
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
