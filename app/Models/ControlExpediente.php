<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ControlExpediente extends Model
{
    use HasFactory;

    protected $table = 'control_expedientes';
    protected $primaryKey = 'idControlExpediente';
    public $timestamps = false; 

    protected $fillable = [
        'fecha_recep_dc',
        'aforo',
        'tipo_informe_itse',
        'fecha_acta_itse',
        'resultado',
        'num_informe_defensa_civil',
        'fecha_informe_defensa_civil',
        'num_resolucion_dc',
        'fecha_resolucion_dc',
        'num_certificado_dc',
        'fecha_cert_dc',
        'fecha_renovacion',
        'fecha_caducidad',
        'notificado',
        'fecha_entrega_cert',
        'estado',
        'observacion'
    ];

    public function detalleLicencias()
    {
        return $this->hasMany(DetalleLicencia::class, 'idControlExpediente', 'idControlExpediente');
    }
}
