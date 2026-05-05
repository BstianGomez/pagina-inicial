<?php

namespace App\Models\Viajes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ArchivoSolicitud extends Model
{
    protected $table = 'archivos_solicitud';

    protected $fillable = [
        'solicitud_id', 'gestion_id',
        'nombre_original', 'ruta', 'mime_type', 'tamano',
    ];

    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function gestion(): BelongsTo
    {
        return $this->belongsTo(Gestion::class);
    }

    public function tamanoLegible(): string
    {
        $bytes = $this->tamano ?? 0;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }

    public function urlDescarga(): string
    {
        return route('archivos.descargar', $this->id);
    }
}
