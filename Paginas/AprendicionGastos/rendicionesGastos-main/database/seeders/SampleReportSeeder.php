<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Expense;
use App\Models\User;
use App\Models\Category;
use App\Models\Ceco;
use Illuminate\Database\Seeder;

class SampleReportSeeder extends Seeder
{
    public function run(): void
    {
        $solicitante = User::role('Solicitante')->first();
        $categories = Category::all();
        $cecos = Ceco::all();

        if (!$solicitante || $categories->isEmpty() || $cecos->isEmpty()) {
            return;
        }

        $samples = [
            [
                'title' => 'Suministros Oficina Marzo',
                'status' => Report::STATUS_DRAFT,
                'expenses' => [
                    ['amount' => 25000, 'desc' => 'Teclado y Mouse', 'cat' => 'Insumos Computacionales'],
                ]
            ],
            [
                'title' => 'Viaje a Valparaiso - Reunión Socios',
                'status' => Report::STATUS_PENDING_MANAGER_APPROVAL,
                'expenses' => [
                    ['amount' => 45000, 'desc' => 'Peajes y Bencina', 'cat' => 'Movilización'],
                    ['amount' => 15000, 'desc' => 'Platillo Almuerzo', 'cat' => 'Alimentación'],
                ]
            ],
            [
                'title' => 'Feria Tecnológica Santiago',
                'status' => Report::STATUS_APPROVED_BY_MANAGER,
                'expenses' => [
                    ['amount' => 120000, 'desc' => 'Inscripción Evento', 'cat' => 'Otros'],
                    ['amount' => 65000, 'desc' => 'Transporte Invitados', 'cat' => 'Movilización'],
                ]
            ],
            [
                'title' => 'Almuerzo Equipo Finanzas',
                'status' => Report::STATUS_OBSERVED_BY_MANAGER,
                'expenses' => [
                    ['amount' => 85000, 'desc' => 'Celebración Mensual', 'cat' => 'Alimentación'],
                ]
            ],
            [
                'title' => 'Compra Notebook Soporte',
                'status' => Report::STATUS_UNDER_REVIEW,
                'expenses' => [
                    ['amount' => 890000, 'desc' => 'Notebook HP ProBook', 'cat' => 'Insumos Computacionales'],
                ]
            ],
            [
                'title' => 'Viático Terreno Copiapó',
                'status' => Report::STATUS_PENDING_PAYMENT,
                'expenses' => [
                    ['amount' => 35000, 'desc' => 'Hospedaje 1 Noche', 'cat' => 'Hospedaje'],
                    ['amount' => 12000, 'desc' => 'Cena Terreno', 'cat' => 'Alimentación'],
                ]
            ],
            [
                'title' => 'Gastos Representación Enero',
                'status' => Report::STATUS_REIMBURSED,
                'expenses' => [
                    ['amount' => 50000, 'desc' => 'Regalo Corporativo', 'cat' => 'Otros'],
                ]
            ],
            [
                'title' => 'Suscripción Software Anual',
                'status' => Report::STATUS_REJECTED_BY_MANAGER,
                'expenses' => [
                    ['amount' => 150000, 'desc' => 'Adobe Creative Cloud', 'cat' => 'Otros'],
                ]
            ],
        ];

        foreach ($samples as $index => $data) {
            $report = Report::create([
                'title' => $data['title'],
                'user_id' => $solicitante->id,
                'status' => $data['status'],
                'total_amount' => collect($data['expenses'])->sum('amount'),
            ]);

            foreach ($data['expenses'] as $exp) {
                $category = Category::where('name', $exp['cat'])->first();
                $ceco = $cecos->random();

                Expense::create([
                    'report_id' => $report->id,
                    'category_id' => $category->id,
                    'ceco_id' => $ceco->id,
                    'rendition_type' => 'Sin fondo fijo',
                    'reason' => 'Gasto operacional',
                    'description' => $exp['desc'],
                    'expense_date' => now()->subDays(rand(1, 15))->toDateString(),
                    'amount' => $exp['amount'],
                    'provider_name' => 'Proveedor Ejemplo ' . $index,
                    'provider_rut' => '76.123.456-7',
                    'document_type' => 'Boleta',
                    'attachment_path' => 'public/sample_bill.jpg',
                    'is_doc_valid' => in_array($data['status'], [Report::STATUS_PENDING_PAYMENT, Report::STATUS_REIMBURSED]),
                    'is_amount_valid' => in_array($data['status'], [Report::STATUS_PENDING_PAYMENT, Report::STATUS_REIMBURSED]),
                    'is_date_valid' => in_array($data['status'], [Report::STATUS_PENDING_PAYMENT, Report::STATUS_REIMBURSED]),
                    'is_duplicity_valid' => in_array($data['status'], [Report::STATUS_PENDING_PAYMENT, Report::STATUS_REIMBURSED]),
                ]);
            }
        }

        // --- THE FULL JOURNEY REPORT ---
        $journey = Report::create([
            'title' => 'VIAJE CONGRESO INNOVACIÓN (CASO REAL)',
            'user_id' => $solicitante->id,
            'status' => Report::STATUS_REIMBURSED,
            'total_amount' => 150000,
        ]);

        Expense::create([
            'report_id' => $journey->id,
            'category_id' => Category::where('name', 'Movilización')->first()->id,
            'ceco_id' => $cecos->first()->id,
            'rendition_type' => 'Sin fondo fijo',
            'reason' => 'Transporte Aeropuerto',
            'description' => 'Taxi ida y vuelta',
            'expense_date' => now()->subDays(5)->toDateString(),
            'amount' => 150000,
            'provider_name' => 'RadioTaxi Transvip',
            'provider_rut' => '99.888.777-6',
            'document_type' => 'Factura',
            'attachment_path' => 'public/sample.jpg',
            'is_doc_valid' => true,
            'is_amount_valid' => true,
            'is_date_valid' => true,
            'is_duplicity_valid' => true,
        ]);

        $aprobador = User::role('Aprobador')->first();
        $gestor = User::role('Gestor')->first();

        // Timeline of the journey
        $journey->comments()->create([
            'user_id' => $solicitante->id,
            'comment' => 'Envío rendición del congreso. Adjunto factura.',
            'from_status' => Report::STATUS_DRAFT,
            'to_status' => Report::STATUS_PENDING_MANAGER_APPROVAL,
        ]);

        $journey->comments()->create([
            'user_id' => $aprobador->id,
            'comment' => 'Aprobado. El viaje fue autorizado previamente.',
            'from_status' => Report::STATUS_PENDING_MANAGER_APPROVAL,
            'to_status' => Report::STATUS_APPROVED_BY_MANAGER,
        ]);

        $journey->comments()->create([
            'user_id' => $gestor->id,
            'comment' => 'Observación: El RUT de la factura no se lee bien en la esquina superior.',
            'from_status' => Report::STATUS_APPROVED_BY_MANAGER,
            'to_status' => Report::STATUS_OBSERVED_BY_REVIEWER,
        ]);

        $journey->comments()->create([
            'user_id' => $solicitante->id,
            'comment' => 'He subido una foto con más luz donde se ve el RUT.',
            'from_status' => Report::STATUS_OBSERVED_BY_REVIEWER,
            'to_status' => Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
        ]);

        $journey->comments()->create([
            'user_id' => $gestor->id,
            'comment' => 'Validación técnica correcta. Se procede al pago.',
            'from_status' => Report::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
            'to_status' => Report::STATUS_PENDING_PAYMENT,
        ]);

        $journey->comments()->create([
            'user_id' => $gestor->id,
            'comment' => 'Transferencia realizada vía Banco de Chile.',
            'from_status' => Report::STATUS_PENDING_PAYMENT,
            'to_status' => Report::STATUS_REIMBURSED,
        ]);
    }
}
