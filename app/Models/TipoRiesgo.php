<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoRiesgo extends Model
{
    protected $table = 'tiporiesgo';
    protected $primaryKey = 'idTipoRiesgo';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    public function locales()
    {
        return $this->hasMany(Local::class, 'idTipoRiesgo', 'idTipoRiesgo');
    }
}
