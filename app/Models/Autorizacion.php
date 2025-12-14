<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autorizacion extends Model
{
    protected $table = 'autorizacion';
    protected $primaryKey = 'idAutorizacion';
    public $timestamps = false;
    protected $fillable = ['nAutorizacion','fecha_ingreso','fecha_emision'];

    public function detalles()
    {
        return $this->hasMany(DetalleLicencia::class, 'idAutorizacion', 'idAutorizacion');
    }
}
