<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'sector';
    protected $primaryKey = 'idSector';
    public $timestamps = false;
    protected $fillable = ['nombre'];

    public function direcciones()
    {
        return $this->hasMany(Direccion::class, 'idSector', 'idSector');
    }
}
