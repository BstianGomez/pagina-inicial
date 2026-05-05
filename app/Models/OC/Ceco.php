<?php

namespace App\Models\OC;

use Illuminate\Database\Eloquent\Model;

class Ceco extends Model
{
    protected $table = 'cecos';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo',
    ];
}
