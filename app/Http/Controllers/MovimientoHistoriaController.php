<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Archivo;

class MovimientoHistoriaController extends Controller
{
    public function index()
    {
    	return view('archivo.movimientohistoria');
    }

    public function listado_historias_archivero()
    {
    	$fecha = date('Y-m-d');
    	$fecha = date("Y-m-d",strtotime($fecha."+ 1 days")); 
		$idempleado = session()->get('id_empleado');


		$archivo = new Archivo();
        $data = $archivo->Listado_Historia_Archivero_Digito_Terminal($idempleado, $fecha);
        
        for ($i=0; $i < count($data); $i++) { 
        	$programaciones[] = array(
        		'idprogramacion'	=> $data[$i]['IdProgramacion'],
        		'horainicio'		=> $data[$i]['InicioPro'],
        		'horafin'			=> $data[$i]['FinPro'],
        		'tiempo'			=> $data[$i]['TPA'],
        		'consultorio'		=> $data[$i]['Consultorio'],
        		'idservicio'		=> $data[$i]['IdServicio']	
        	);
        }

        $programaciones = array_map("unserialize", array_unique(array_map("serialize", $programaciones)));
    	$programaciones = array_values($programaciones);

    	for ($i=0; $i < count($programaciones); $i++) { 
    		// $nueva_programaciones[$i]['idservicio'] = $programaciones[$i]['idservicio'];
    		// $nueva_programaciones[$i]['consultorio'] = $programaciones[$i]['consultorio'];
    		$nueva_programaciones[$i]['idprogramacion'] = $programaciones[$i]['idprogramacion'];
    		// $nueva_programaciones[$i]['horainicioprogramacion'] = $programaciones[$i]['horainicio'];
    		// $nueva_programaciones[$i]['horafinprogramacion'] = $programaciones[$i]['horafin'];
    		$horainicio = $programaciones[$i]['horainicio'];
    		$c = 0;
    		while ($horainicio < $programaciones[$i]['horafin']) {
    			
    			// echo $programaciones[$i]['consultorio'] . '|' . $horainicio . '|';
    			$nueva_programaciones[$i]['cupos'][$c]['horainicio'] = $horainicio;
    			$horainicio = strtotime('+' . $programaciones[$i]['tiempo'] . ' minute', strtotime($horainicio));
    			
    			$horainicio = date('H:i', $horainicio);
    			// echo $horainicio . '|';
    			$nueva_programaciones[$i]['cupos'][$c]['horafin'] = $horainicio;
    			// echo $c . '';
    			$nueva_programaciones[$i]['cupos'][$c]['cupo'] = $c + 1;

    			$c++;
    			// echo '<br>';
    		}
    		// echo 'IdProgramacion:' . $programaciones[$i]['idprogramacion'] . '&nbsp;';
    		// echo 'Cupos:' . $c . '<br>';
    	}

    	for ($i=0; $i < count($data); $i++) { 
    		for ($j=0; $j < count($nueva_programaciones); $j++) { 
    			if ($data[$i]['IdProgramacion'] == $nueva_programaciones[$j]['idprogramacion']) {
    				for ($k=0; $k < count($nueva_programaciones[$j]['cupos']); $k++) { 
    					if ($nueva_programaciones[$j]['cupos'][$k]['horainicio'] == $data[$i]['InicioCita']) {
    						$nueva_programaciones[$j]['cupos'][$k]['historia'] = $data[$i]['NroHistoriaClinica'];
    						$nueva_programaciones[$j]['cupos'][$k]['Paciente'] = $data[$i]['Paciente'];
    						// $nueva_programaciones[$j]['cupos'][$k]['horainicio2'] = $data[$i]['InicioCita'];
    						$nueva_programaciones[$j]['cupos'][$k]['consultorio'] = $data[$i]['Consultorio'];
    						$nueva_programaciones[$j]['cupos'][$k]['SalidaArchivo'] = $data[$i]['SalidaArchivo'];
    					} else {
    						$nueva_programaciones[$j]['cupos'][$k]['consultorio'] = $data[$i]['Consultorio'];
    						$nueva_programaciones[$j]['cupos'][$k]['Archivero'] = $data[$i]['Archivero'];
    						$nueva_programaciones[$j]['cupos'][$k]['IdHistoriaSolicitada'] = $data[$i]['IdHistoriaSolicitada'];
    						$nueva_programaciones[$j]['cupos'][$k]['SalidaArchivo'] = $data[$i]['SalidaArchivo'];
    					}
    				}
    			}
    		}
    	}

    	for ($i=0; $i < count($nueva_programaciones); $i++) { 
    		for ($x=0; $x < count($nueva_programaciones[$i]['cupos']); $x++) { 
    			$nuevo[] = $nueva_programaciones[$i]['cupos'][$x];
    			// print_r($nueva_programaciones[$i]['cupos'][$x]);
    		}
    	}

        return response()->json(['data' => $nuevo]);
    }
}
