<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public const STATUS_DRAFT = 'Borrador';
    public const STATUS_READY = 'Listo';
    public const STATUS_ASSIGNED = 'Asignado'; // When assigned to a Report

    protected $fillable = [
        'report_id',
        'user_id',
        'status',
        'category_id',
        'ceco_id',
        'rendition_type',
        'reason',
        'description',
        'expense_date',
        'amount',
        'provider_name',
        'provider_rut',
        'document_type',
        'attachment_path',
        'comanda_path',
        'is_doc_valid',
        'is_amount_valid',
        'is_date_valid',
        'is_duplicity_valid',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ceco()
    {
        return $this->belongsTo(Ceco::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        $mandatoryFields = [
            'category_id',
            'reason',
            'expense_date',
            'amount',
            'provider_name',
            'provider_rut',
            'document_type',
            'attachment_path',
        ];

        foreach ($mandatoryFields as $field) {
            if (empty($this->{$field})) {
                return false;
            }
        }

        // Check comanda if category is food
        $categoryName = optional($this->category)->name;
        $needsComanda = \Illuminate\Support\Str::of((string) $categoryName)->ascii()->lower()->trim()->value() === 'alimentacion';
        if ($needsComanda && empty($this->comanda_path)) {
            return false;
        }

        return true;
    }
}
