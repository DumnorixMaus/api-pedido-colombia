<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Permiso;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   
     ////  GUARDAR USUARIO //////////

     public function login(Request $request){
           //validate incoming request
         $this->validate($request, [
             'usuario' => 'required|string',
             'password' => 'required_without_all'
         ]);
         $token = null;
         $user = User::with('permisos_user')->where('usuario', $request['usuario'])->first();

         if(!$user){
            return response()->json([
                'autenticado' => false,
                'token' => null,
                'user'=> null,
                'mensaje'=> 'No se pudo inicar sesión, verifique sus credenciales o comuniquese con el administrador.'
            ]);
         }

         if ($user->estatus == 1 && Hash::check($request['password'], $user->password)) {

            //  Log::create(['id_usuario'=>$user->id_usuario,'descripcion'=>'Se inicio sesión','script'=>json_encode($request),'fecha'=>date('Y-m-d H:i:s'),'so'=>'', 'navegador'=>'', 'ip' =>'']);

             return response()->json([
                 'autenticado' => true,
                 'token' => null,
                 'user'=> $user,
                 'mensaje'=>'Se inicio sesión correctamente.'
             ]);

         }else{

            return response()->json([
                'autenticado' => false,
                'token' => null,
                'user'=> null,
                'mensaje'=> 'No se pudo inicar sesión, verifique sus credenciales o comuniquese con el administrador.'
            ]);

         }
         
     }

     public function usuarios(){
        $datos = [
            'error' => false,
            'mensaje' =>  '',
            'data' => User::with('permisos')->orderBy('nombre')->get()
            ];
        return response()->json($datos);
    }

    public function usuario($id){
        $dataqry = User::findOrFail($id);
        
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

    public function store_usuario_permiso(Request $request, $id){        
        $user = User::find($id);   
        $dataqry = $user->permisos()->sync($request->all());

        try {
            $datos = [
                'error' => false,
                'mensaje' => 'Se guardaron los permisos correctamente',
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


    public function store_usuario(Request $request){        
        
        $valida = array(
            'nombre'=>'required',
            'apellidos'=>'required',
            'usuario'=>'required',
            'password'=>'required',
            'tipo'=>'required',
            'editar'=>'required',
            'eliminar'=>'required'
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
        
        $request['password'] = Hash::make($request['password']);

        $now = date('Y-m-d H:i:s');
        $nombre = $request['usuario'];
        $existe = User::where('usuario','like',"$nombre")->get()->first();
        if ($existe) {
            $datos = [
                        'error' => true,
                        'mensaje' => 'El usuario de este registro ya existe',
                        'data' => null
                        ];
            return response()->json($datos);
        }
        
        $request['fecha_registro'] = $now;
        $request['fecha_eliminado'] = null;

            // return $guarda;

        $dataqry = User::create($request->all());

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
    

    public function update_usuario(Request $request, $id){        
        
        $valida = array(
            'usuario'=>['required', Rule::unique('tbl_usuario','usuario')->ignore($id,'id_usuario')],
            'nombre'=>'required',
            'apellidos'=>'required',
            'tipo'=>'required',
            'editar'=>'required',
            'eliminar'=>'required'
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

        $dataqry = User::find($id);
        if ($dataqry) {

            $rqdata = ($request['password'] == '') ? $request->except('password') : $request->all();

            $dataqry->update($rqdata);

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

    public function delete_usuario($id){
        $dataqry = User::find($id);
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


    //// FIN GUARDAR USUARIO //////////

    ////  GUARDAR PERMISOS //////////

    public function permisos_padre(){
        $datos = [
            'error' => false,
            'mensaje' =>  '',
            'data' => Permiso::where('id_padre',0)->where('archivo', '')->orderBy('ordenamiento')->get()
            ];
        return response()->json($datos);
    }

    public function permisos(){
        $datos = [
            'error' => false,
            'mensaje' =>  '',
            'data' => Permiso::where('id_padre',0)->orderBy('ordenamiento')->get()
            ];
        return response()->json($datos);
    }

    public function permiso($id){
        $dataqry = Permiso::findOrFail($id);
        
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


    public function store_permiso(Request $request){        
        
        $valida = array(
            'nombre'=>'required',
            'ordenamiento'=>'required',
            'tipo'=>'required'
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
        $nombre = $request['archivo'];
        if($nombre != ''){
            $existe = Permiso::where('archivo','like',"$nombre")->get()->first();
            if ($existe) {
                $datos = [
                            'error' => true,
                            'mensaje' => 'El nombre del archivo de este registro ya existe',
                            'data' => null
                            ];
                return response()->json($datos);
            }
        }
       
        
        $request['fecha_registro'] = $now;
        $request['fecha_eliminado'] = null;

            // return $guarda;

        $dataqry = Permiso::create($request->all());

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
    

    public function update_permiso(Request $request, $id){        
        
        $valida = array(
            'archivo'=>[Rule::unique('tbl_permiso','archivo')->ignore($id,'id_permiso')],
            'nombre'=>'required',
            'ordenamiento'=>'required',
            'tipo'=>'required'
        );    
        $nombre = $request['archivo'];
        if($nombre == '') unset($valida[0]);
        
        $validator = Validator::make( $request->all(), $valida );
        if($validator->fails()) {        
            $datos = [
                'error' => true,
                'mensaje' =>  $validator->errors()->first(),
                'data' => null
                ];
            return response()->json($datos);
        }

        $dataqry = Permiso::find($id);
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

    public function delete_permiso($id){
        $dataqry = Permiso::find($id);
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

    //// FIN GUARDAR PERMISOS //////////
}
