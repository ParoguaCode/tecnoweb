<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use SoftDeletes;

    protected $table = 'pagos';

    protected $fillable = [
        'estado',
        'fechapago',
        'metodopago',
        'monto',
        'numerocuota',
        'referencia',
        'plan_pago_id',
    ];

    protected $casts = [
        'fechapago' => 'date',
        'monto' => 'float',
    ];

    public function planPago()
    {
        return $this->belongsTo(PlanPago::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }
}
