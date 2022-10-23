<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UnidadMedida extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'fecha_eliminado';

    public $timestamps = false;
    protected $table = 'tblc_unidad_medida';
    protected $fillable = [
        'id_unidad_medida',
        'nombre',
        'siglas',
        'estatus',
        'fecha_registro',
        'fecha_eliminado'
    ];
    protected $cast = [
        'fecha_registro'=>'datetime',
        'fecha_eliminado'=>'datetime',
    ];
    protected $primaryKey = 'id_unidad_medida';
    protected $hidden = ['fecha_registro', 'fecha_eliminado'];
}
