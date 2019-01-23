<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Archivo;

class RutasController extends Controller
{
    public function index()
    {
    	return view('archivo.ruta');
    }

    public function mostrar_rutas()
    {
    	$archivo = new Archivo();
    	$data = $archivo->Listar_Rutas();

    	if (count($data) > 0) {
    		for ($i=0; $i < count($data); $i++) {
    			$response[] = array(
    				'IdRuta'	=> $data[$i]['IdRuta'],
    				'Nombre'	=> $data[$i]['Nombre']
    			);
    		}

    		return response()->json(['data' => $response]);
    	} else {
    		return response()->json(['data' => 'sindatos']);
    	}
    }

    public function mostrar_rutas_detalle($idruta)
    {
    	$tiposervicios = array();

    	$archivo = new Archivo();
    	$data = $archivo->Listar_Rutas_Detalle($idruta);

    	if (count($data) > 0) {
    		/*for ($i=0; $i < count($data); $i++) {
    			$response[] = array(
    				'IdRuta'	=> $data[$i]['IdRuta'],
    				'Nombre'	=> $data[$i]['Nombre']
    			);
    		}
*/
    		for ($i=0; $i < count($data); $i++) {
    			$tiposervicios[$i] = array(
    				'IdTipoServicio' 	=> $data[$i]['IdTipoServicio'],
    				'TipoServicio'		=> trim($data[$i]['TipoServicio'])
    			);
    		}

    		for ($i=0; $i < count($data); $i++) {
    			$especialidades[$i] = array(
    				'IdEspecialidad' 	=> $data[$i]['IdEspecialidad'],
    				'Especialidad'		=> trim($data[$i]['Especialidad']),
    				'IdTipoServicio' 	=> $data[$i]['IdTipoServicio']
    			);
    		}

    		for ($i=0; $i < count($data); $i++) {
    			$servicios[$i] = array(
    				'IdServicio' 		=> $data[$i]['IdServicio'],
    				'Servicio'			=> trim($data[$i]['Servicio']),
    				'IdEspecialidad' 	=> $data[$i]['IdEspecialidad']
    			);
    		}

    		$tiposervicios = array_map("unserialize", array_unique(array_map("serialize", $tiposervicios)));
    		$tiposervicios = array_values($tiposervicios);

    		$especialidades = array_map("unserialize", array_unique(array_map("serialize", $especialidades)));
    		$especialidades = array_values($especialidades);

    		$servicios = array_map("unserialize", array_unique(array_map("serialize", $servicios)));
    		$servicios = array_values($servicios);

    		for ($i=0; $i < count($especialidades); $i++) {
    			$x = 0; 
    			for ($j=0; $j < count($servicios); $j++) { 
    				if ($especialidades[$i]['IdEspecialidad'] == $servicios[$j]['IdEspecialidad']) {
    					$especialidades[$i]['Servicios'][$x] = array(
    						'IdServicio' 	=> $servicios[$j]['IdServicio'],
    						'Servicio'		=> trim($servicios[$j]['Servicio'])
    					);
    					$x++;
    				}
    			}
    		}

    		for ($i=0; $i < count($tiposervicios); $i++) {
    			$x = 0;
    			for ($j=0; $j < count($especialidades); $j++) { 
    				if ($tiposervicios[$i]['IdTipoServicio'] == $especialidades[$j]['IdTipoServicio']) {
    					$tiposervicios[$i]['Especialidades'][$x] = $especialidades[$j];
    				}
    				$x++;
    			}
    		}

    		# traer archiveros
    		$data_conserjes = $archivo->Listar_Conserjes_Por_Ruta($idruta);

    		if ($data_conserjes[0]['IdEmpleado'] != NULL) {
    			for ($i=0; $i < count($data_conserjes); $i++) { 
	    			$conserjes[] = array(
	    				'IdEmpleado' => $data_conserjes[$i]['IdEmpleado'],
	    				'Empleado' => $data_conserjes[$i]['ApellidoPaterno'] . ' ' . $data_conserjes[$i]['ApellidoMaterno'] . ' ' . $data_conserjes[$i]['Nombres']
	    			);
	    		}
    		} else {
    			$conserjes = "Sin conserjes registrados";
    		}

    		$response = array(
    			'ruta'		=> $data[0]['Ruta'],
    			'detalle' 	=> $tiposervicios,
    			'conserjes' => $conserjes
    		);

    		return response()->json(['data' => $response]);
    	} else {
    		return response()->json(['data' => 'sindatos']);
    	}
    }

    public function registrar_ruta(Request $request)
    {
    	$messages = [
            'ruta_nombre.required' 		=> 'Ingrese un nombre de ruta.'
        ];

        $rules = [
            'ruta_nombre' 	=> 'required'
        ];

        $this->validate($request,$rules,$messages);
    	
    	# insertando nueva ruta 
    	$IdRuta = false;
    	$archivo = new Archivo();
    	$IdRuta = $archivo->Inserta_Nueva_Ruta(strtoupper($request->ruta_nombre));

    	if ($IdRuta) {
    		# registrar los servicios
    		if (count($request->servicios) > 0) {
    			for ($i=0; $i < count($request->servicios); $i++) {
	    			$archivo->Inserta_Nueva_Ruta_Servicios($IdRuta, $request->servicios[$i]['IdServicio']);
	    		}
    		}

    		# registrar conserjes
    		if (count($request->conserjes) > 0) {
    			for ($i=0; $i < count($request->conserjes); $i++) {
	    			$archivo->Inserta_Nueva_Ruta_Conserje($IdRuta, $request->conserjes[$i]['IdEmpleado']);
	    		}
    		}

    		return response()->json(['data' => 'seregistro']);

    	} else {
    		return response()->json(['data' => 'noseregistro']);
    	}

    	// print_r($Request->servicios);
    	// echo 'hola';
    }

    public function eliminar_ruta($idruta)
    {
    	$archivo = new Archivo();
    	$archivo->Eliminar_Ruta($idruta);
    }
}
