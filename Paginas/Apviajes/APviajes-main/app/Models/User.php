<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'rol'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function solicitudes()
    {
        return $this->hasMany(\App\Models\Solicitud::class);
    }

    public function solicitudesAprobadas()
    {
        return $this->hasMany(\App\Models\Solicitud::class, 'aprobado_por');
    }

    public function solicitudesGestionadas()
    {
        return $this->hasMany(\App\Models\Solicitud::class, 'gestionado_por');
    }
}
