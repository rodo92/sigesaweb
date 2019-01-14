<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Sistema;

class SistemaController extends Controller
{
    public function get_data_session()
    {
    	if (session()->get('id_empleado')) {
    		$nombre_corto 		= session()->get('nombre_corto');
    		$rol_usuario 		= session()->get('rol_usuario');
			$anio_ingreso 		= session()->get('anio_ingreso');
			$datos_usuario 		= session()->get('datos_usuario');
			$menu_principal 	= session()->get('menu_principal');
			$submenu_principal 	= session()->get('submenu_principal');
			$con_mp 			= session()->get('con_mp');
			
			for ($i=0; $i < count($menu_principal); $i++) { 
    			$menu[$i] = array(
    				'IdListGrupo' 	=> $menu_principal[$i]['IdListGrupo'],
    				'Texto' 		=> $menu_principal[$i]['Texto'],
					'Clave' 		=> $menu_principal[$i]['Clave'],
					'Icono' 		=> $menu_principal[$i]['IconosWeb']
    			);			
    			foreach ($submenu_principal as $key => $value) {
	    			if ($menu_principal[$i]['IdListGrupo'] == $key) {
	    				for ($j=0; $j < count($value); $j++) { 
	    					$submenu[$j] = array(
	    						'Texto' => $value[$j]['Texto'], 
	    						'Clave' => $value[$j]['Clave']
	    					);

	    					$menu[$i]['Submenu'] = $submenu;
	    				}
	    			}
	    		}
    		}

    		return response()->json([
    			'nombre_corto'	=> $nombre_corto,
    			'rol_usuario' 	=> $rol_usuario,
				'anio_ingreso' 	=> $anio_ingreso,
				'datos_usuario'	=> $datos_usuario,
				'menu' 			=> $menu,
				'anio_ingreso' 	=> $anio_ingreso,
				'con_mp' 		=> $con_mp
    		]);
    		// echo "<pre>";print_r($menu);
    	}

    	return response()->json([
    		'error' => 'No se han creado las variables de sesion.'
    	]);
    }

    public function proveedores()
    {
    	$data = [];
        $Sistema = new Sistema();
    	$proveedores = $Sistema->Mostrar_Provedores();

        for ($i=0; $i < count($proveedores); $i++) { 
            //array_push($data, array(
                //'id'    => $proveedores[$i]['IDPROVEEDOR'],
                //'name'  => $proveedores[$i]['RAZONSOCIAL']
                //array_push($data,$proveedores[$i]['RAZONSOCIAL']);
            //));
            $data[] = array(
                'IDPROVEEDOR'   => $proveedores[$i]['IDPROVEEDOR'],
                'RUC'           => $proveedores[$i]['RUC'],
                'RAZONSOCIAL'   => $proveedores[$i]['RAZONSOCIAL']
            );
        }
        //array_values($proveedores);
    	return response()->json($data);
    }

    public function proveedor($ruc)
    {
        $data = false;

        $Sistema = new Sistema();
        $proveedores = $Sistema->Mostrar_Provedores();
            for ($i=0; $i < count($proveedores); $i++) { 
                if ($proveedores[$i]['RUC'] == trim($ruc)) {
                    $data = array(
                        'IDPROVEEDOR'   => $proveedores[$i]['IDPROVEEDOR'],
                        'RUC'           => $proveedores[$i]['RUC'],
                        'RAZONSOCIAL'   => $proveedores[$i]['RAZONSOCIAL'],
                        'DIRECCION'     => $proveedores[$i]['DIRECCION']
                    );
                }                
            }     
        return response()->json($data);
    }

    public function especialidades($idespecialidad = false)
    {
        $data = false;
        $Sistema = new Sistema();
        $especialidades = $Sistema->Obtener_Especialidades();

        if ($idespecialidad) {
            for ($i=0; $i < count($especialidades); $i++) { 
                if ($especialidades[$i]['IdEspecialidad'] == $idespecialidad) {
                    $data = array(
                        'id'        => $especialidades[$i]['IdEspecialidad'],
                        'name'      => strtoupper($especialidades[$i]['Nombre']),
                        'iddpto'    => $especialidades[$i]['IdDepartamento']
                    );
                }                
            }  
        } else {
            for ($i=0; $i < count($especialidades); $i++) { 
                $data[] = array(
                    'id'        => $especialidades[$i]['IdEspecialidad'],
                    'name'      => strtoupper($especialidades[$i]['Nombre']),
                    'iddpto'    => $especialidades[$i]['IdDepartamento']
                );
            }   
        }

        return response()->json($data);
    }

    public function Especialidades_Tipo_Servicio($idtiposervicio)
    {
        // para consulta externa
        $Sistema = new Sistema();
        $especialidades = $Sistema->Obtener_Especialidades_Tipo_Servicio($idtiposervicio);

        for ($i=0; $i < count($especialidades); $i++) { 
            $data[] = array(
                'id'        => $especialidades[$i]['IdEspecialidad'],
                'name'      => strtoupper($especialidades[$i]['Nombre'])
            );
        }   

        return response()->json($data);
    }

    public function Servicio_Especialidad($idespecialidad)
    {
        // para consulta externa
        $Sistema = new Sistema();
        $servicio = $Sistema->Obtener_Servicio_Por_Especialidad($idespecialidad);

        for ($i=0; $i < count($servicio); $i++) { 
            $data[] = array(
                'id'        => $servicio[$i]['IdServicio'],
                'name'      => strtoupper($servicio[$i]['Nombre'])
            );
        }   

        return response()->json($data);
    }

    public function tiposervicios($idtiposervicio = false)
    {
        $data = false;
        $Sistema = new Sistema();
        $tiposervicios = $Sistema->Obtener_Tipo_Servicio();

        if ($idtiposervicio) {
            for ($i=0; $i < count($tiposervicios); $i++) { 
                if ($tiposervicios[$i]['IdTipoServicio'] == $idtiposervicio) {
                    $data = array(
                        'id'        => $tiposervicios[$i]['IdTipoServicio'],
                        'name'      => strtoupper($tiposervicios[$i]['Descripcion'])
                    );
                }                
            }  
        } else {
            for ($i=0; $i < count($tiposervicios); $i++) { 
                $data[] = array(
                    'id'        => $tiposervicios[$i]['IdTipoServicio'],
                    'name'      => strtoupper($tiposervicios[$i]['Descripcion'])
                );
            }   
        }

        return response()->json($data);
    }
}