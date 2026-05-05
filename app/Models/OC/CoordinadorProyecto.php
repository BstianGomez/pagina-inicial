<?php

namespace App\Models\OC;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoordinadorProyecto extends Model
{
    use HasFactory;
    protected $fillable = ['nombre'];
}
