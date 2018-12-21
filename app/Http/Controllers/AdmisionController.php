<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Sistema;
use WebSigesa\Medico;

class AdmisionController extends Controller
{
    public function AdmisionCitas()
    {
    	return view('consultaexterna.admision');
    }

    public function Especialidades_Tipo_Servicio()
    {
    	// para consulta externa
    	$Sistema = new Sistema();
    	$especialidades = $Sistema->Obtener_Especialidades_Tipo_Servicio(1);

    	for ($i=0; $i < count($especialidades); $i++) { 
            $data[] = array(
                'id'        => $especialidades[$i]['IdEspecialidad'],
                'name'      => strtoupper($especialidades[$i]['Nombre']),
                'iddpto'    => $especialidades[$i]['IdDepartamento']
            );
        }   

    	return response()->json($data);
    }

    public function Medicos_CE()
    {
    	// para consulta externa
    	$Medico = new Medico();
    	$medicos = $Medico->Obtener_Medicos(1);

    	for ($i=0; $i < count($medicos); $i++) { 
            $data[] = array(
                'id'        => $medicos[$i]['IdMedico'],
                'name'      => strtoupper($medicos[$i]['ApellidoPaterno'] . ' ' . $medicos[$i]['ApellidoMaterno'] . ' ' . $medicos[$i]['Nombres'])
            );
        }   

    	return response()->json($data);
    }

}
