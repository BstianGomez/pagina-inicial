<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            DB::table('categories')->updateOrInsert(['name' => $category['name']], $category);
        }

        $cecos = [
            ['code' => '20001', 'name' => 'Finanzas General', 'tipo' => 'Interno'],
            ['code' => '20002', 'name' => 'Finanzas y Operaciones', 'tipo' => 'Interno'],
            ['code' => '20003', 'name' => 'Desarrollo Organizacional', 'tipo' => 'Interno'],
            ['code' => '20004', 'name' => 'Operaciones', 'tipo' => 'Interno'],
            ['code' => '20005', 'name' => 'Servicios Generales', 'tipo' => 'Interno'],
            ['code' => '20131', 'name' => 'TI', 'tipo' => 'Interno'],
            ['code' => '20132', 'name' => 'Cursos Internos Fundación', 'tipo' => 'Interno'],
            ['code' => '20133', 'name' => 'Sofofa Servicios', 'tipo' => 'Interno'],
            ['code' => '20134', 'name' => 'Cursos Téc y Bec Fun', 'tipo' => 'Interno'],
            ['code' => '20136', 'name' => 'Gestión de Clientes', 'tipo' => 'Interno'],
            ['code' => '20139', 'name' => 'Innovación', 'tipo' => 'Interno'],
            ['code' => '20143', 'name' => 'Gerencias', 'tipo' => 'Interno'],
            ['code' => '20147', 'name' => 'Comunicaciones', 'tipo' => 'Interno'],
            ['code' => '20148', 'name' => 'Control de Gestión', 'tipo' => 'Interno'],
            
            ['code' => '20137', 'name' => 'Sucursal Concepción', 'tipo' => 'Externo'],
            ['code' => '20141', 'name' => 'Metro', 'tipo' => 'Externo'],
            ['code' => '20144', 'name' => 'Academia Latam', 'tipo' => 'Externo'],
            ['code' => '20146', 'name' => 'Academia Leonera', 'tipo' => 'Externo'],
            ['code' => '20150', 'name' => 'Academia (Gastos Inv. / Forma)', 'tipo' => 'Externo'],
            ['code' => '20151', 'name' => 'Gestion Interna', 'tipo' => 'Externo'],
            ['code' => '20152', 'name' => 'Academia Carozzi', 'tipo' => 'Externo'],
            ['code' => '20135', 'name' => 'Academia SQM', 'tipo' => 'Externo'],
            ['code' => '20153', 'name' => 'Inversiones SQM', 'tipo' => 'Externo'],

            ['code' => '20006', 'name' => 'Formacion', 'tipo' => 'Unidad de Negocio'],
            ['code' => '20138', 'name' => 'Plataforma', 'tipo' => 'Unidad de Negocio'],
            ['code' => '20140', 'name' => 'Gestión del Conocimiento', 'tipo' => 'Unidad de Negocio'],
            ['code' => '20149', 'name' => 'Diseño', 'tipo' => 'Unidad de Negocio'],
        ];

        foreach ($cecos as $ceco) {
            DB::table('cecos')->updateOrInsert(
                ['code' => $ceco['code']], 
                [
                    'code' => $ceco['code'],
                    'name' => $ceco['name'],
                    'codigo' => $ceco['code'], // OC compatibility
                    'nombre' => $ceco['name'], // OC compatibility
                    'tipo' => $ceco['tipo'] ?? 'Unidad de Negocio',
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
