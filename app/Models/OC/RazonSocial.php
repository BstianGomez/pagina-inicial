<?php

namespace App\Models\OC;

use Illuminate\Database\Eloquent\Model;

class RazonSocial extends Model
{
    protected $table = 'razones_sociales';

    protected $fillable = [
        'cliente_id',
        'rut',
        'cod_deudor',
        'razon_social',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
