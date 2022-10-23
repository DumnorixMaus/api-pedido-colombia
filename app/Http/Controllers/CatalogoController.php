<?php

namespace App\Http\Controllers;

use App\Models\UnidadMedida;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Proveedor;


use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class CatalogoController extends Controller
{

    ////  GUARDAR CATALOGOS UNIDAD DE MEDIDA //////////

    public function unidad_medidas(){
        $datos = [
            'error' => false,
            'mensaje' =>  '',
            'data' => UnidadMedida::orderBy('nombre')->get()
            ];
        return response()->json($datos);
    }
    public function unidad_medida($id){
        $umedida = UnidadMedida::findOrFail($id);

        if (!$umedida->fecha_eliminado) {
            $datos = [
                'error' => false,
                'mensaje' =>  '',
                'data' => $umedida
                ];
            return response()->json($datos);
        }else{
            $datos = [
                'error' => true,
                'mensaje' =>  'El registro no se encuentra en la base de datos',
                'data' => null
                ];
            return response()->json($datos);
        }
    }


    public function store_unidad_medida(Request $request){

        $valida = array(
            'nombre'=>'required|string',
            'siglas'=>'required'
        );

        $validator = Validator::make( $request->all(), $valida );
        if($validator->fails()) {
            $datos = [
                'error' => true,
                'mensaje' =>  $validator->errors()->first(),
                'data' => null
                ];
            return response()->json($datos);
        }

        $now = date('Y-m-d H:i:s');
        $nombre = $request['nombre'];
        $existe = UnidadMedida::where('nombre','like',"$nombre")->get()->first();
        if ($existe) {
            $datos = [
                        'error' => true,
                        'mensaje' => 'El nombre de este registro ya existe',
                        'data' => null
                        ];
            return response()->json($datos);
        }

        $request['fecha_registro'] = $now;
        $request['fecha_eliminado'] = null;

            // return $guarda;

        $umedida = UnidadMedida::create($request->all());

        try {
            $datos = [
                'error' => false,
                'mensaje' => 'El registro se guardo correctamente',
                'data' => $umedida
                ];
        } catch (\Throwable $th) {

            $datos = [
                'error' => true,
                'mensaje' => $th->getMessage(),
                'data' => null
                ];
        }
        return response()->json($datos);
    }


    public function update_unidad_medida(Request $request, $id){

        $valida = array(
            'nombre'=>['required','string', Rule::unique('tblc_unidad_medida','nombre')->ignore($id,'id_unidad_medida')],
            'siglas'=>'required'
        );
        $validator = Validator::make( $request->all(), $valida );
        if($validator->fails()) {
            $datos = [
                'error' => true,
                'mensaje' =>  $validator->errors()->first(),
                'data' => null
                ];
            return response()->json($datos);
        }

        $umedida = UnidadMedida::find($id);
        if ($umedida) {
            $umedida->update($request->all());

            $datos = [
                'error' => false,
                'mensaje' => 'El registro se actualizo correctamente',
                'data' => $umedida
                ];
            return response()->json($datos);
        }

        $datos = [
            'error' => true,
            'mensaje' => 'Problemas al actualizar este registro!',
            'data' => null
            ];
        return response()->json($datos);

    }

    public function delete_unidad_medida($id){
        $umedida = UnidadMedida::find($id);
        if ($umedida) {
            $umedida->update(['fecha_eliminado'=>date('Y-m-d H:i:s')]);
            $datos = [
                'error' => false,
                'mensaje' => 'El registro se elimino correctamente',
                'data' => $umedida
                ];
            return response()->json($datos);
        }

        $datos = [
            'error' => true,
            'mensaje' => 'El registro que desea eliminar no existe',
            'data' => null
            ];
        return response()->json($datos);

    }
    //// FIN GUARDAR CATALOGOS UNIDAD DE MEDIDA //////////



        ////  GUARDAR CATALOGOS CATEGORIA PRODUCTOS //////////

        public function cat_productos(){
            $datos = [
                'error' => false,
                'mensaje' =>  '',
                'data' => Categoria::orderBy('nombre')->get()
                ];
            return response()->json($datos);
        }

        public function cat_producto($id){
            $dataqry = Categoria::findOrFail($id);

            if (!$dataqry->fecha_eliminado) {
                $datos = [
                    'error' => false,
                    'mensaje' =>  '',
                    'data' => $dataqry
                    ];
                return response()->json($datos);
            }else{
                $datos = [
                    'error' => true,
                    'mensaje' =>  'El registro no se encuentra en la base de datos',
                    'data' => null
                    ];
                return response()->json($datos);
            }
        }


        public function store_cat_producto(Request $request){

            $valida = array(
                'nombre'=>'required|string'

            );

            $validator = Validator::make( $request->all(), $valida );
            if($validator->fails()) {
                $datos = [
                    'error' => true,
                    'mensaje' =>  $validator->errors()->first(),
                    'data' => null
                    ];
                return response()->json($datos);
            }

            $now = date('Y-m-d H:i:s');
            $nombre = $request['nombre'];
            $existe = Categoria::where('nombre','like',"$nombre")->get()->first();
            if ($existe) {
                $datos = [
                            'error' => true,
                            'mensaje' => 'El nombre de este registro ya existe',
                            'data' => null
                            ];
                return response()->json($datos);
            }

            $request['fecha_registro'] = $now;
            $request['fecha_eliminado'] = null;

            $dataqry = Categoria::create($request->all());

            try {
                $datos = [
                    'error' => false,
                    'mensaje' => 'El registro se guardo correctamente',
                    'data' => $dataqry
                    ];
            } catch (\Throwable $th) {

                $datos = [
                    'error' => true,
                    'mensaje' => $th->getMessage(),
                    'data' => null
                    ];
            }
            return response()->json($datos);
        }


        public function update_cat_producto(Request $request, $id){

            $valida = array(
                'nombre'=>['required','string', Rule::unique('tblc_categoria','nombre')->ignore($id,'id_categoria')]
            );
            $validator = Validator::make( $request->all(), $valida );
            if($validator->fails()) {
                $datos = [
                    'error' => true,
                    'mensaje' =>  $validator->errors()->first(),
                    'data' => null
                    ];
                return response()->json($datos);
            }

            $dataqry = Categoria::find($id);
            if ($dataqry) {


                $dataqry->update($request->all());

                $datos = [
                    'error' => false,
                    'mensaje' => 'El registro se actualizo correctamente',
                    'data' => $dataqry
                    ];
                return response()->json($datos);
            }

            $datos = [
                'error' => true,
                'mensaje' => 'Problemas al actualizar este registro!',
                'data' => null
                ];
            return response()->json($datos);

        }

        public function delete_cat_producto($id){
            $dataqry = Categoria::find($id);
            if ($dataqry) {
                $dataqry->update(['fecha_eliminado'=>date('Y-m-d H:i:s')]);
                $datos = [
                    'error' => false,
                    'mensaje' => 'El registro se elimino correctamente',
                    'data' => $dataqry
                    ];
                return response()->json($datos);
            }

            $datos = [
                'error' => true,
                'mensaje' => 'El registro que desea eliminar no existe',
                'data' => null
                ];
            return response()->json($datos);

        }

        //// FIN GUARDAR CATALOGOS CATEGORIA PRODUCTOS //////////

        ////  GUARDAR CATALOGOS PRODUCTOS //////////

           public function productos(){
            $datos = [
                'error' => false,
                'mensaje' =>  '',
                'data' => Producto::orderBy('nombre')->get()
                ];
            return response()->json($datos);
        }

        public function producto($id){
            $dataqry = Producto::findOrFail($id);

            if (!$dataqry->fecha_eliminado) {
                $datos = [
                    'error' => false,
                    'mensaje' =>  '',
                    'data' => $dataqry
                    ];
                return response()->json($datos);
            }else{
                $datos = [
                    'error' => true,
                    'mensaje' =>  'El registro no se encuentra en la base de datos',
                    'data' => null
                    ];
                return response()->json($datos);
            }
        }


        public function store_producto(Request $request){

            $valida = array(
                'id_unidad_medida'=>'required',
                'id_categoria'=>'required',
                'id_proveedor'=>'required',
                'nombre'=>'required|string',
                'costo_venta'=>'required',
                'codigo'=>'required',
                'estatus'=>'required'
            );

            $validator = Validator::make( $request->all(), $valida );
            if($validator->fails()) {
                $datos = [
                    'error' => true,
                    'mensaje' =>  $validator->errors()->first(),
                    'data' => null
                    ];
                return response()->json($datos);
            }

            $now = date('Y-m-d H:i:s');
            $nombre = $request['nombre'];
            $existe = Producto::where('nombre','like',"$nombre")->where('id_unidad_medida',$request['id_unidad_medida'])->where('id_categoria',$request['id_categoria'])->get()->first();
            if ($existe) {
                $datos = [
                            'error' => true,
                            'mensaje' => 'El nombre de este registro ya existe',
                            'data' => null
                            ];
                return response()->json($datos);
            }

            $request['fecha_registro'] = $now;
            $request['fecha_eliminado'] = null;

                // return $guarda;

            $dataqry = Producto::create($request->all());

            try {
                $datos = [
                    'error' => false,
                    'mensaje' => 'El registro se guardo correctamente',
                    'data' => $dataqry
                    ];
            } catch (\Throwable $th) {

                $datos = [
                    'error' => true,
                    'mensaje' => $th->getMessage(),
                    'data' => null
                    ];
            }
            return response()->json($datos);
        }


        public function update_producto(Request $request, $id){

            $valida = array(
                'nombre'=>['required','string', Rule::unique('tblc_producto','nombre')->where('id_unidad_medida',$request['id_unidad_medida'])->where('id_categoria',$request['id_categoria'])->ignore($id,'id_producto')],
                'id_unidad_medida'=>'required',
                'id_categoria'=>'required',
                'id_proveedor'=>'required',
                'costo_venta'=>'required',
                'codigo'=>'required',
                'estatus'=>'required'
            );
            $validator = Validator::make( $request->all(), $valida );
            if($validator->fails()) {
                $datos = [
                    'error' => true,
                    'mensaje' =>  $validator->errors()->first(),
                    'data' => null
                    ];
                return response()->json($datos);
            }

            $dataqry = Producto::find($id);
            if ($dataqry) {
                $dataqry->update($request->all());

                $datos = [
                    'error' => false,
                    'mensaje' => 'El registro se actualizo correctamente',
                    'data' => $dataqry
                    ];
                return response()->json($datos);
            }

            $datos = [
                'error' => true,
                'mensaje' => 'Problemas al actualizar este registro!',
                'data' => null
                ];
            return response()->json($datos);

        }

        public function delete_producto($id){
            $dataqry = Producto::find($id);
            if ($dataqry) {
                $dataqry->update(['fecha_eliminado'=>date('Y-m-d H:i:s')]);
                $datos = [
                    'error' => false,
                    'mensaje' => 'El registro se elimino correctamente',
                    'data' => $dataqry
                    ];
                return response()->json($datos);
            }

            $datos = [
                'error' => true,
                'mensaje' => 'El registro que desea eliminar no existe',
                'data' => null
                ];
            return response()->json($datos);

        }

        //// FIN GUARDAR CATALOGOS PRODUCTOS //////////


    ////  GUARDAR CATALOGOS PROVEEDOR //////////

    public function proveedors(){
        $datos = [
            'error' => false,
            'mensaje' =>  '',
            'data' => Proveedor::orderBy('nombre')->get()
            ];
        return response()->json($datos);
    }

    public function proveedor($id){
        $proveedor = Proveedor::findOrFail($id);

        if (!$proveedor->fecha_eliminado) {
            $datos = [
                'error' => false,
                'mensaje' =>  '',
                'data' => $proveedor
                ];
            return response()->json($datos);
        }else{
            $datos = [
                'error' => true,
                'mensaje' =>  'El registro no se encuentra en la base de datos',
                'data' => null
                ];
            return response()->json($datos);
        }
    }


    public function store_proveedor(Request $request){

        $valida = array(
            'nombre'=>'required|string',
            'telefono'=>'required'
        );

        $validator = Validator::make( $request->all(), $valida );
        if($validator->fails()) {
            $datos = [
                'error' => true,
                'mensaje' =>  $validator->errors()->first(),
                'data' => null
                ];
            return response()->json($datos);
        }

        $now = date('Y-m-d H:i:s');
        $nombre = $request['nombre'];
        $existe = Proveedor::where('nombre','like',"$nombre")->get()->first();
        if ($existe) {
            $datos = [
                        'error' => true,
                        'mensaje' => 'El nombre de este registro ya existe',
                        'data' => null
                        ];
            return response()->json($datos);
        }

        $request['fecha_registro'] = $now;
        $request['fecha_eliminado'] = null;

            // return $guarda;

        $proveedor = Proveedor::create($request->all());

        try {
            $datos = [
                'error' => false,
                'mensaje' => 'El registro se guardo correctamente',
                'data' => $proveedor
                ];
        } catch (\Throwable $th) {

            $datos = [
                'error' => true,
                'mensaje' => $th->getMessage(),
                'data' => null
                ];
        }
        return response()->json($datos);
    }


    public function update_proveedor(Request $request, $id){

        $valida = array(
            'nombre'=>['required','string', Rule::unique('tblc_proveedor','nombre')->ignore($id,'id_proveedor')],
            'telefono'=>'required'
        );
        $validator = Validator::make( $request->all(), $valida );
        if($validator->fails()) {
            $datos = [
                'error' => true,
                'mensaje' =>  $validator->errors()->first(),
                'data' => null
                ];
            return response()->json($datos);
        }

        $proveedor = Proveedor::find($id);
        if ($proveedor) {
            $proveedor->update($request->all());

            $datos = [
                'error' => false,
                'mensaje' => 'El registro se actualizo correctamente',
                'data' => $proveedor
                ];
            return response()->json($datos);
        }

        $datos = [
            'error' => true,
            'mensaje' => 'Problemas al actualizar este registro!',
            'data' => null
            ];
        return response()->json($datos);

    }

    public function delete_proveedor($id){
        $proveedor = Proveedor::find($id);
        if ($proveedor) {
            $proveedor->update(['fecha_eliminado'=>date('Y-m-d H:i:s')]);
            $datos = [
                'error' => false,
                'mensaje' => 'El registro se elimino correctamente',
                'data' => $proveedor
                ];
            return response()->json($datos);
        }

        $datos = [
            'error' => true,
            'mensaje' => 'El registro que desea eliminar no existe',
            'data' => null
            ];
        return response()->json($datos);

    }

    //// FIN GUARDAR CATALOGOS PROVEEDOR //////////




}
