<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Archivo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Codedge\Fpdf\Fpdf\Fpdf;

class MovimientoHistoriaController extends Controller
{
	private $fpdf;

    public function index()
    {
    	return view('archivo.movimientohistoria');
    }

    public function listado_historias_archivero()
    {
    	$fecha = date('Y-m-d');
    	//$fecha = date("Y-m-d",strtotime($fecha."+ 1 days")); // habilitar antes de irte
		$idempleado = session()->get('id_empleado');


		$archivo = new Archivo();
        $data = $archivo->Listado_Historia_Archivero_Digito_Terminal($idempleado, $fecha);
        for ($i=0; $i < count($data); $i++) { 
        	$programaciones[] = array(
        		'archivero'			=> $data[$i]['Archivero'],
        		'idprogramacion'	=> $data[$i]['IdProgramacion'],
        		'horainicio'		=> $data[$i]['InicioPro'],
        		'horafin'			=> $data[$i]['FinPro'],
        		'tiempo'			=> $data[$i]['TPA'],
        		'consultorio'		=> $data[$i]['Consultorio'],
        		'dinicial'			=> $data[$i]['DI'],
        		'dfinal'			=> $data[$i]['DF'],
        		'idservicio'		=> $data[$i]['IdServicio']	
        	);
        }

        $programaciones = array_map("unserialize", array_unique(array_map("serialize", $programaciones)));
    	$programaciones = array_values($programaciones);

    	for ($i=0; $i < count($programaciones); $i++) { 
    		
    		$horainicio = $programaciones[$i]['horainicio'];
    		$c = 0;
    		while ($horainicio < $programaciones[$i]['horafin']) {
    			
    			$nueva_programaciones[$i]['cupos'][$c]['horainicio'] = $horainicio;
    			$horainicio = strtotime('+' . $programaciones[$i]['tiempo'] . ' minute', strtotime($horainicio));
    			$horainicio = date('H:i', $horainicio);
    			$nueva_programaciones[$i]['cupos'][$c]['horafin'] = $horainicio;
    			$nueva_programaciones[$i]['cupos'][$c]['idservicio'] = $programaciones[$i]['idservicio'];
    			$nueva_programaciones[$i]['cupos'][$c]['idprogramacion'] = $programaciones[$i]['idprogramacion'];
    			$nueva_programaciones[$i]['cupos'][$c]['consultorio'] = $programaciones[$i]['consultorio'];
    			$nueva_programaciones[$i]['cupos'][$c]['archivero'] = $programaciones[$i]['archivero'];
    			$nueva_programaciones[$i]['cupos'][$c]['dinicial'] = $programaciones[$i]['dinicial'];
    			$nueva_programaciones[$i]['cupos'][$c]['dfinal'] = $programaciones[$i]['dfinal'];
    			$nueva_programaciones[$i]['cupos'][$c]['cupo'] = $c + 1;
    			$c++;
    		}
    	}

    	for ($i=0; $i < count($nueva_programaciones); $i++) { 
    		for ($x=0; $x < count($nueva_programaciones[$i]['cupos']); $x++) { 
    			$nuevo[] = $nueva_programaciones[$i]['cupos'][$x];
    		}
    	}

    	// algoritmo
    	for ($i=0; $i < count($nuevo); $i++) { 
    		for ($j=0; $j < count($data); $j++) { 
    			if ($nuevo[$i]['idprogramacion'] == $data[$j]['IdProgramacion']) {
    				if ($nuevo[$i]['horainicio'] == $data[$j]['InicioCita']) {
    					$nuevo[$i]['historia'] = $data[$j]['NroHistoriaClinica'];
    					$nuevo[$i]['paciente'] = $data[$j]['Paciente'];
    					$nuevo[$i]['idhistoriasolicitada'] = $data[$j]['IdHistoriaSolicitada'];
    					$nuevo[$i]['salidaarchivo'] = $data[$j]['SalidaArchivo'];
    					$nuevo[$i]['seriehc'] = $data[$j]['Serie_HC'];
	    			} else {

	    			}
    			}

    		}
    	}

    	return response()->json(['data' => $nuevo]);
    }

    public function estado_no_encontrado_historia_archiver($IdHistoriaSolicitada)
    {
    	$archivo = new Archivo();
        $data = $archivo->No_Encontrado_Historia_Archivero($IdHistoriaSolicitada);
    }

    public function estado_encontrado_historia_archiver($IdHistoriaSolicitada)
    {
    	$fecha = date('Y-m-d');
    	$archivo = new Archivo();
        $data = $archivo->Encontrado_Historia_Archivero($IdHistoriaSolicitada,$fecha);
    }

    public function excel_listado_archivero()
    {
    	$fecha = date('Y-m-d');
    	//$fecha = date("Y-m-d",strtotime($fecha."+ 1 days")); // habilitar antes de irte
		$idempleado = session()->get('id_empleado');
    	$archivo = new Archivo();
        $data = $archivo->Listado_Historia_Archivero_Digito_Terminal($idempleado, $fecha);

    	$styleArray = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFE8E5E5'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allborders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $styleCell = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xls($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();

        $activeSheet->setTitle("Reporte de Ingresos de Almacen");

        $activeSheet->getStyle('A1:D1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'HISTORIA')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'PACIENTE')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'SERIE HC')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'CONSULTORIO')->getStyle('D1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:D1");

        //Ingresando datos
        $j = 2;
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['NroHistoriaClinica']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['Paciente']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['Serie_HC']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['Consultorio']);

            $activeSheet->getStyle("A".$j.":D".$j)->applyFromArray($styleCell);
            $j++;
        }

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ListadoArchivero.xls"');
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }

    public function Listado_Historias_Enrutadas()
    {
    	$fecha = date('Y-m-d');
    	//$fecha = date("Y-m-d",strtotime($fecha."+ 1 days")); // habilitar antes de irte
		$idempleado = session()->get('id_empleado');

    	$archivo = new Archivo();
        $data = $archivo->Listado_Historia_Enrutado($fecha);
        return response()->json(['data' => $data]);
    }

    public function Listado_Historias_Conserje()
    {
    	$fecha = date('Y-m-d');
    	//$fecha = date("Y-m-d",strtotime($fecha."+ 1 days")); // habilitar antes de irte
		$idempleado = session()->get('id_empleado');

    	$archivo = new Archivo();
        $data = $archivo->Listado_Historia_Conserje($fecha, $idempleado);
        return response()->json(['data' => $data]);
    }

    public function Listado_Historias_Enrutadas_Excel()
    {
    	$fecha = date('Y-m-d');
    	//$fecha = date("Y-m-d",strtotime($fecha."+ 1 days")); // habilitar antes de irte
		$idempleado = session()->get('id_empleado');
    	$archivo = new Archivo();
        $data = $archivo->Listado_Historia_Enrutado($fecha);

    	$styleArray = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFE8E5E5'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allborders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $styleCell = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'top' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xls($spreadsheet);
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();

        $activeSheet->setTitle("Reporte de Historias con Rutas");

        $activeSheet->getStyle('A1:F1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'HISTORIA')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'PACIENTE')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'RUTA')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'ESPECIALIDAD')->getStyle('D1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'CONSULTORIO')->getStyle('E1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'CONSERJE')->getStyle('F1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:F1");

        //Ingresando datos
        $j = 2;
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['NroHistoriaClinica']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['Paciente']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['Ruta']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['Especialidad']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['Consultorio']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['Conserje']);

            $activeSheet->getStyle("A".$j.":F".$j)->applyFromArray($styleCell);
            $j++;
        }

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ListadoHistoriasEnrutadas.xls"');
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }

    public function generar_listados_conserje()
    {
    	$fecha = date('Y-m-d');
    	//$fecha = date("Y-m-d",strtotime($fecha."+ 1 days")); // habilitar antes de irte
		$idempleado = session()->get('id_empleado');
    	$archivo = new Archivo();
        $data = $archivo->Listado_Historia_Conserje($fecha, $idempleado);

        for ($i=0; $i < count($data); $i++) { 
        	$ruta[] = array(
        		'idruta'	=> $data[$i]['IdRuta'],
        		'ruta'		=> $data[$i]['Ruta']
        	);
        }

        $ruta = array_map("unserialize", array_unique(array_map("serialize", $ruta)));
    	$ruta = array_values($ruta);

        for ($i=0; $i < count($data); $i++) { 
        	$especialidades[] = array(
        		'idruta'	=> $data[$i]['IdRuta'],
        		'idespecialidad'		=> $data[$i]['IdEspecialidad'],
        		'especialidad'		=> $data[$i]['Especialidad']
        	);
        }

        $especialidades = array_map("unserialize", array_unique(array_map("serialize", $especialidades)));
    	$especialidades = array_values($especialidades);

    	for ($i=0; $i < count($data); $i++) { 
        	$consultorios[] = array(
        		'idespecialidad'	=> $data[$i]['IdEspecialidad'],
        		'consultorio'		=> $data[$i]['Consultorio'],
        		'idconsultorio'		=> $data[$i]['IdServicio']
        	);
        }

        $consultorios = array_map("unserialize", array_unique(array_map("serialize", $consultorios)));
    	$consultorios = array_values($consultorios);



    	for ($i=0; $i < count($ruta); $i++) {
    		
    		for ($j=0; $j < count($especialidades); $j++) { 
    			if ($ruta[$i]['idruta'] == $especialidades[$j]['idruta']) {
    				echo '<table border=1>';
    				echo '<tr><td colspan="2">' . $ruta[$i]['ruta'] . '</td></tr>';
    				echo '<tr><td>Especialidad: </td><td>' . $especialidades[$j]['especialidad'] . '</td></tr>';
    				for ($k=0; $k < count($consultorios); $k++) { 
    					if ($especialidades[$j]['idespecialidad'] == $consultorios[$k]['idespecialidad']) {
    						echo '<tr><td>Consultorio: </td><td>' . $consultorios[$k]['consultorio'] . '</td></tr>';
    						echo '<tr><td colspan="2">Historias Clínicas</td></tr>';
    						for ($l=0; $l < count($data); $l++) { 
    							if ($consultorios[$k]['idconsultorio'] == $data[$l]['IdServicio']) {
    								echo '<tr><td>' . $data[$l]['NroHistoriaClinica'] . '</td><td>' . $data[$l]['Paciente'] . '</td></tr>';

    							}
    						}
    					}
    				}
    			}
    			echo '</table>';
    		}
    		
    	}

        // return response()->json($especialidades);

    }
}
