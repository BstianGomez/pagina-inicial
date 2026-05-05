<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Viajes\Solicitud as ViajesSolicitud;
use App\Models\Viajes\Gestion as ViagesGestion;
use App\Models\OC\OcSubida;
use App\Models\Rendicion\Report;
use App\Models\Rendicion\Expense;
use App\Models\Rendicion\Category;
use App\Models\Rendicion\Ceco;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════════════
        // 1. DATOS OC - Solicitudes y Subidas
        // ═══════════════════════════════════════════════════════════════
        
        // OC Solicitudes
        DB::table('oc_solicitudes')->updateOrInsert(
            ['id' => 1],
            [
                'ceco' => '20001',
                'tipo_solicitud' => 'Bien',
                'tipo_documento' => 'Factura',
                'estado' => 'Solicitada',
                'rut' => '76.123.456-7',
                'proveedor' => 'Google Chile',
                'descripcion' => 'Licencias Google Workspace - 50 usuarios',
                'cantidad' => 50,
                'monto' => 1500000,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ]
        );

        DB::table('oc_solicitudes')->updateOrInsert(
            ['id' => 2],
            [
                'ceco' => '20131',
                'tipo_solicitud' => 'Servicio',
                'tipo_documento' => 'Factura',
                'estado' => 'Aceptada',
                'rut' => '77.987.654-3',
                'proveedor' => 'Microsoft Chile',
                'descripcion' => 'Soporte Técnico Azure - 12 meses',
                'cantidad' => 1,
                'monto' => 2500000,
                'created_at' => now()->subDays(10),
                'updated_at' => now()->subDays(2),
            ]
        );

        DB::table('oc_solicitudes')->updateOrInsert(
            ['id' => 3],
            [
                'ceco' => '20002',
                'tipo_solicitud' => 'Bien',
                'tipo_documento' => 'Factura',
                'estado' => 'Rechazada',
                'rut' => '78.456.123-K',
                'proveedor' => 'Amazon Web Services',
                'descripcion' => 'Instancias EC2 Premium - 100 horas/mes',
                'cantidad' => 100,
                'monto' => 850000,
                'created_at' => now()->subDays(15),
                'updated_at' => now()->subDays(3),
            ]
        );

        DB::table('oc_solicitudes')->updateOrInsert(
            ['id' => 4],
            [
                'ceco' => '20133',
                'tipo_solicitud' => 'Bien',
                'tipo_documento' => 'Boleta',
                'estado' => 'Solicitada',
                'rut' => '79.111.222-3',
                'proveedor' => 'Equipo TI Solutions',
                'descripcion' => 'Monitores 27" 4K - 10 unidades',
                'cantidad' => 10,
                'monto' => 3500000,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ]
        );

        // OC Subidas
        DB::table('oc_subidas')->updateOrInsert(
            ['id' => 1],
            [
                'numero_oc' => 'OC-2026-001',
                'ceco' => '20001',
                'estado' => 'Enviado',
                'monto' => 1500000,
                'fecha_envio' => now()->subDays(3),
                'archivo_path' => 'storage/oc/OC-2026-001.pdf',
                'archivo_nombre' => 'OC-2026-001.pdf',
                'enviado_a_email' => 'finanzas@google.cl',
                'proveedor_email' => 'finanzas@google.cl',
                'token_envio' => bin2hex(random_bytes(18)),
                'token_proveedor' => bin2hex(random_bytes(18)),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(3),
            ]
        );

        DB::table('oc_subidas')->updateOrInsert(
            ['id' => 2],
            [
                'numero_oc' => 'OC-2026-002',
                'ceco' => '20131',
                'estado' => 'Confirmado',
                'monto' => 2500000,
                'fecha_envio' => now()->subDays(8),
                'archivo_path' => 'storage/oc/OC-2026-002.pdf',
                'archivo_nombre' => 'OC-2026-002.pdf',
                'enviado_a_email' => 'billing@microsoft.cl',
                'proveedor_email' => 'billing@microsoft.cl',
                'token_envio' => bin2hex(random_bytes(18)),
                'token_proveedor' => bin2hex(random_bytes(18)),
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(1),
            ]
        );

        // ═══════════════════════════════════════════════════════════════
        // 2. DATOS VIAJES - Solicitudes de Viaje y Gestiones
        // ═══════════════════════════════════════════════════════════════

        $usuarioViajes = User::where('email', 'viajes@example.com')->first() 
                          ?? User::where('email', 'multi1@example.com')->first();

        if ($usuarioViajes) {
            // Solicitud de Viaje Aprobada
            $viaje1 = ViajesSolicitud::updateOrCreate(
                ['id' => 1],
                [
                    'user_id' => $usuarioViajes->id,
                    'tipo' => 'interno',
                    'ceco' => '20001',
                    'destino' => 'Santiago - Valparaíso',
                    'fecha_viaje' => now()->addDays(7)->toDateString(),
                    'fecha_retorno' => now()->addDays(8)->toDateString(),
                    'motivo' => 'Reunión con clientes estratégicos y presentación de nuevos servicios',
                    'alojamiento' => true,
                    'traslado' => true,
                    'estado' => 'aprobado',
                    'aprobado_por' => $usuarioViajes->id,
                    'aprobado_en' => now()->subDays(3),
                    'monto_estimado' => 850000,
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(3),
                ]
            );

            // Gestión asociada
            ViagesGestion::updateOrCreate(
                ['id' => 1],
                [
                    'solicitud_id' => $viaje1->id,
                    'gestionado_por' => $usuarioViajes->id,
                    'nro_reserva' => 'SKW-789456',
                    'linea_aerea' => 'LATAM',
                    'nro_boleto' => '000-1234567890',
                    'hotel' => 'Hotel del Pacífico 5*',
                    'nro_confirmacion' => 'HDP-2026-05-12',
                    'checkin' => now()->addDays(7)->toDateString(),
                    'checkout' => now()->addDays(8)->toDateString(),
                    'monto_pasaje' => 450000,
                    'monto_hotel' => 250000,
                    'monto_traslado' => 150000,
                    'notas' => 'Vuelo directo a las 08:30. Hotel con vista al mar confirmado.',
                    'created_at' => now()->subDays(2),
                    'updated_at' => now()->subDays(2),
                ]
            );

            // Solicitud de Viaje Pendiente
            ViajesSolicitud::updateOrCreate(
                ['id' => 2],
                [
                    'user_id' => $usuarioViajes->id,
                    'tipo' => 'interno',
                    'ceco' => '20131',
                    'destino' => 'Santiago - Concepción',
                    'fecha_viaje' => now()->addDays(14)->toDateString(),
                    'fecha_retorno' => now()->addDays(15)->toDateString(),
                    'motivo' => 'Auditoría técnica de infraestructura en sucursal',
                    'alojamiento' => false,
                    'traslado' => true,
                    'estado' => 'pendiente',
                    'monto_estimado' => 320000,
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()->subDays(1),
                ]
            );

            // Solicitud de Viaje Rechazada
            ViajesSolicitud::updateOrCreate(
                ['id' => 3],
                [
                    'user_id' => $usuarioViajes->id,
                    'tipo' => 'externo',
                    'nombre_externo' => 'Roberto Silva',
                    'correo_externo' => 'r.silva@cliente.com',
                    'rut' => '15.555.666-7',
                    'cargo_externo' => 'Gerente Operacional',
                    'ceco' => '20147',
                    'destino' => 'Santiago - Miami',
                    'fecha_viaje' => now()->addDays(21)->toDateString(),
                    'fecha_retorno' => now()->addDays(24)->toDateString(),
                    'motivo' => 'Conferencia internacional de tecnología',
                    'alojamiento' => true,
                    'traslado' => true,
                    'estado' => 'rechazado',
                    'rechazado_por' => $usuarioViajes->id,
                    'rechazado_en' => now()->subDays(4),
                    'comentario_rechazo' => 'Presupuesto excedido para viajes internacionales.',
                    'monto_estimado' => 3500000,
                    'created_at' => now()->subDays(10),
                    'updated_at' => now()->subDays(4),
                ]
            );
        }

        // ═══════════════════════════════════════════════════════════════
        // 3. DATOS RENDICIÓN - Informes de Gastos (Reports y Expenses)
        // ═══════════════════════════════════════════════════════════════

        $usuarioRendicion = User::where('email', 'rendicion@example.com')->first() 
                           ?? User::where('email', 'multi2@example.com')->first();

        if ($usuarioRendicion) {
            // Asegurar que existen categorías y cecos
            $categories = Category::all();
            if ($categories->isEmpty()) {
                $this->call(CategoryCecoSeeder::class);
                $categories = Category::all();
            }

            $cecos = Ceco::all();

            if (!$categories->isEmpty() && !$cecos->isEmpty()) {
                // Informe Borrador
                $report1 = Report::updateOrCreate(
                    ['id' => 1],
                    [
                        'user_id' => $usuarioRendicion->id,
                        'title' => 'Viaje Conferencia Tecnológica - Mayo 2026',
                        'status' => 'Borrador',
                        'total_amount' => 0,
                        'created_at' => now()->subDays(2),
                        'updated_at' => now()->subDays(2),
                    ]
                );

                // Informe Aprobado
                $report2 = Report::updateOrCreate(
                    ['id' => 2],
                    [
                        'user_id' => $usuarioRendicion->id,
                        'title' => 'Gastos Operacionales Marzo',
                        'status' => 'Aprobada por jefatura',
                        'total_amount' => 185000,
                        'ceco_id' => $cecos->first()->id ?? null,
                        'created_at' => now()->subDays(15),
                        'updated_at' => now()->subDays(5),
                    ]
                );

                // Gastos del Informe Aprobado
                $cat_movilizacion = $categories->where('name', 'Movilización')->first();
                $cat_alimentacion = $categories->where('name', 'Alimentación')->first();
                $cat_hospedaje = $categories->where('name', 'Hospedaje')->first();

                if ($cat_movilizacion) {
                    Expense::updateOrCreate(
                        ['id' => 1],
                        [
                            'report_id' => $report2->id,
                            'user_id' => $usuarioRendicion->id,
                            'category_id' => $cat_movilizacion->id,
                            'ceco_id' => $cecos->first()->id ?? null,
                            'rendition_type' => 'Sin fondo fijo',
                            'reason' => 'Transporte',
                            'description' => 'Taxi aeropuerto ida y vuelta',
                            'expense_date' => now()->subDays(10)->toDateString(),
                            'amount' => 85000,
                            'provider_name' => 'RadioTaxi Transvip',
                            'provider_rut' => '99.888.777-6',
                            'document_type' => 'Boleta',
                            'attachment_path' => 'storage/expenses/taxis.jpg',
                            'is_doc_valid' => true,
                            'is_amount_valid' => true,
                            'is_date_valid' => true,
                            'is_duplicity_valid' => true,
                            'created_at' => now()->subDays(12),
                            'updated_at' => now()->subDays(5),
                        ]
                    );
                }

                if ($cat_alimentacion) {
                    Expense::updateOrCreate(
                        ['id' => 2],
                        [
                            'report_id' => $report2->id,
                            'user_id' => $usuarioRendicion->id,
                            'category_id' => $cat_alimentacion->id,
                            'ceco_id' => $cecos->first()->id ?? null,
                            'rendition_type' => 'Sin fondo fijo',
                            'reason' => 'Almuerzo reunión cliente',
                            'description' => 'Almuerzo equipo - Restaurante Centro',
                            'expense_date' => now()->subDays(9)->toDateString(),
                            'amount' => 100000,
                            'provider_name' => 'Restaurant Centro SpA',
                            'provider_rut' => '88.777.666-5',
                            'document_type' => 'Factura',
                            'attachment_path' => 'storage/expenses/almuerzo.jpg',
                            'is_doc_valid' => true,
                            'is_amount_valid' => true,
                            'is_date_valid' => true,
                            'is_duplicity_valid' => true,
                            'created_at' => now()->subDays(11),
                            'updated_at' => now()->subDays(5),
                        ]
                    );
                }

                // Informe Pendiente Pago
                $report3 = Report::updateOrCreate(
                    ['id' => 3],
                    [
                        'user_id' => $usuarioRendicion->id,
                        'title' => 'Suministros Oficina - Abril 2026',
                        'status' => 'Pendiente pago',
                        'total_amount' => 425000,
                        'ceco_id' => $cecos->first()->id ?? null,
                        'created_at' => now()->subDays(20),
                        'updated_at' => now()->subDays(8),
                    ]
                );

                $cat_insumos = $categories->where('name', 'Insumos Computacionales')->first();
                if ($cat_insumos) {
                    Expense::updateOrCreate(
                        ['id' => 3],
                        [
                            'report_id' => $report3->id,
                            'user_id' => $usuarioRendicion->id,
                            'category_id' => $cat_insumos->id,
                            'ceco_id' => $cecos->first()->id ?? null,
                            'rendition_type' => 'Sin fondo fijo',
                            'reason' => 'Compra suministros TI',
                            'description' => 'Teclados inalámbricos - 5 unidades',
                            'expense_date' => now()->subDays(18)->toDateString(),
                            'amount' => 425000,
                            'provider_name' => 'Tech Store Chile',
                            'provider_rut' => '77.555.444-3',
                            'document_type' => 'Factura',
                            'attachment_path' => 'storage/expenses/teclados.jpg',
                            'is_doc_valid' => true,
                            'is_amount_valid' => true,
                            'is_date_valid' => true,
                            'is_duplicity_valid' => true,
                            'created_at' => now()->subDays(19),
                            'updated_at' => now()->subDays(8),
                        ]
                    );
                }

                // Informe Reembolsado
                $report4 = Report::updateOrCreate(
                    ['id' => 4],
                    [
                        'user_id' => $usuarioRendicion->id,
                        'title' => 'Reembolso Viaje Febrero',
                        'status' => 'Reembolsada',
                        'total_amount' => 650000,
                        'ceco_id' => $cecos->first()->id ?? null,
                        'created_at' => now()->subDays(30),
                        'updated_at' => now()->subDays(10),
                    ]
                );

                if ($cat_hospedaje) {
                    Expense::updateOrCreate(
                        ['id' => 4],
                        [
                            'report_id' => $report4->id,
                            'user_id' => $usuarioRendicion->id,
                            'category_id' => $cat_hospedaje->id,
                            'ceco_id' => $cecos->first()->id ?? null,
                            'rendition_type' => 'Sin fondo fijo',
                            'reason' => 'Hospedaje Valparaíso',
                            'description' => 'Hotel Pacífico - 2 noches',
                            'expense_date' => now()->subDays(28)->toDateString(),
                            'amount' => 650000,
                            'provider_name' => 'Hotel Pacífico 5*',
                            'provider_rut' => '66.444.333-2',
                            'document_type' => 'Factura',
                            'attachment_path' => 'storage/expenses/hotel.jpg',
                            'is_doc_valid' => true,
                            'is_amount_valid' => true,
                            'is_date_valid' => true,
                            'is_duplicity_valid' => true,
                            'created_at' => now()->subDays(29),
                            'updated_at' => now()->subDays(10),
                        ]
                    );
                }
            }
        }

        $this->command->info('Datos de prueba creados exitosamente en OC, Viajes y Rendición.');
    }
}
