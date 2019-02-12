<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Farmacia;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class ReporteFarmaciaController extends Controller
{
    public function reporte_venta_producto_resumen($fechainicio, $fechafin, $idAlmacen, $insumomedicamento, $movinicio=null, $movfin=null)
    {
        $farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Venta_Producto_Resumen($fechainicio, $fechafin, $idAlmacen, $insumomedicamento, $movinicio, $movfin);
        // echo count($data); exit;
        if (count($data) > 0) { 
            
            for ($i=0; $i < count($data); $i++) { 
                $response[] = array(
                    'IDPRODUCTO'                    => $data[$i]['id_producto'],
                    'CODIGOSISMED'                  => $data[$i]['codigo_sismed'],
                    'PRODUCTO'                      => $data[$i]['producto'],
                    'CANTIDADVENTAS'                => $data[$i]['cv'],
                    'CONSULTAEXTERNA'               => $data[$i]['ce'],
                    'HOSPITALIZACION'               => $data[$i]['ho'],
                    'EMERGENCIA'                    => $data[$i]['em'],
                    'PACIENTEEXTERNO'               => $data[$i]['ext'],
                    'PARTICULAR'                    => $data[$i]['cash'],
                    'SIS'                           => $data[$i]['sis'],
                    'SOAT'                          => $data[$i]['soat'],
                    'PENDIENTE'                     => $data[$i]['pnd'],
                    'EXONERADO'                     => $data[$i]['exo'],
                    'DONACION'                      => $data[$i]['do'],
                    'INTERVENCIONSANITARIA'         => $data[$i]['is'],
                    'STOCK'                         => $data[$i]['stock'],
                    'CANTIDADFACTURADA'             => $data[$i]['cant_factura'],
                    'TOTAL'                         => $data[$i]['total'],
                    'DEVOLUCIONES'                  => $data[$i]['dv'],
                    'CONSULTAEXTERNADEVOLUCIONES'   => $data[$i]['ce_d'],
                    'HOSPITALIZACIONDEVOLUCIONES'   => $data[$i]['ho_d'],
                    'EMERGENCIADEVOLUCIONES'        => $data[$i]['em_d'],
                    'CANTVENTASMENOSDEVOLUCIONES'   => $data[$i]['cv-dv']
                );
            }
            return response()->json(
                [
                    'data' => $response
                ]); 
        } else { return response()->json(['data' => 'sindatos']); }
    }

    public function reporte_entradas_salidas_documentos($inicio, $fin, $almacenid, $movtipo)
    {
    	$farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Entrada_Salida_Documentos($inicio, $fin, $almacenid, $movtipo);

        if (count($data) > 0) { 
        	$total = number_format(0,2,'.',' ');

        	for ($i=0; $i < count($data); $i++) { 
        		$response[] = array(
        			'FECHA' => $data[$i]['FECHA'],
        			'MOVTIPO' => $data[$i]['MOVTIPO'],
        			'MOVNUMERO' => $data[$i]['MOVNUMERO'],
        			'USUARIO' => $data[$i]['USUARIO'],
        			'DOCUMENTONUMERO' => $data[$i]['DOCUMENTONUMERO'],
        			'DOCUMENTO' => $data[$i]['DOCUMENTO'],
        			'ORIGEN' => $data[$i]['ORIGEN'],
        			'OBSERVACIONES' => $data[$i]['OBSERVACIONES'],
        			'DESTINO' => $data[$i]['DESTINO'],
        			'ESTADO' => $data[$i]['ESTADO'],
        			'TOTAL' => number_format($data[$i]['TOTAL'],2,'.',' ')
        		);

        		$total = $total + $data[$i]['TOTAL'];
        	}
        	return response()->json(
        		[
        			'data' => $response,
        			'total' => number_format(trim($total),2,'.',' ')
        		]); 
        } else { return response()->json(['data' => 'sindatos']); }        
    }

     public function reporte_por_usuario($inicio, $fin, $almacenid)
    {
    	$farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Reporte_Por_Usuario($inicio, $fin, $almacenid);

        if (count($data) > 0) { 
        	for ($i=0; $i < count($data); $i++) { 

        		$estado = 'Anulado';
        		if($data[$i]['Estado'] == '1') { $estado = 'Activo'; }

        		$response[] = array(
        			'MOVNUMERO' => $data[$i]['movnumero'],
        			'FECHA' => $data[$i]['Fecha'],
        			// 'IDALMACEN' => $data[$i]['Idalmacen'],
        			'ALMACEN' => strtoupper($data[$i]['Almacen']),
        			// 'IdUsuario' => $data[$i]['idUsuario'],
        			'USUARIO' => strtoupper($data[$i]['Usuario']),
        			'ESTADO' => strtoupper($estado),
        			'CODIGO' => $data[$i]['Codigo'],
        			'PRODUCTO' => strtoupper($data[$i]['Producto']),
        			'CANTIDAD' => $data[$i]['Cantidad'],
        			'PRECIO' => number_format($data[$i]['Precio'],2,'.',' '),
        			'TOTAL' => number_format($data[$i]['Total'],2,'.',' ')
        		);
        	}
        	return response()->json(
        		[
        			'data' => $response,
        		]); 
        } else { return response()->json(['data' => 'sindatos']); }        
    }

    public function reporte_por_usuario_excel($inicio, $fin, $almacenid)
    {
        $farmacia = new Farmacia();
		$data = $farmacia->Reporte_Almacen_Reporte_Por_Usuario($inicio, $fin, $almacenid);

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

        $activeSheet->getStyle('A1:J1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'MOVNUMERO')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'FECHA')->getStyle('B1')->getFont()->setBold(true);
        // $activeSheet->setCellValue('1', 'IDALMACEN')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'ALMACEN')->getStyle('D1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'USUARIO')->getStyle('E1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'ESTADO')->getStyle('F1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'CODIGO')->getStyle('G1')->getFont()->setBold(true);
        $activeSheet->setCellValue('G1', 'PRODUCTO')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('H1', 'CANTIDAD')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('I1', 'PRECIO')->getStyle('J1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'TOTAL')->getStyle('K1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:J1");

        //Ingresando datos
        $j = 2;
        for ($i = 0; $i < count($data); $i++) {

        	$estado = 'Anulado';
        	if($data[$i]['Estado'] == '1') { $estado = 'Activo'; }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['movnumero']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['Fecha']);
            // $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['Idalmacen']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, strtoupper($data[$i]['Almacen']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, strtoupper($data[$i]['Usuario']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, strtoupper($estado));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['Codigo']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, strtoupper($data[$i]['Producto']));
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['Cantidad']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, number_format($data[$i]['Precio'],2,'.',' ') );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, number_format($data[$i]['Total'],2,'.',' ') );

            $activeSheet->getStyle("A".$j.":J".$j)->applyFromArray($styleCell);
            $activeSheet->getStyle("I".$j)->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("J".$j)->getNumberFormat()->setFormatCode('#,##0.00');
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

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReportePorUsuario.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }

    public function reporte_entradas_salidas_documentos_excel($inicio, $fin, $almacenid, $movtipo)
    {
        $farmacia = new Farmacia();
		$data = $farmacia->Reporte_Almacen_Entrada_Salida_Documentos($inicio, $fin, $almacenid, $movtipo);

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

        $activeSheet->getStyle('A1:K1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'FECHA')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'MOVTIPO DE COMPRA')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'MOVNUMERO')->getStyle('C1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'USUARIO')->getStyle('D1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'DOCUMENTONUMERO')->getStyle('E1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'DOCUMENTO SISMED')->getStyle('F1')->getFont()->setBold(true);
        $activeSheet->setCellValue('G1', 'ORIGEN')->getStyle('G1')->getFont()->setBold(true);
        $activeSheet->setCellValue('H1', 'OBSERVACIONES')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('I1', 'DESTINO')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'ESTADO')->getStyle('J1')->getFont()->setBold(true);
        $activeSheet->setCellValue('K1', 'TOTAL')->getStyle('K1')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A1:K1");

        //Ingresando datos
        $j = 2;
        $total = number_format(0,2,'.',' ');
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['FECHA']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['MOVTIPO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['MOVNUMERO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['USUARIO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['DOCUMENTONUMERO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['DOCUMENTO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, $data[$i]['ORIGEN']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['OBSERVACIONES']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, $data[$i]['DESTINO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, $data[$i]['ESTADO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, number_format($data[$i]['TOTAL'],2,'.',' ') );

            $activeSheet->getStyle("A".$j.":K".$j)->applyFromArray($styleCell);
            $activeSheet->getStyle("K".$j)->getNumberFormat()->setFormatCode('#,##0.00');
            $j++;
            $total = $total + $data[$i]['TOTAL'];
        }

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, 'TOTAL DE ACTIVOS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, 'MONTO TOTAL SIN ANULADOS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, number_format($total,2,'.',' '));

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

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReportedeESDocumentos.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }

    public function reporte_venta_producto_resumen_excel($fechainicio, $fechafin, $idAlmacen, $insumomedicamento, $movinicio=null, $movifin=null)
    {
        $farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Venta_Producto_Resumen($fechainicio, $fechafin, $idAlmacen, $insumomedicamento, $movinicio, $movifin);
        // print_r($data);exit();
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

        $styleArrayTitulo = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFE8E5E5'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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

        $activeSheet->setTitle("Reporte Ventas Producto");

        $activeSheet->getStyle('A4:V4')->applyFromArray($styleArray);
        $activeSheet->getStyle('B1:C1')->applyFromArray($styleArrayTitulo);
        $activeSheet->getStyle('B2:C2')->applyFromArray($styleArrayTitulo);

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B1', 'DESDE: ' . $fechainicio . ' HASTA: ' . $fechafin)->getStyle('B1')->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B2', 'NÚMERO DE MOVIMIENTO INICIAL : ' . $movinicio . ' NÚMERO DE MOVIMIENTO FINAL: ' . $movifin)->getStyle('B2')->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('B1:C1');
        $spreadsheet->setActiveSheetIndex(0)->mergeCells('B2:C2');

        //Cabeceras de excel
        $activeSheet->setCellValue('A4', 'CODIGOSISMED')->getStyle('A4')->getFont()->setBold(true);
        $activeSheet->setCellValue('B4', 'PRODUCTO DE COMPRA')->getStyle('B4')->getFont()->setBold(true);
        $activeSheet->setCellValue('C4', 'CANTIDADVENTAS')->getStyle('C4')->getFont()->setBold(true);
        $activeSheet->setCellValue('D4', 'CONSULTAEXTERNA')->getStyle('D4')->getFont()->setBold(true);
        $activeSheet->setCellValue('E4', 'HOSPITALIZACION')->getStyle('E4')->getFont()->setBold(true);
        $activeSheet->setCellValue('F4', 'EMERGENCIA SISMED')->getStyle('F4')->getFont()->setBold(true);
        $activeSheet->setCellValue('G4', 'PACIENTEEXTERNO')->getStyle('G4')->getFont()->setBold(true);
        $activeSheet->setCellValue('H4', 'PARTICULAR')->getStyle('H4')->getFont()->setBold(true);
        $activeSheet->setCellValue('I4', 'SIS')->getStyle('I4')->getFont()->setBold(true);
        $activeSheet->setCellValue('J4', 'SOAT')->getStyle('J4')->getFont()->setBold(true);
        $activeSheet->setCellValue('K4', 'PENDIENTE')->getStyle('K4')->getFont()->setBold(true);
        $activeSheet->setCellValue('L4', 'EXONERADO SISMED')->getStyle('L4')->getFont()->setBold(true);
        $activeSheet->setCellValue('M4', 'DONACION')->getStyle('M4')->getFont()->setBold(true);
        $activeSheet->setCellValue('N4', 'INTERVENCIONSANITARIA')->getStyle('N4')->getFont()->setBold(true);
        $activeSheet->setCellValue('O4', 'STOCK')->getStyle('O4')->getFont()->setBold(true);
        $activeSheet->setCellValue('P4', 'CANTIDADFACTURADA')->getStyle('P4')->getFont()->setBold(true);
        $activeSheet->setCellValue('Q4', 'TOTAL')->getStyle('Q4')->getFont()->setBold(true);
        $activeSheet->setCellValue('R4', 'DEVOLUCIONES')->getStyle('R4')->getFont()->setBold(true);
        $activeSheet->setCellValue('S4', 'CONSULTAEXTERNADEVOLUCIONES')->getStyle('S4')->getFont()->setBold(true);
        $activeSheet->setCellValue('T4', 'HOSPITALIZACIONDEVOLUCIONES')->getStyle('T4')->getFont()->setBold(true);
        $activeSheet->setCellValue('U4', 'EMERGENCIADEVOLUCIONES')->getStyle('U4')->getFont()->setBold(true);
        $activeSheet->setCellValue('V4', 'CANTVENTASMENOSDEVOLUCIONES')->getStyle('V4')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A4:V4");

        //Ingresando datos
        $j = 5;
        // $total = number_format(0,2,'.',' ');
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['codigo_sismed']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['producto']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['cv']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['ce']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['ho']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['em']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, $data[$i]['ext']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['cash']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, $data[$i]['sis']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, $data[$i]['soat']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, $data[$i]['pnd']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$j, $data[$i]['exo']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$j, $data[$i]['do']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$j, $data[$i]['is']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$j, number_format($data[$i]['stock'],2,'.',' ') );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$j, $data[$i]['cant_factura']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$j, number_format($data[$i]['total'],2,'.',' ') );
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$j, $data[$i]['dv']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$j, $data[$i]['ce_d']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('T'.$j, $data[$i]['ho_d']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('U'.$j, $data[$i]['em_d']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('V'.$j, $data[$i]['dv']);

            $activeSheet->getStyle("A".$j.":V".$j)->applyFromArray($styleCell);
            // $activeSheet->getStyle("O".$j)->getNumberFormat()->setFormatCode('#,##0.00');
            // $activeSheet->getStyle("Q".$j)->getNumberFormat()->setFormatCode('#,##0.00');
            $j++;
            // $total = $total + $data[$i]['TOTAL'];
        }

        /*$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, 'TOTAL DE ACTIVOS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, 'MONTO TOTAL SIN ANULADOS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, number_format($total,2,'.',' '));*/

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
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReporteVenProd.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }

    public function reportesaldosxalmacen()
    {
        $farmacia = new Farmacia();
        $data = $farmacia->Reporte_Almacen_Saldos_Por_Almacen();

        // print_r($data);exit();
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

        $styleArrayTitulo = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFE8E5E5'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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

        $activeSheet->setTitle("Reporte Saldos Por Almacen");

        $activeSheet->setCellValue('B2','SALDOS POR ALMACEN');        

        $activeSheet->setCellValue('B4','FECHA Y HORA:    '. date("d-m-Y H:i:s"));

        //Cabeceras de excel
        $activeSheet->setCellValue('A6', 'CODIGOSISMED')->getStyle('A6')->getFont()->setBold(true);
        $activeSheet->setCellValue('B6', 'NOMBRE')->getStyle('B6')->getFont()->setBold(true);
        $activeSheet->setCellValue('C6', 'ALMACEN - SISMED')->getStyle('C6')->getFont()->setBold(true);
        $activeSheet->setCellValue('D6', 'Almacen - Estrategias')->getStyle('D6')->getFont()->setBold(true);
        $activeSheet->setCellValue('E6', 'Almacen - Especiales')->getStyle('E6')->getFont()->setBold(true);
        $activeSheet->setCellValue('F6', 'Almacen - Donaciones')->getStyle('F6')->getFont()->setBold(true);
        $activeSheet->setCellValue('G6', 'Almacen - Galenicos')->getStyle('G6')->getFont()->setBold(true);
        $activeSheet->setCellValue('H6', 'Almacen -Bajas')->getStyle('H6')->getFont()->setBold(true);
        $activeSheet->setCellValue('I6', 'Far. Ambulatoria')->getStyle('I6')->getFont()->setBold(true);
        $activeSheet->setCellValue('J6', 'Far. Emergencia')->getStyle('J6')->getFont()->setBold(true);
        $activeSheet->setCellValue('K6', 'Far. Hospi. Donaciones')->getStyle('K6')->getFont()->setBold(true);
        $activeSheet->setCellValue('L6', 'Far. Estrategias')->getStyle('L6')->getFont()->setBold(true);
        $activeSheet->setCellValue('M6', 'Far. Hospitalizacion')->getStyle('M6')->getFont()->setBold(true);
        $activeSheet->setCellValue('N6', 'Far. Donaciones')->getStyle('N6')->getFont()->setBold(true);
        $activeSheet->setCellValue('O6', 'Far. Materno')->getStyle('O6')->getFont()->setBold(true);
        $activeSheet->setCellValue('P6', 'Almacen Devoluciones')->getStyle('P6')->getFont()->setBold(true);
        $activeSheet->setCellValue('Q6', 'Far. Materno Donaciones')->getStyle('Q6')->getFont()->setBold(true);
        $activeSheet->setCellValue('R6', 'Far. Galénicos')->getStyle('R6')->getFont()->setBold(true);
        $activeSheet->setCellValue('S6', 'Far. Galénicos Estrategias')->getStyle('S6')->getFont()->setBold(true);
        $activeSheet->setCellValue('T6', 'Far. SOP6')->getStyle('T6')->getFont()->setBold(true);
        $activeSheet->setCellValue('U6', 'Far. SOP6 Donaciones')->getStyle('U6')->getFont()->setBold(true);
        $activeSheet->setCellValue('V6', 'Otros Servicios del Hospital')->getStyle('V6')->getFont()->setBold(true);
        $activeSheet->setCellValue('W6', 'Far. Casos Especiales')->getStyle('W6')->getFont()->setBold(true);

        // Filtro
        $activeSheet->setAutoFilter("A4:V4");

        //Ingresando datos
        $j = 7;
        // $total = number_format(0,2,'.',' ');
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['IDPRODUCTO']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['NOMBRE']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['ALMACEN - SISMED']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['Almacen - Estrategias']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['Almacen - Especiales']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['Almacen - Donaciones']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, $data[$i]['Almacen - Galenicos']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['Almacen -Bajas']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, $data[$i]['Far. Ambulatoria']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, $data[$i]['Far. Emergencia']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, $data[$i]['Far. Hospi. Donaciones']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('L'.$j, $data[$i]['Far. Estrategias']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('M'.$j, $data[$i]['Far. Hospitalizacion']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('N'.$j, $data[$i]['Far. Donaciones']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('O'.$j, $data[$i]['Far. Materno']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('P'.$j, $data[$i]['Almacen Devoluciones']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q'.$j, $data[$i]['Far. Materno Donaciones']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('R'.$j, $data[$i]['Far. Galénicos']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('S'.$j, $data[$i]['Far. Galénicos Estrategias']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('T'.$j, $data[$i]['Far. SOP6']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('U'.$j, $data[$i]['Far. SOP6 Donaciones']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('V'.$j, $data[$i]['Otros Servicios del Hospital']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('W'.$j, $data[$i]['Far. Casos Especiales']);

            $activeSheet->getStyle("A".$j.":V".$j)->applyFromArray($styleCell);
            // $activeSheet->getStyle("O".$j)->getNumberFormat()->setFormatCode('#,##0.00');
            // $activeSheet->getStyle("Q".$j)->getNumberFormat()->setFormatCode('#,##0.00');
            $j++;
            // $total = $total + $data[$i]['TOTAL'];
        }

        /*$spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, 'TOTAL DE ACTIVOS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, 'MONTO TOTAL SIN ANULADOS');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, '');
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('K'.$j, number_format($total,2,'.',' '));*/

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
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('T')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('U')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('V')->setAutoSize(true);
        $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('W')->setAutoSize(true);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ReporteSaldoAlmacen.xls"'); /*-- $filename is  xsl filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }
}
