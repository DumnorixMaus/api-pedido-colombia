<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Carrito extends Model
{
    use SoftDeletes;
    const DELETED_AT = '';
    public $timestamps = false;
    protected $table = 'tbl_carrito';
    protected $fillable = [
        'id_carrito',
        'id_cliente',
        'id_producto',
        'cantidad'
    ];
    protected $cast = [];
    protected $primaryKey = 'id_carrito';
    protected $hidden = [];
}
