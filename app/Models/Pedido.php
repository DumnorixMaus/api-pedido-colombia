<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Pedido extends Model
{

    use SoftDeletes;
    const DELETED_AT = 'fecha_eliminado';
    public $timestamps = false;
    protected $table = 'tbl_pedido';
    protected $appends = ['cliente'];

    protected $fillable = [
        'id_pedido',
        'id_cliente',
        'folio',
        'fecha',
        'monto_total',
        'estatus',
        'tipo',
        'observaciones',
        'fecha_registro',
        'fecha_eliminado'
    ];
    protected $cast = [
        'fecha_registro'=>'datetime',
        'fecha_eliminado'=>'datetime',
    ];
    protected $primaryKey = 'id_pedido';
    protected $hidden = ['fecha_registro', 'fecha_eliminado'];

    public function getClienteAttribute(){
        return DB::table('tblc_cliente')->where('id_cliente',$this->id_cliente)->get()->first();
    }

    public function productos(){
        return $this->belongsToMany(Producto::class,'tbl_pedido_producto', 'id_pedido', 'id_producto');
    }
}
