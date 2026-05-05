<?php

namespace App\Models\Rendicion;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'requires_comanda', 'is_custom'];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
