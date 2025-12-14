<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    

    protected $table = 'documento';
    protected $primaryKey = 'idDocumento';
    public $timestamps = false;
    protected $fillable = [
        'idDetalle', 'certificado_pdf', 'resolucion_pdf', 
        'fecha_emision', 'fecha_vencimiento', 'fecha_subida'
    ];


     public function detalle()
    {
        return $this->belongsTo(DetalleLicencia::class, 'idDetalle', 'idDetalle');
    }
}

