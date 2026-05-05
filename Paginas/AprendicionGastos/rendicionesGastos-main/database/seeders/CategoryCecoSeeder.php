<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ceco;
use Illuminate\Database\Seeder;

class CategoryCecoSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Insumos Computacionales', 'requires_comanda' => false],
            ['name' => 'Alimentación', 'requires_comanda' => true],
            ['name' => 'Movilización', 'requires_comanda' => false],
            ['name' => 'Hospedaje', 'requires_comanda' => false],
            ['name' => 'Papelería', 'requires_comanda' => false],
            ['name' => 'Otros', 'requires_comanda' => false],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }

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
            ['code' => '20135', 'name' => 'Academia SQM'],
            ['code' => '20136', 'name' => 'Gestión de Clientes'],
            ['code' => '20137', 'name' => 'Sucursal Concepción'],
            ['code' => '20138', 'name' => 'Plataforma'],
            ['code' => '20139', 'name' => 'Innovación'],
            ['code' => '20140', 'name' => 'Gestión del Conocimiento'],
            ['code' => '20141', 'name' => 'Metro'],
            ['code' => '20143', 'name' => 'Gerencias'],
            ['code' => '20144', 'name' => 'Academia Latam'],
            ['code' => '20146', 'name' => 'Academia Leonera'],
            ['code' => '20147', 'name' => 'Comunicaciones'],
            ['code' => '20148', 'name' => 'Control de Gestión'],
            ['code' => '20149', 'name' => 'Diseño'],
            ['code' => '20150', 'name' => 'Academia (Gastos Inv. / Forma)'],
            ['code' => '20151', 'name' => 'Gestion Interna'],
            ['code' => '20152', 'name' => 'Academia Carozzi'],
            ['code' => '20153', 'name' => 'Inversiones SQM'],
        ];

        foreach ($cecos as $ceco) {
            Ceco::updateOrCreate(['code' => $ceco['code']], $ceco);
        }
    }
}
