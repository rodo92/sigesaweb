<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Farmacia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ReporteAlmacenController extends Controller
{
    public function reporte_traslados(Request $request)
    {
    	$messages = [
            'inicio.required' 		=> 'Ingrese una fecha de inicio.',
            'fin.required' 			=> 'Ingrese una fecha fin.',
            'almacenid.required' 	=> 'Seleccione un almacen.'
        ];

        $rules = [
            'inicio' 	=> 'required',
            'fin'		=> 'required',
            'almacenid'	=> 'required'
        ];

        $this->validate($request,$rules,$messages);
        
        list($dia, $mes, $anio) = explode("/", $request->inicio);
        $fechainicio = $anio."-".$mes."-".$dia.' 00:00:00.000';

        list($dia, $mes, $anio) = explode("/", $request->fin);
        $fechafin = $anio."-".$mes."-".$dia.' 23:59:59.000';

        $farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Traslado($fechainicio, $fechafin, $request->almacenid);

        return response()->json(['data' => $data]);
    }

    public function reporte_traslados_excel($fechainicio, $fechafin, $almacenid)
    {
        $farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Traslado($fechainicio . ' 00:00:00.000', $fechafin' 23:59:59.000', $almacenid);

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

        $activeSheet->setTitle("Reporte de Traslados");

        $activeSheet->getStyle('A1:L1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'FECHA')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'NRO REGISTRO')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'ORIGEN')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'DESTINO')->getStyle('D1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'NRO DOCUMENTO')->getStyle('E1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'ESTADO')->getStyle('F1')->getFont()->setBold(true);
        $activeSheet->setCellValue('G1', 'SISMED')->getStyle('G1')->getFont()->setBold(true);
        $activeSheet->setCellValue('H1', 'DESCRIPCION')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('I1', 'CANTIDAD')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'LOTE')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('K1', 'FV')->getStyle('J1')->getFont()->setBold(true);
        $activeSheet->setCellValue('L1', 'RS')->getStyle('K1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:K1");

        //Ingresando datos
        $j = 2;
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['FECHA']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['NRO REGISTRO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['ORIGEN']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['DESTINO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['NRO DOCUMENTO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['ESTADO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, $data[$i]['SISMED']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['DESCRIPCION']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, $data[$i]['CANTIDAD']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, $data[$i]['LOTE']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, $data[$i]['FV']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$j, $data[$i]['RS']);

            $activeSheet->getStyle("A".$j.":L".$j)->applyFromArray($styleCell);
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

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReportedeTrasladosdesde.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }
}
