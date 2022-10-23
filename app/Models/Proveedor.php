<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proveedor extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'fecha_eliminado';

    public $timestamps = false;
    protected $table = 'tblc_proveedor';
    protected $appends = [];
    protected $fillable = [
        'id_proveedor',
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'estatus',
        'fecha_registro',
        'fecha_eliminado'
    ];
    protected $cast = [
        'fecha_registro'=>'datetime',
        'fecha_eliminado'=>'datetime',
    ];
    protected $primaryKey = 'id_proveedor';
    protected $hidden = ['fecha_registro', 'fecha_eliminado'];


}
