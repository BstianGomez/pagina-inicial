<?php

namespace App\Models\Viajes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gestion extends Model
{
    protected $table = 'gestiones';

    protected $fillable = [
        'solicitud_id', 'gestionado_por',
        'nro_reserva', 'linea_aerea', 'nro_boleto',
        'hotel', 'nro_confirmacion', 'checkin', 'checkout',
        'monto_pasaje', 'monto_hotel', 'monto_traslado',
        'notas',
    ];

    protected $casts = [
        'checkin'  => 'date',
        'checkout' => 'date',
        'monto_pasaje'   => 'decimal:2',
        'monto_hotel'    => 'decimal:2',
        'monto_traslado' => 'decimal:2',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function gestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gestionado_por');
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(ArchivoSolicitud::class, 'gestion_id');
    }

    public function montoTotal(): float
    {
        return (float)($this->monto_pasaje ?? 0)
             + (float)($this->monto_hotel ?? 0)
             + (float)($this->monto_traslado ?? 0);
    }
}
