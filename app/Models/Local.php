<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Local extends Model
{
    protected $table = 'local';
    protected $primaryKey = 'idLocal';
    public $timestamps = false;
    protected $fillable = [
        'nombre_comercial','nMunicipal','giro_autorizado','area','idDireccion','idTipoRiesgo'
    ];

    public function direccion()
    {
        return $this->belongsTo(Direccion::class, 'idDireccion', 'idDireccion');
    }

    public function tipoRiesgo()
    {
        return $this->belongsTo(TipoRiesgo::class, 'idTipoRiesgo', 'idTipoRiesgo');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleLicencia::class, 'idLocal', 'idLocal');
    }
}
