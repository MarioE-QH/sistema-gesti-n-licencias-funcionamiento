<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'idPersona';
    public $timestamps = false;
    protected $fillable = ['nombre_completos', 'dni', 'ruc'];
    protected $casts = [
        'ruc' => 'string',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleLicencia::class, 'idPersona', 'idPersona');
    }
    
    public function locales()
{
    return $this->hasMany(Local::class, 'idPersona', 'idPersona');
}

  
}
