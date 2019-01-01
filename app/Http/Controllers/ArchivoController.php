<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Archivo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ArchivoController extends Controller
{
    public function Reporte_Conserjeria($turno, $fecha)
    {
    	$Archivo = new Archivo();
        $data = $Archivo->Reporte_Archivo_Conserjeria($turno, $fecha);

        if (count($data) > 0) { 
        	for ($i=0; $i < count($data); $i++) { 
        		$response[] = array(
        			'RUTA' => strtoupper($data[$i]['Ruta']),
        			'ESPECIALIDAD' => strtoupper($data[$i]['Especialidad']),
        			'SERVICIO' => strtoupper($data[$i]['servicio']),
        			'NRO HISTORIA' => $data[$i]['NroHistoriaClinica'],
        			'PACIENTE' => strtoupper($data[$i]['nombres']),
        			'FECHA SOLICITUD' => $data[$i]['fechasolicitud'],
        			'TIPO PACIENTE' => strtoupper($data[$i]['TipoPaciente']),
        			'TIPO CITA' => strtoupper($data[$i]['TipoCita']),
        			'HORA INICIO' => $data[$i]['horainicio'],
        			'CONSERJE' => strtoupper($data[$i]['conserje']),
        			'ARCHIVERO' => strtoupper($data[$i]['archivero']),
        			'TECNICA' => strtoupper($data[$i]['tecnica']),
        			'MEDICO' => strtoupper($data[$i]['Medico']),
        			'SEGURO' => strtoupper($data[$i]['financiamiento']),
        			'DIGITO TERMINAL' => $data[$i]['digitoterminal'],
        			'DESTINO' => strtoupper($data[$i]['destino']),
        			'ESTADO' => strtoupper($data[$i]['estado']),
        			'UBICACION' => strtoupper($data[$i]['Ubicacion']),
        			'ULTIMO MOVIMIENTO' => strtoupper($data[$i]['ultimomov']),
        			'TURNO' => strtoupper($data[$i]['TURNO'])
        		);
        	}
        	return response()->json(
        		[
        			'data' => $response
        		]); 
        } else { return response()->json(['data' => 'sindatos']); }
    }

    public function Reporte_Conserjeria_Excel($turno, $fecha)
    {
        $Archivo = new Archivo();
        $data = $Archivo->Reporte_Archivo_Conserjeria($turno, $fecha);

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

        $spreadsheet = new Spreadsheet();  /*----Spreadsheet object-----*/
        $Excel_writer = new Xls($spreadsheet);  /*----- Excel (Xls) Object*/
        $spreadsheet->setActiveSheetIndex(0);
        $activeSheet = $spreadsheet->getActiveSheet();

        $activeSheet->setTitle("Reporte de Ingresos de Almacen");

        $activeSheet->getStyle('A1:S1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'RUTA')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'ESPECIALIDAD')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'SERVICIO')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'NRO HISTORIA')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'PACIENTE')->getStyle('D1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'FECHA SOLICITUD')->getStyle('E1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'TIPO PACIENTE')->getStyle('F1')->getFont()->setBold(true);
        $activeSheet->setCellValue('G1', 'TIPO CITA')->getStyle('G1')->getFont()->setBold(true);
        $activeSheet->setCellValue('H1', 'HORA INICIO')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('I1', 'CONSERJE')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'ARCHIVERO')->getStyle('J1')->getFont()->setBold(true);
        $activeSheet->setCellValue('K1', 'TECNICA')->getStyle('K1')->getFont()->setBold(true);
        $activeSheet->setCellValue('L1', 'MEDICO')->getStyle('L1')->getFont()->setBold(true);
        $activeSheet->setCellValue('M1', 'SEGURO')->getStyle('M1')->getFont()->setBold(true);
        $activeSheet->setCellValue('N1', 'DIGITO TERMINAL')->getStyle('N1')->getFont()->setBold(true);
        $activeSheet->setCellValue('O1', 'DESTINO')->getStyle('O1')->getFont()->setBold(true);
        $activeSheet->setCellValue('P1', 'ESTADO')->getStyle('P1')->getFont()->setBold(true);
        $activeSheet->setCellValue('Q1', 'UBICACION')->getStyle('Q1')->getFont()->setBold(true);
        $activeSheet->setCellValue('R1', 'ULTIMO MOVIMIENTO')->getStyle('R1')->getFont()->setBold(true);
        $activeSheet->setCellValue('S1', 'TURNO')->getStyle('S1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:S1");

        //Ingresando datos
        $j = 2;
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, strtoupper($data[$i]['Ruta']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, strtoupper($data[$i]['Especialidad']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, strtoupper($data[$i]['servicio']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['NroHistoriaClinica'] );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, strtoupper($data[$i]['nombres']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['fechasolicitud'] );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, strtoupper($data[$i]['TipoPaciente']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, strtoupper($data[$i]['TipoCita']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['horainicio'] );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, strtoupper($data[$i]['conserje']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, strtoupper($data[$i]['archivero']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, strtoupper($data[$i]['tecnica']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$j, strtoupper($data[$i]['Medico']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$j, strtoupper($data[$i]['financiamiento']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$j, $data[$i]['digitoterminal'] );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$j, strtoupper($data[$i]['destino']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$j, strtoupper($data[$i]['estado']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$j, strtoupper($data[$i]['Ubicacion']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$j, strtoupper($data[$i]['ultimomov']) );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$j, strtoupper($data[$i]['TURNO']) );
            $j++;
        }

        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('M')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('N')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('O')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('P')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('Q')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('R')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('S')->setAutoSize(true);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReporteConserjeria.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }
}
