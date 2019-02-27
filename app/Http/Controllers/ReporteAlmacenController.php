<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Farmacia;
use WebSigesa\Sistema;
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

    public function reporte_ingresos_almacen(Request $request)
    {
        $messages = [
            'inicioia.required'       => 'Ingrese una fecha de inicio.',
            'finia.required'          => 'Ingrese una fecha fin.',
            'idProveedor.required'  => 'Seleccione un laboratorio.'
        ];

        $rules = [
            'inicioia'        => 'required',
            'finia'           => 'required',
            'idProveedor'   => 'required'
        ];

        $this->validate($request,$rules,$messages);
        
        list($dia, $mes, $anio) = explode("/", $request->inicioia);
        $fechainicio = $anio."-".$mes."-".$dia.' 00:00:00.000';

        list($dia, $mes, $anio) = explode("/", $request->finia);
        $fechafin = $anio."-".$mes."-".$dia.' 23:59:59.000';

        $Sistema = new Sistema();
        $proveedores = $Sistema->Mostrar_Provedores();

        $farmacia = new Farmacia();

        if (trim($request->idProveedor) == 0) {
            $data = $farmacia->Reporte_Almacen_Ingresos_Almacen($fechainicio, $fechafin, '0');
            
        }

        else {
            for ($i=0; $i < count($proveedores); $i++) { 
                if ($proveedores[$i]['RUC'] == trim($request->idProveedor)) {
                    $id_proveedor = $proveedores[$i]['IDPROVEEDOR'];
                }
            }
            $data = $farmacia->Reporte_Almacen_Ingresos_Almacen($fechainicio, $fechafin, $id_proveedor);
            
        }
        return response()->json(['data' => $data]);
    }

    public function reporte_ingresos_almacen_excel($fechainicio, $fechafin, $ruc)
    {
        $fechainicio = $fechainicio . ' 00:00:00.000';
        $fechafin = $fechafin . ' 23:59:59.000';

        $farmacia = new Farmacia();

        if (trim($ruc) == 0) {
            $data = $farmacia->Reporte_Almacen_Ingresos_Almacen($fechainicio, $fechafin, '0');
            //echo 'soy o';exit;
        }

        else {

            $Sistema = new Sistema();
            $proveedores = $Sistema->Mostrar_Provedores();
            for ($i=0; $i < count($proveedores); $i++) { 
                if ($proveedores[$i]['RUC'] == trim($ruc)) {
                    $id_proveedor = $proveedores[$i]['IDPROVEEDOR'];
                }
            }
            $data = $farmacia->Reporte_Almacen_Ingresos_Almacen($fechainicio, $fechafin, $id_proveedor);
            //echo '<pre>';print_r($data);exit();
        }

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

        $activeSheet->getStyle('A1:I1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'FECHA')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'MOV. NUMERO')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'ORDEN DE COMPRA')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'LABORATORIO')->getStyle('D1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'N°ENTREGA')->getStyle('E1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'LICITACION')->getStyle('F1')->getFont()->setBold(true);
        $activeSheet->setCellValue('G1', 'CODIGO SISMED')->getStyle('G1')->getFont()->setBold(true);
        $activeSheet->setCellValue('H1', 'DESCRIPCION')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('I1', 'CANTIDAD')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'OBSERVACIONES')->getStyle('J1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:J1");

        //Ingresando datos
        $j = 2;
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['FECHA']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['MOVNUMERO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['ORDEN DE COMPRA']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['LABORATORIO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['NRO DE ENTREGA']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['LICITACION']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, $data[$i]['CODIGO SISMED']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['DESCRIPCIÓN DE PRODUCTO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, $data[$i]['CANTIDAD']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, $data[$i]['OBSERVACIONES']);

            $activeSheet->getStyle("A".$j.":J".$j)->applyFromArray($styleCell);
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

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReportedeIngresos.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }

    public function reporte_traslados_excel($fechainicio, $fechafin, $almacenid)
    {
        $fechainicio = $fechainicio . ' 00:00:00.000';
        $fechafin = $fechafin . ' 23:59:59.000';
        $farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Traslado($fechainicio, $fechafin, $almacenid);

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
        $activeSheet->setCellValue('I1', 'CANTIDAD')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'LOTE')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('K1', 'FV')->getStyle('J1')->getFont()->setBold(true);
        $activeSheet->setCellValue('L1', 'RS')->getStyle('K1')->getFont()->setBold(true);
        $activeSheet->setCellValue('L1', 'OBSERVACIONES')->getStyle('L1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:L1");

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
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$j, $data[$i]['OBSERVACIONES']);

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
