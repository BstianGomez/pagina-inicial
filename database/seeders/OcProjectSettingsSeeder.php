<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OcProjectSettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Coordinadores de Proyecto
        $coordinadores = [
            ['nombre' => 'Bastián Morales'],
            ['nombre' => 'Juan Pérez'],
            ['nombre' => 'María García'],
            ['nombre' => 'Carlos Soto'],
            ['nombre' => 'Ana Morales'],
        ];

        foreach ($coordinadores as $item) {
            DB::table('coordinador_proyectos')->updateOrInsert(['nombre' => $item['nombre']], $item);
        }

        // Tipos de Proyecto
        $tipoProyectos = [
            ['nombre' => 'Proyecto Interno'],
            ['nombre' => 'Asesoría Externa'],
            ['nombre' => 'Mantenimiento'],
            ['nombre' => 'Desarrollo Software'],
        ];

        foreach ($tipoProyectos as $item) {
            DB::table('tipo_proyectos')->updateOrInsert(['nombre' => $item['nombre']], $item);
        }

        // Tipos de Servicio
        $tipoServicios = [
            ['nombre' => 'Consultoría'],
            ['nombre' => 'Licenciamiento'],
            ['nombre' => 'Servicios Profesionales'],
            ['nombre' => 'Soporte Técnico'],
        ];

        foreach ($tipoServicios as $item) {
            DB::table('tipo_servicios')->updateOrInsert(['nombre' => $item['nombre']], $item);
        }

        // Proveedores (Básico)
        $proveedores = [
            [
                'nombre' => 'Google Chile',
                'rut' => '76.123.456-7',
                'acreedor' => 'P-0001',
                'razon_social' => 'Google Chile Ltda.',
                'direccion' => 'Av. Providencia 1234',
                'comuna' => 'Providencia',
                'region' => 'Metropolitana',
                'telefono' => '+56223456789',
                'correo' => 'finanzas@google.cl',
            ],
            [
                'nombre' => 'Microsoft Chile',
                'rut' => '77.987.654-3',
                'acreedor' => 'P-0002',
                'razon_social' => 'Microsoft Chile S.A.',
                'direccion' => 'Av. Vitacura 5678',
                'comuna' => 'Vitacura',
                'region' => 'Metropolitana',
                'telefono' => '+56229876543',
                'correo' => 'billing@microsoft.cl',
            ],
            [
                'nombre' => 'Amazon Web Services',
                'rut' => '78.456.123-K',
                'acreedor' => 'P-0003',
                'razon_social' => 'AWS Chile SpA',
                'direccion' => 'Isidora Goyenechea 3000',
                'comuna' => 'Las Condes',
                'region' => 'Metropolitana',
                'telefono' => '+56224561234',
                'correo' => 'accounts@aws.com',
            ],
        ];

        foreach ($proveedores as $item) {
            DB::table('proveedores')->updateOrInsert(['rut' => $item['rut']], array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
