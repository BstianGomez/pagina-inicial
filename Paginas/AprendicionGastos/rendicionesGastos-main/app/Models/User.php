<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Report;
use App\Models\Expense;

#[Fillable(['name', 'email', 'password', 'has_fixed_fund', 'fixed_fund_amount'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_fixed_fund' => 'boolean',
            'fixed_fund_amount' => 'decimal:2',
        ];
    }

    public function getUsedFixedFundAttribute(): float
    {
        if (!$this->has_fixed_fund) {
            return 0;
        }

        $excludedStatuses = [
            Report::STATUS_REJECTED_BY_MANAGER,
            Report::STATUS_REJECTED_BY_REVIEWER,
            Report::STATUS_REIMBURSED,
            Report::STATUS_CLOSED,
            Report::STATUS_CANCELLED,
        ];

        return (float) Expense::query()
            ->where('rendition_type', 'Con fondo fijo')
            ->whereHas('report', function ($query) use ($excludedStatuses) {
                $query->where('user_id', $this->id)
                      ->whereNotIn('status', $excludedStatuses);
            })
            ->sum('amount');
    }

    public function getRemainingFixedFundAttribute(): float
    {
        return (float)$this->fixed_fund_amount - $this->used_fixed_fund;
    }
}
