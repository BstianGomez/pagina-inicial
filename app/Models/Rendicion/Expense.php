<?php

namespace App\Models\Rendicion;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'project_number',
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
            $val = $this->getAttribute($field);
            
            if ($field === 'amount') {
                if (is_null($val) || (float)$val <= 0) {
                    return false;
                }
                continue;
            }

            if (is_null($val) || (string)$val === '') {
                return false;
            }
        }

        // Check comanda if category is food
        if (!$this->relationLoaded('category')) {
            $this->load('category');
        }

        $categoryName = $this->category?->name;
        $needsComanda = \Illuminate\Support\Str::of((string) $categoryName)->ascii()->lower()->trim()->value() === 'alimentacion';
        if ($needsComanda && empty($this->comanda_path)) {
            return false;
        }

        return true;
    }
}
