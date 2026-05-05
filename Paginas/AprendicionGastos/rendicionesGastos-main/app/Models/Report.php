<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public const STATUS_DRAFT = 'Borrador';
    public const STATUS_SUBMITTED = 'Enviado';
    public const STATUS_PENDING_MANAGER_APPROVAL = 'Pendiente aprobación jefatura';
    public const STATUS_OBSERVED_BY_MANAGER = 'Observada por jefatura';
    public const STATUS_RESUBMITTED_BY_REQUESTER_MANAGER = 'Subsanada por solicitante (jefatura)';
    public const STATUS_APPROVED_BY_MANAGER = 'Aprobada por jefatura';
    public const STATUS_REJECTED_BY_MANAGER = 'Rechazada por jefatura';
    public const STATUS_UNDER_REVIEW = 'En revisión';
    public const STATUS_OBSERVED_BY_REVIEWER = 'Observada por gestor';
    public const STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER = 'Subsanada por solicitante (Gestor)';
    public const STATUS_APPROVED_BY_REVIEWER = 'Aprobada por gestor';
    public const STATUS_REJECTED_BY_REVIEWER = 'Rechazada por gestor';
    public const STATUS_PENDING_PAYMENT = 'Pendiente pago';
    public const STATUS_REIMBURSED = 'Reembolsada';
    public const STATUS_CLOSED = 'Cerrada';
    public const STATUS_CANCELLED = 'Anulada';

    protected $fillable = [
        'title', 
        'user_id', 
        'status', 
        'observation', 
        'total_amount',
        'ceco_id'
    ];

    public static function pendingStatuses(): array
    {
        return [
            self::STATUS_PENDING_MANAGER_APPROVAL,
            self::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
            self::STATUS_APPROVED_BY_MANAGER,
            self::STATUS_UNDER_REVIEW,
            self::STATUS_OBSERVED_BY_REVIEWER,
            self::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
            self::STATUS_APPROVED_BY_REVIEWER,
            self::STATUS_PENDING_PAYMENT,
        ];
    }

    public function isEditableByRequester(): bool
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_OBSERVED_BY_MANAGER,
            self::STATUS_OBSERVED_BY_REVIEWER,
            self::STATUS_RESUBMITTED_BY_REQUESTER_MANAGER,
            self::STATUS_RESUBMITTED_BY_REQUESTER_REVIEWER,
        ], true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function getHasDuplicateExpensesAttribute(): bool
    {
        foreach ($this->expenses as $expense) {
            // A duplicate expense shares the exact same amount and provider_rut 
            // but is a different record in the DB
            $isDupe = \App\Models\Expense::where('amount', $expense->amount)
                ->where('provider_rut', $expense->provider_rut)
                ->where('id', '!=', $expense->id)
                ->exists();
                
            if ($isDupe) {
                return true;
            }
        }
        return false;
    }

    public function getDuplicateReportsAttribute()
    {
        $duplicateReportIds = collect();
        foreach ($this->expenses as $expense) {
            $dupes = \App\Models\Expense::where('amount', $expense->amount)
                ->where('provider_rut', $expense->provider_rut)
                ->where('id', '!=', $expense->id)
                ->pluck('report_id');
            $duplicateReportIds = $duplicateReportIds->merge($dupes);
        }
        
        return \App\Models\Report::whereIn('id', $duplicateReportIds->unique())->where('id', '!=', $this->id)->get();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
