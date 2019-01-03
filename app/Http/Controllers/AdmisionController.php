<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Sistema;
use WebSigesa\Medico;
use WebSigesa\Admision;

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

    public function Programacion_Por_Filtro($fecha, $especialidad=false,$medico=false)
    {
        $filtro = '';

        if ($especialidad)  { $filtro .= ' AND ProgramacionMedica.IdEspecialidad=' . $especialidad . ' '; }
        if ($medico)        { $filtro .= ' AND ProgramacionMedica.IdMedico=' . $medico . ' '; }

        $admision = new Admision();
        $data = $admision->Progamacion_Filtrado($fecha, ' AND ProgramacionMedica.IdProgramacion=459136 ');
        
        if (count($data) > 0) {
            /*for ($i=0; $i < count($data); $i++) { 
                $data_uno[]= array(
                    'IDPROGRAMACION' => $data[$i]['IdProgramacion'],
                    'FECHA' => $data[$i]['Fecha'],
                    // 'ID MEDICO' => $data[$i]['IdMedico'],
                    'MEDICO' => $data[$i]['MEDICO'],
                    'HORA INICIO' => $data[$i]['HoraInicio'],
                    'HORA FIN' => $data[$i]['HoraFin'],
                    'HORA FIN PROGRAMACION' => $data[$i]['HoraFinProgramacion'],
                    // 'ID TIPO PROGRAMACION' => $data[$i]['IdTipoProgramacion'],
                    'TIPO PROGRAMACION' => $data[$i]['TIPOPROGRAMACION'],
                    // 'ID TURNO' => $data[$i]['IdTurno'],
                    'TURNO' => $data[$i]['TURNO'],
                    // 'ID ESPECIALIDAD' => $data[$i]['IdEspecialidad'],
                    'ESPECIALIDAD' => $data[$i]['ESPECIALIDAD'],
                    // 'ID SERVICIO' => $data[$i]['IdServicio'],
                    'SERVICIOS' => $data[$i]['SERVICIOS'],
                    // 'ID TIPO SERVICIO' => $data[$i]['IdTipoServicio'],
                    'TIPO SERVICIO' => $data[$i]['TIPOSERVICIO'],
                    'TIEMPO' => $data[$i]['TIEMPO'],
                    'FECHA REGISTRO' => $data[$i]['FECHAREGISTRO']
                );

                $data_pre_dos = $admision->Cupos_Tomados($data[$i]['IdProgramacion']);
                echo json_encode($data_pre_dos);
            }*/

            return response()->json(['data' => $data]);

        }
        else { return response()->json(['data' => 'sindatos']); }
    }

}
