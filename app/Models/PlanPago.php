<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanPago extends Model
{
    use SoftDeletes;

    protected $table = 'plan_pagos';
    protected $fillable = [
        'estado',
        'fechainicio',
        'fechafin',
        'montoporcuota',
        'montototal',
        'numerocuotas',
        'observacion',
        'orden_trabajo_id',
    ];

    protected $casts = [
        'fechainicio' => 'date',
        'fechafin' => 'date',
        'montoporcuota' => 'float',
        'montototal' => 'float',
    ];

    public function ordenTrabajo()
    {
        return $this->belongsTo(OrdenTrabajo::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }
}
