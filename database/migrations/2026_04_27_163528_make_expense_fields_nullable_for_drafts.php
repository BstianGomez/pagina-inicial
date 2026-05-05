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
            $table->unsignedBigInteger('category_id')->nullable()->change();
            $table->unsignedBigInteger('ceco_id')->nullable()->change();
            $table->string('rendition_type')->nullable()->change();
            $table->string('reason')->nullable()->change();
            $table->date('expense_date')->nullable()->change();
            $table->decimal('amount', 15, 2)->nullable()->change();
            $table->string('provider_name')->nullable()->change();
            $table->string('provider_rut')->nullable()->change();
            $table->string('document_type')->nullable()->change();
            $table->string('attachment_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(false)->change();
            $table->unsignedBigInteger('ceco_id')->nullable(false)->change();
            $table->string('rendition_type')->nullable(false)->change();
            $table->string('reason')->nullable(false)->change();
            $table->date('expense_date')->nullable(false)->change();
            $table->decimal('amount', 15, 2)->nullable(false)->change();
            $table->string('provider_name')->nullable(false)->change();
            $table->string('provider_rut')->nullable(false)->change();
            $table->string('document_type')->nullable(false)->change();
            $table->string('attachment_path')->nullable(false)->change();
        });
    }
};
