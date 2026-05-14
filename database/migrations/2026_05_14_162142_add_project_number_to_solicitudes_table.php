<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            if (!Schema::hasColumn('solicitudes', 'project_number')) {
                $table->string('project_number', 50)->nullable()->after('estado');
            }
            if (!Schema::hasColumn('solicitudes', 'project_name')) {
                $table->string('project_name', 255)->nullable()->after('project_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn(['project_number', 'project_name']);
        });
    }
};
