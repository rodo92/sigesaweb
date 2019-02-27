<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Caja;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Codedge\Fpdf\Fpdf\Fpdf;

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
                    'DEVUELTO'					=> $data[$i]['Devuelto'],
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
        $j = 2;
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

            $activeSheet->getStyle("A".$j.":J".$j)->applyFromArray($styleCell);
            $j++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ResumenPorCajero.xls"'); /*-- $filename is  xls filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");

    }

    public function reporte_resumen_por_cajas_excel($fechainicio, $fechafin, $idcaja)
    {
    	$Caja = new Caja();
        $data = $Caja->Reporte_resumen_por_cajas($fechainicio, $fechafin, $idcaja);

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

        $activeSheet->setTitle("Resumen Por Caja");

        $activeSheet->getStyle('A1:L1')->applyFromArray($styleArray);

        //Cabeceras de excel
        $activeSheet->setCellValue('A1', 'CAJERO')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('B1', 'TURNO')->getStyle('A1')->getFont()->setBold(true);
        $activeSheet->setCellValue('C1', 'FECHA COBRANZA')->getStyle('B1')->getFont()->setBold(true);
        $activeSheet->setCellValue('D1', 'CONTADO')->getStyle('F1')->getFont()->setBold(true);
        $activeSheet->setCellValue('E1', 'REBAJA')->getStyle('G1')->getFont()->setBold(true);
        $activeSheet->setCellValue('F1', 'EXONERADO')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('G1', 'TOTAL DEV')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('H1', 'DEVOLUCION')->getStyle('H1')->getFont()->setBold(true);
        $activeSheet->setCellValue('I1', 'ANULADO')->getStyle('I1')->getFont()->setBold(true);
        $activeSheet->setCellValue('J1', 'TOTAL')->getStyle('J1')->getFont()->setBold(true);

        //Filtro
        $activeSheet->setAutoFilter("A1:J1");

        //Ingresando datos
        $j = 2;
        for ($i = 0; $i < count($data); $i++) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A'.$j, $data[$i]['Empleado']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B'.$j, $data[$i]['Turno']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C'.$j, $data[$i]['FechaCobranza']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D'.$j, $data[$i]['Contado']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E'.$j, $data[$i]['Rebaja']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F'.$j, $data[$i]['Exonerado']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('G'.$j, $data[$i]['TotalDev']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H'.$j, $data[$i]['DEVOLUCION']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I'.$j, $data[$i]['Anulado']);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J'.$j, $data[$i]['Total']);

            $activeSheet->getStyle("A".$j.":J".$j)->applyFromArray($styleCell);
            $j++;
        }

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ResumenPorCaja.xls"'); /*-- $filename is  xls filename ---*/
        header('Cache-Control: max-age=0');
        return $Excel_writer->save("php://output");
    }

    public function reporte_resumen_por_cajeros_pdf($fechainicio, $fechafin, $idcajero)
    {

    	$Caja = new Caja();
        $data = $Caja->Reporte_resumen_por_cajeros($fechainicio, $fechafin, $idcajero);

    	$this->fpdf = new Fpdf();
        $this->fpdf->SetAutoPageBreak(true, 10);
        $this->fpdf->AddPage('L','A4');
        	
        $this->cabecera("RESUMEN POR CAJERO");
		$this->detalles_resumen_por_cajeros($data);
		

        $this->fpdf->Output();
        $this->fpdf->Close();
    }

    public function reporte_resumen_por_cajas_pdf($fechainicio, $fechafin, $idcaja)
    {

    	$Caja = new Caja();
        $data = $Caja->Reporte_resumen_por_cajas($fechainicio, $fechafin, $idcaja);

    	$this->fpdf = new Fpdf();
        $this->fpdf->SetAutoPageBreak(true, 10);
        $this->fpdf->AddPage('L','A4');
        	
        $this->cabecera("RESUMEN POR CAJA");
		$this->detalles_resumen_por_caja($data);
		

        $this->fpdf->Output();
        $this->fpdf->Close();
    }

    public function cabecera($nombre)
    {
    	$this->fpdf->Image('svg/Logos/logo-factura.jpg',10,8,33);
		$this->fpdf->SetFont('Arial','B',9);
		$this->fpdf->Cell(277,1,$nombre,0,1,'C');
		$this->fpdf->Ln(5 );
		$this->fpdf->Cell(277,1,'HOSPITAL NACIONAL ARZOBISPO LOAYZA',0,1,'C');
		$this->fpdf->Ln(2);
		$this->fpdf->SetFont('Arial','',7);
		$this->fpdf->Cell(277,1,utf8_decode(strtoupper('Av. Alfonso Ugarte 848 - Cercado de Lima')),0,1,'C');
		$this->fpdf->Ln(2);
		$this->fpdf->Cell(277,1,utf8_decode(strtoupper('Prov. de Lima - Lima - Peru')),0,1,'C');
		$this->fpdf->SetFont('Arial','B',8);
		$this->fpdf->Ln(10);
    }

    public function detalles_resumen_por_cajeros($data)
    {
	    $this->fpdf->Cell(70,5,'CAJERO',1,0,'C');
        $this->fpdf->Cell(25,5,'F. COBRANZA',1,0,'C');
        $this->fpdf->Cell(30,5,'NRO. FACT. INICIAL',1,0,'C');
        $this->fpdf->Cell(30,5,'NRO. FACT. FINAL',1,0,'C');
        $this->fpdf->Cell(20,5,'CONTADO',1,0,'C');
        $this->fpdf->Cell(20,5,'REBAJA',1,0,'C');
        $this->fpdf->Cell(20,5,'EXONERADO',1,0,'C');
        $this->fpdf->Cell(20,5,'ANULADO',1,0,'C');
        $this->fpdf->Cell(20,5,'DEVUELTO',1,0,'C');
        $this->fpdf->Cell(20,5,'TOTAL',1,0,'C');
        $this->fpdf->Ln(5);
        $this->fpdf->SetFont('Arial','',8);


        $nextpage = 1;
        for ($i = 0; $i < count($data); $i++) {

            $this->fpdf->Cell(70,5,utf8_decode($data[$i]['Cajero']),1,0,'L');
            $this->fpdf->Cell(25,5,$data[$i]['FechaCobranza'],1,0,'C');
            $this->fpdf->Cell(30,5,$data[$i]['NroFacturaInicial'],1,0,'C');
            $this->fpdf->Cell(30,5,$data[$i]['NroFacturaFinal'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Contado'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Rebaja'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Exonerado'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Anulado'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Devuelto'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Total'],1,0,'C');
            $this->fpdf->Ln(5);
            $nextpage++;
        }
    }

    public function detalles_resumen_por_caja($data)
    {
    	$this->fpdf->Cell(70,5,'CAJERO',1,0,'C');
    	$this->fpdf->Cell(25,5,'TURNO',1,0,'C');
        $this->fpdf->Cell(25,5,'F. COBRANZA',1,0,'C');
        $this->fpdf->Cell(30,5,'CONTADO',1,0,'C');
        $this->fpdf->Cell(30,5,'REBAJA',1,0,'C');
        $this->fpdf->Cell(20,5,'EXONERADO',1,0,'C');
        $this->fpdf->Cell(20,5,'TOTAL DEV',1,0,'C');
        $this->fpdf->Cell(20,5,'DEVOLUCION',1,0,'C');
        $this->fpdf->Cell(20,5,'ANULADO',1,0,'C');
        $this->fpdf->Cell(20,5,'TOTAL',1,0,'C');	
        $this->fpdf->Ln(5);
        $this->fpdf->SetFont('Arial','',8);


        $nextpage = 1;
        for ($i = 0; $i < count($data); $i++) {

            $this->fpdf->Cell(70,5,utf8_decode($data[$i]['Empleado']),1,0,'L');
            $this->fpdf->Cell(25,5,$data[$i]['Turno'],1,0,'C');
            $this->fpdf->Cell(25,5,$data[$i]['FechaCobranza'],1,0,'C');
            $this->fpdf->Cell(30,5,$data[$i]['Contado'],1,0,'C');
            $this->fpdf->Cell(30,5,$data[$i]['Rebaja'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Exonerado'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['TotalDev'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['DEVOLUCION'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Anulado'],1,0,'C');
            $this->fpdf->Cell(20,5,$data[$i]['Total'],1,0,'C');
            $this->fpdf->Ln(5);
            $nextpage++;
        }
    }
}
