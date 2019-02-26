<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Caja;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ReporteCajaController extends Controller
{
    public function reporte_resumen_por_cajeros($fechainicio, $fechafin, $idcajero)
    {
    	$Caja = new Caja();
        $data = $Caja->Reporte_resumen_por_cajeros($fechainicio, $fechafin, $idcajero);
    // echo count($data); exit;
        if (count($data) > 0) { 
            
            for ($i=0; $i < count($data); $i++) { 
                $response[] = array(
                    'CAJERO'                    => $data[$i]['Cajero'],
                    'FECHACOBRANZA'			    => $data[$i]['FechaCobranza'],
                    'NROFACTURAINCIAL'			=> $data[$i]['NroFacturaInicial'],
                    'NROFACTURAFINAL'			=> $data[$i]['NroFacturaFinal'],
                    'CONTADO'					=> $data[$i]['Contado'],
                    'REBAJA'					=> $data[$i]['Rebaja'],
                    'EXONERADO'					=> $data[$i]['Exonerado'],
                    'ANULADO'					=> $data[$i]['Anulado'],
                    'TOTAL'						=> $data[$i]['Total'],
                );
            }
            return response()->json(
                [
                    'data' => $response
                ]); 
        } else { return response()->json(['data' => 'sindatos']); }
    }

    public function reporte_resumen_por_cajas($fechainicio, $fechafin, $idcaja)
    {
    	$Caja = new Caja();
        $data = $Caja->Reporte_resumen_por_cajas($fechainicio, $fechafin, $idcaja);
    // echo count($data); exit;
        if (count($data) > 0) { 
            
            for ($i=0; $i < count($data); $i++) { 
                $response[] = array(
                    'CAJERO'                    => $data[$i]['Empleado'],
                    'TURNO'                    => $data[$i]['Turno'],
                    'FECHACOBRANZA'			    => $data[$i]['FechaCobranza'],
                    'CONTADO'					=> $data[$i]['Contado'],
                    'REBAJA'					=> $data[$i]['Rebaja'],
                    'EXONERADO'					=> $data[$i]['Exonerado'],
                    'TOTALDEV'					=> $data[$i]['TotalDev'],
                    'DEVOLUCION' 				=> $data[$i]['DEVOLUCION'],
                    'ANULADO'					=> $data[$i]['Anulado'],
                    'TOTAL'						=> $data[$i]['Total'],
                );
            }
            return response()->json(
                [
                    'data' => $response
                ]); 
        } else { return response()->json(['data' => 'sindatos']); }
    }

    public function reporte_resumen_por_cajeros_excel($fechainicio, $fechafin, $idcajero)
    {
    	$Caja = new Caja();
        $data = $Caja->Reporte_resumen_por_cajeros($fechainicio, $fechafin, $idcajero);

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

        $activeSheet->setTitle("Resumen Por Cajero");

        $activeSheet->getStyle('A1:L1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'CAJERO')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'FECHA COBRANZA')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'NRO FACTURA INICIAL')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'NRO FACTURA FINAL')->getStyle('D1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'CONTADO')->getStyle('F1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'REBAJA')->getStyle('G1')->getFont()->setBold(true);
        $activeSheet->setCellValue('G1', 'EXONERADO')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('H1', 'ANULADO')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('I1', 'DEVUELTO')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'TOTAL')->getStyle('J1')->getFont()->setBold(true);

        //Filtro
        $activeSheet->setAutoFilter("A1:J1");

        //Ingresando datos
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['Cajero']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['FechaCobranza']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['NroFacturaInicial']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['NroFacturaFinal']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['Contado']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['Rebaja']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, $data[$i]['Exonerado']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['Anulado']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, $data[$i]['Devuelto']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, $data[$i]['Total']);

            $activeSheet->getStyle("A".$j.":L".$j)->applyFromArray($styleCell);
            $j++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ResumenPorCajero.xls"'); /*-- $filename is  xls filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");

    }
}
