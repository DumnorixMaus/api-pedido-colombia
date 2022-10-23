<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
    const DELETED_AT = 'fecha_eliminado';

    public $timestamps = false;
    protected $table = 'tblc_cliente';
    protected $fillable = [
        'id_cliente',
        'nombre',
        'apellidos',
        'telefono',
        'correo',
        'estatus',
        'direccion',
        'latitud',
        'longitud',
        'fecha_registro',
        'fecha_eliminado'
    ];
    protected $cast = [
        'fecha_registro'=>'datetime',
        'fecha_eliminado'=>'datetime',
    ];
    protected $primaryKey = 'id_cliente';
    protected $hidden = ['fecha_registro', 'fecha_eliminado'];
}
