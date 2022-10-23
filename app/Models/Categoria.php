<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
     use SoftDeletes;

    const DELETED_AT = 'fecha_eliminado';

    public $timestamps = false;
    protected $with = [];
    protected $table = 'tblc_categoria';
    protected $appends = [''];
    protected $fillable = [
        'id_categoria',
        'nombre',
        'color',
        'estatus',
        'imagen',
        'fecha_registro',
        'fecha_eliminado'
    ];
    protected $cast = [
        'fecha_registro'=>'datetime',
        'fecha_eliminado'=>'datetime',
    ];
    protected $primaryKey = 'id_categoria';
    protected $hidden = ['fecha_registro', 'fecha_eliminado'];
}
