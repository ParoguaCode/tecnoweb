<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parte extends Model
{
    protected $fillable = [
        'nombre',
        'motor_id',
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
