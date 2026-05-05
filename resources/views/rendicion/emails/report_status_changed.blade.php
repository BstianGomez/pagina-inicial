<mail::message>
# Actualización de su Rendición de Gastos

Estimado/a {{ $report->user->name }},

El estado de su informe de rendición **"{{ $report->title }}"** ha sido actualizado.

**Nuevo Estado:** {{ $report->status }}

@if($comment)
**Comentario:**
{{ $comment->comment }}
@endif

**Monto Total:** ${{ number_format($report->total_amount, 0, ',', '.') }}
@if($report->status === 'Pendiente aprobación jefatura' || $report->status === 'Subsanada por solicitante (jefatura)')
**Acción Requerida:** Por favor revise esta rendición y tome una acción.
@endif

@if($approveUrl && $rejectUrl)
<mail::table>
| | |
| :--- | :--- |
| <mail::button :url="$approveUrl" color="success">APROBAR</x-mail::button> | <mail::button :url="$rejectUrl" color="error">RECHAZAR</x-mail::button> |
</x-mail::table>
@else
<mail::button :url="config('app.url') . '/informes/' . $report->id">
Ver Detalles de la Rendición
</x-mail::button>
@endif

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
