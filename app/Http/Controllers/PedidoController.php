<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PedidoController extends Controller
{

        ////  GUARDAR PEDIDO //////////

        public function pedidos(){
            $datos = [
                'error' => false,
                'mensaje' =>  '',
                'data' => Pedido::orderBy('fecha')->get()
                ];
            return response()->json($datos);
        }

        public function pedido($id){
            $dataqry = Pedido::findOrFail($id);

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


        public function store_pedido(Request $request){

            $valida = array(
                'id_cliente'=>'required',
                'fecha'=>'required',
                'monto_total'=>'required'
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

            $request['fecha_registro'] = $now;
            $request['fecha_eliminado'] = null;
            $request['fecha'] = $now;

                // return $guarda;

            $dataqry = Pedido::create($request->all());

            try {
                 $productos = Pedido::find($dataqry->id_pedido);
                 $productos->update(['folio'=> $dataqry->id_pedido.'-'.date('Ym')]);

                $prodPedido = $request->input('productos');
                $productos->productos()->sync($prodPedido);
                $datpedido = Pedido::find($dataqry->id_pedido);

                $datos = [
                    'error' => false,
                    'mensaje' => 'El registro se guardo correctamente',
                    'data' => $datpedido
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


        public function update_pedido(Request $request, $id){

            $valida = array(
                'id_cliente'=>'required',
                'fecha'=>'required',
                'monto_total'=>'required'
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

            $dataqry = Pedido::find($id);
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

        public function delete_pedido($id){
            $dataqry = Pedido::find($id);
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

        //// FIN GUARDAR PEDIDO //////////

}
