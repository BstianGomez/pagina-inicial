<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitud extends Model
{
    use HasFactory;
    protected $table = 'solicitudes';

    protected $fillable = [
        'user_id', 'tipo',
        'nombre_externo', 'correo_externo', 'rut', 'fecha_nacimiento', 'cargo_externo',
        'ceco', 'destino', 'fecha_viaje', 'fecha_retorno', 'motivo', 'alojamiento', 'traslado', 'gastos', 'pv', 'monto_estimado',
        'estado',
        'aprobado_por', 'aprobado_en', 'comentario_aprobador',
        'rechazado_por', 'rechazado_en', 'comentario_rechazo',
        'gestionado_por', 'gestionado_en',
    ];

    protected $casts = [
        'gastos'          => 'array',
        'pv'              => 'array',
        'alojamiento'     => 'boolean',
        'traslado'        => 'boolean',
        'fecha_viaje'     => 'date',
        'fecha_retorno'   => 'date',
        'fecha_nacimiento'=> 'date',
        'aprobado_en'     => 'datetime',
        'rechazado_en'    => 'datetime',
        'gestionado_en'   => 'datetime',
    ];

    // ── Relaciones ──────────────────────────────────────────
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }

    public function rechazador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rechazado_por');
    }

    public function gestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'gestionado_por');
    }

    public function gestion(): HasOne
    {
        return $this->hasOne(Gestion::class);
    }

    public function archivos(): HasMany
    {
        return $this->hasMany(ArchivoSolicitud::class);
    }

    // ── Scopes ───────────────────────────────────────────────
    public function scopePendientes($q)       { return $q->where('estado', 'pendiente'); }
    public function scopeEnAprobacion($q)     { return $q->where('estado', 'en_aprobacion'); }
    public function scopeAprobadas($q)        { return $q->where('estado', 'aprobado'); }
    public function scopeParaGestor($q)       { return $q->whereIn('estado', ['pendiente', 'aprobado']); }
    public function scopeGestionadas($q)      { return $q->where('estado', 'gestionado'); }

    // ── Helpers ──────────────────────────────────────────────
    public function esPendiente(): bool     { return $this->estado === 'pendiente'; }
    public function esEnAprobacion(): bool  { return $this->estado === 'en_aprobacion'; }
    public function esAprobada(): bool      { return $this->estado === 'aprobado'; }
    public function esRechazada(): bool     { return $this->estado === 'rechazado'; }
    public function esGestionada(): bool    { return $this->estado === 'gestionado'; }

    public function badgeColor(): string
    {
        return match($this->estado) {
            'pendiente'     => 'badge-pendiente',
            'en_aprobacion' => 'badge-info', // Necesitaremos definir esto en el CSS o usar una clase existente
            'aprobado'      => 'badge-aprobado',
            'rechazado'     => 'badge-rechazado',
            'gestionado'    => 'badge-gestionado',
            default         => '',
        };
    }
}
