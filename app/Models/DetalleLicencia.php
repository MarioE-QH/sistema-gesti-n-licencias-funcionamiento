<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleLicencia extends Model
{
    protected $table = 'detalle_licencias';
    protected $primaryKey = 'idDetalle';
    public $timestamps = false;
    protected $fillable = ['nsobre','descripcion','nExpediente','nResolucion','estado','idPersona','idLocal','idAutorizacion','idControlExpediente'];

   
    protected $casts = [
        'estado' => 'boolean'
    ];

    
    public function getEstadoAttribute($value)
    {
        
        if (is_string($value) && strlen($value) === 1) {
            return ord($value) === 1;
        }
        return (bool) $value;
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'idPersona', 'idPersona');
    }

    public function local()
    {
        return $this->belongsTo(Local::class, 'idLocal', 'idLocal');
    }

    public function autorizacion()
    {
        return $this->belongsTo(Autorizacion::class, 'idAutorizacion', 'idAutorizacion');
    }

    public function controlExpediente()
    {
         return $this->belongsTo(ControlExpediente::class, 'idControlExpediente', 'idControlExpediente');
    }

    public function documentos()
    {
        return $this->hasMany(Documento::class, 'idDetalle', 'idDetalle');
    }

}
