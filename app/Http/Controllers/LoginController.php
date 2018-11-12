<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Sistema;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'usuario.required'      => 'Debe ingresar un nombre de usuario',
            'contrasenia.required'  => 'Debe ingresar una contraseña'
        ];

        $rules = [
            'usuario'       => 'required',
            'contrasenia'   => 'required'
        ];

        $this->validate($request,$rules,$messages);

        $datos = false;

        $Sistema = new Sistema();
        $datos = $Sistema->validar_datos_login($usuario,$contrasenia);
        

        if ($datos) 
        {
            /**
             * Registrar variables de sesión
             */
            
            $menu_principal = $Sistema->EmpleadoPermisoMenu_M($datos[0]['IdEmpleado']);

            for ($i=0; $i < count($menu_principal); $i++) { 
                $submenu_principal[$menu_principal[$i]['IdListGrupo']] = $Sistema->EmpleadoPermisoSubMenu_M($menu_principal[$i]['IdListGrupo'],$datos[0]['IdEmpleado']);
            }

            $datos_usuario = $datos[0]['Nombres'] . ' ' . $datos[0]['ApellidoPaterno'] . ' ' . $datos[0]['ApellidoMaterno'];
            $nombre = explode(" ", $datos[0]['Nombres']);
            $nombre_corto = $nombre[0] . ' ' . $datos[0]['ApellidoPaterno'];
            $rol_usuario = $datos[0]['Rol'];

            session( ['menu_principal'      => $menu_principal] );
            session( ['submenu_principal'   => $submenu_principal] );
            session( ['datos_usuario'       => $datos_usuario] );
            session( ['nombre_corto'        => $nombre_corto] );
            session( ['rol_usuario'         => $rol_usuario] );
            session( ['id_empleado'         => $datos[0]['IdEmpleado'] ] );
            session( ['anio_ingreso'        => $datos[0]['AnioIngreso'] ] );
            session( ['con_mp'              => true ] );
            return redirect('inicio');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
