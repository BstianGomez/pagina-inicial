<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Rendicion\Report;
use App\Models\Rendicion\Expense;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'rol',
        'assigned_app', // legacy, usar assigned_apps preferentemente
        'assigned_apps',
        'has_fixed_fund',
        'fixed_fund_amount',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'has_fixed_fund' => 'boolean',
        'fixed_fund_amount' => 'decimal:2',
        'assigned_apps' => 'array',
    ];

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

    /**
     * Normalize role aliases so 'Superadmin', 'super_admin', 'superadmin',
     * 'Admin', 'admin' etc. are all treated as equivalent variants.
     */
    private function normalizeRole(string $role): string
    {
        $aliases = [
            'super_admin'  => 'superadmin',
            'superadmin'   => 'superadmin',
            'super admin'  => 'superadmin',
            'admin'        => 'admin',
            'gestor'       => 'gestor',
            'aprobador'    => 'aprobador',
            'usuario'      => 'usuario',
            'cliente'      => 'cliente',
        ];

        $key = strtolower(str_replace(' ', '_', $role));
        $key = str_replace('-', '_', $key);

        return $aliases[$key] ?? strtolower($role);
    }

    public function hasRole($role)
    {
        $normalizedCheck  = $this->normalizeRole($role);
        $normalizedStored = $this->normalizeRole($this->role ?? '');
        $normalizedStoredRol = $this->normalizeRole($this->rol ?? '');

        return $normalizedStored === $normalizedCheck
            || $normalizedStoredRol === $normalizedCheck;
    }

    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
            return false;
        }

        return $this->hasRole($roles);
    }

    public function isAdmin()
    {
        return $this->hasAnyRole(['admin', 'super_admin', 'superadmin']);
    }

    public function isSuperAdmin()
    {
        return $this->hasAnyRole(['super_admin', 'superadmin']);
    }

    public function isCliente()
    {
        return $this->hasRole('cliente');
    }

    public function hasApp($app)
    {
        return $this->assigned_app === $app;
    }
}
