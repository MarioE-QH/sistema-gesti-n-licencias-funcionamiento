<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $table = 'direccion';
    protected $primaryKey = 'idDireccion';
    public $timestamps = false;
    protected $fillable = ['nombre_via', 'idSector'];

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'idSector', 'idSector');
    }

    public function locales()
    {
        return $this->hasMany(Local::class, 'idDireccion', 'idDireccion');
    }
}
