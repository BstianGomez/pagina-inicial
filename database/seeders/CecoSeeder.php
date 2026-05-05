<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ceco;
use Illuminate\Support\Facades\DB;

class CecoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate existing CECOs to avoid duplicates
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Ceco::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $cecos = [
            ['code' => '20001', 'name' => 'Finanzas General'],
            ['code' => '20002', 'name' => 'Finanzas y Operaciones'],
            ['code' => '20003', 'name' => 'Desarrollo Organizacional'],
            ['code' => '20004', 'name' => 'Operaciones'],
            ['code' => '20005', 'name' => 'Servicios Generales'],
            ['code' => '20006', 'name' => 'Formacion'],
            ['code' => '20131', 'name' => 'TI'],
            ['code' => '20132', 'name' => 'Cursos Internos Fundación'],
            ['code' => '20133', 'name' => 'Sofofa Servicios'],
            ['code' => '20134', 'name' => 'Cursos Téc y Bec Fun'],
            ['code' => '20136', 'name' => 'Gestión de Clientes'],
            ['code' => '20137', 'name' => 'Sucursal Concepción'],
            ['code' => '20138', 'name' => 'Plataforma'],
            ['code' => '20139', 'name' => 'Innovación'],
            ['code' => '20140', 'name' => 'Gestión del Conocimiento'],
            ['code' => '20141', 'name' => 'Metro'],
            ['code' => '20143', 'name' => 'Gerencias'],
            ['code' => '20144', 'name' => 'Academia Latam'],
            ['code' => '20150', 'name' => 'Academia (Gastos Inv. / Forma)'],
            ['code' => '20146', 'name' => 'Academia Leonera'],
            ['code' => '20147', 'name' => 'Comunicaciones'],
            ['code' => '20148', 'name' => 'Control de Gestión'],
            ['code' => '20149', 'name' => 'Diseño'],
            ['code' => '20151', 'name' => 'Gestion Interna'],
            ['code' => '20152', 'name' => 'Academia Carozzi'],
            ['code' => '20135', 'name' => 'Academia SQM'],
            ['code' => '20153', 'name' => 'Inversiones SQM'],
        ];

        foreach ($cecos as $data) {
            Ceco::create($data);
        }
    }
}
