<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
     ////  GUARDAR CATALOGOS CLIENTES //////////

     public function clientes(){
        $datos = [
            'error' => false,
            'mensaje' =>  '',
            'data' => Cliente::orderBy('nombre')->get()
            ];
        return response()->json($datos);
    }

    public function cliente($id){
        $dataqry = Cliente::findOrFail($id);

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


    public function store_cliente(Request $request){

        $valida = array(
            'nombre'=>'required|string',
            'apellidos'=>'required|string',
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
        $apellidos = $request['apellidos'];

        $existe = Cliente::where('nombre','like',"$nombre")->where('nombre','like',"$apellidos")->get()->first();
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

        $dataqry = Cliente::create($request->all());

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


    public function update_cliente(Request $request, $id){

        $nombre = $request['nombre'];
        $apellidos = $request['apellidos'];

        $valida = array(
            'nombre'=>['required','string', Rule::unique('tblc_cliente')->where(function ($query) use($nombre,$apellidos) {
                return $query->where('nombre','like', $nombre)->where('apellidos', 'like', $apellidos);
              })->ignore($id,'id_cliente')],
            'apellidos'=>'required|string',
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

        $dataqry = Cliente::find($id);
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

    public function delete_cliente($id){
        $dataqry = Cliente::find($id);
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

    //// FIN GUARDAR CATALOGOS CLIENTES //////////


}
