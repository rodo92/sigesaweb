<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Sistema;

class InicioController extends Controller
{
    public function index()
    {
    	
    	return view('inicio.index');
    }

    public function menu($idlistgrupo)
    {
    	echo "<pre>";
    	$menu = session('menu_principal');
    	$submenu_principal = session('submenu_principal');

    	for ($i=0; $i < count($menu); $i++) { 
    		if ($menu[$i]['IdListGrupo'] == $idlistgrupo) {
    			$nuevo_menu[0] = $menu[$i];
    		}
    	}
    	
    	if (array_key_exists($idlistgrupo, $submenu_principal)) {
            for ($i=0; $i < count($menu); $i++) { 
                if ($menu[$i]['IdListGrupo'] == $idlistgrupo) {
                    $titulo = $menu[$i]['Texto'];
                }
            }
            
        }
        $nuevo_submenu[$idlistgrupo] = $submenu_principal[$idlistgrupo];
        
        // seteando variables de inicio
        session( ['con_mp'      => false ] );
        //session( ['titulo'      => $titulo ] );
        //session( ['subtitulo'   => 'Inicio' ] );
        session( ['menu_principal'  	=> $nuevo_menu ] );
        session( ['submenu_principal'	=> $nuevo_submenu ] );
    }

    public function restaurar()
    {
    	$Sistema = new Sistema();
        $menu_principal = $Sistema->EmpleadoPermisoMenu_M(session('id_empleado'));

        for ($i=0; $i < count($menu_principal); $i++) { 
            $submenu_principal[$menu_principal[$i]['IdListGrupo']] = $Sistema->EmpleadoPermisoSubMenu_M($menu_principal[$i]['IdListGrupo'],session('id_empleado'));
        }
        

        session( ['menu_principal'      => $menu_principal] );
        session( ['submenu_principal'   => $submenu_principal] );
        session( ['con_mp'              => true ] );
        return redirect('inicio');
    }
}
