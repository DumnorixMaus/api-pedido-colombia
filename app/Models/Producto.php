<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Producto extends Model
{
    use SoftDeletes;

    const DELETED_AT = 'fecha_eliminado';

    public $timestamps = false;
    protected $with = [];
    protected $table = 'tblc_producto';
    protected $appends = ['categoria', 'umedida', 'proveedor'];
    protected $fillable = [
        'id_producto',
        'id_unidad_medida',
        'id_categoria',
        'id_proveedor',
        'nombre',
        'imagen',
        'codigo',
        'costo',
        'costo_venta',
        'descripcion',
        'estatus',
        'stock',
        'stock_minimo',
        'fecha_registro',
        'fecha_eliminado'
    ];
    protected $cast = [
        'fecha_registro'=>'datetime',
        'fecha_eliminado'=>'datetime',
    ];
    protected $primaryKey = 'id_producto';
    protected $hidden = ['fecha_registro', 'fecha_eliminado'];

    public function getCategoriaAttribute(){
        return DB::table('tblc_categoria')->where('id_categoria',$this->id_categoria)->get()->first();
    }

    public function getUmedidaAttribute(){
        return DB::table('tblc_unidad_medida')->where('id_unidad_medida',$this->id_unidad_medida)->get()->first();
    }
    public function getProveedorAttribute(){
        return DB::table('tblc_proveedor')->where('id_proveedor',$this->id_proveedor)->get()->first();
    }

}
