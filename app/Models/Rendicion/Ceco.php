<?php

namespace App\Models\Rendicion;

use Illuminate\Database\Eloquent\Model;

class Ceco extends Model
{
    protected $fillable = ['name', 'code'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
