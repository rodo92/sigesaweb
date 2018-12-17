<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use WebSigesa\Caja;
use WebSigesa\Sistema;
use WebSigesa\Http\Controllers\QRController;

class PDFTicketController extends Controller
{
    private $idorden = '';
    private $fpdf;
    private $detalle;
    private $cabecera;
    private $nombre;
    private $data_proveedor;
    private $fecha_emision;
    private $html;

    public function generar($idorden)
    {
    	$this->idorden = $idorden;

        $Caja = new Caja();
        $this->detalle = $Caja->Obtener_Datos_Factura_Detalle_Id($this->idorden);
        $this->cabecera = $Caja->Obtener_Datos_Factura_Cabecera_Id($this->idorden);

        switch ($this->cabecera[0]['IdTipoComprobante']) {
        	case 'F':
        		$this->nombre = 'FACTURA';
        		break;
        	
        	case 'B':
        		$this->nombre = 'BOLETA';
        		break;
        }

        $this->fecha_emision = substr($this->cabecera[0]['FechaCobranza'], 0,10);
        list($anio, $mes, $dia) = explode("-", $this->fecha_emision);
        $this->fecha_emision = $dia."/".$mes."/".$anio;

    	$this->html = '<html><head><style type="text/css">
    					* {
    						font-family: "CALIBRI";
    						font-size: 12px;
    						margin: 0px;
    						padding: 0px;
    						/*height-max: 17cm;*/
    						width: 7cm;
    					}
    					@media print {
    						font-family: "CALIBRI";
    						font-size: 12px;
    						margin: 0px;
    						padding: 0px;
    						/*height-max: 17cm;*/
    						width: 7cm;
    					}
    					</style></head><body>';

		$this->cabecera_ticket();
		$this->datos_receptor();
		$this->detalle_factura();
		$this->pie_factura();
    	echo $this->html;
    }

    public function cabecera_ticket()
    {
    	$this->html .= '<div style="text-align: center;">';
    	$this->html .= '<h1>HOSPITAL NACIONAL ARZOBISPO LOAYZA</h1>';
    	$this->html .= '<p>AV. ALFONSO UGARTE 848 - TELEFONO 6144646</p>';
    	$this->html .= '<p>RUC: 20154996991</p>';
    	$this->html .= '<p>TELF:6144650 PARA CITAS DE LUNES - VIERNES</p>';
    	$this->html .= '<br><hr>';
    	$this->html .= 'BOLETA ELECTRÓNICA';
    	$this->html .= '<hr>';
    	$this->html .= '<div><br>';
    }

    public function datos_receptor()
    {
		$this->html .= '<div style="text-align: left;">';
		$this->html .= '<table>';
		$this->html .= '<tr>';
    	$this->html .= '<td>N° DOC: </td>';
    	$this->html .= '<td>' . $this->cabecera[0]['NroSerie'] . "-" . $this->cabecera[0]['NroDocumento'] . '</td>';
        $this->html .= '</tr>';
        $this->html .= '<tr>';
    	$this->html .= '<td>FECHA: </td>';
    	$this->html .= '<td>' . $this->fecha_emision . '</td>';
    	$this->html .= '</tr>';
    	$this->html .= '<tr>';
    	$this->html .= '<td>PACIENTE: </td>';
    	$this->html .= '<td colspan="2">' . $this->cabecera[0]['ApellidoPaterno'] . ' ' . $this->cabecera[0]['ApellidoMaterno'] . ' ' . $this->cabecera[0]['PrimerNombre'] . '</td>';
    	$this->html .= '</tr>';
    	$this->html .= '</table>';
    	$this->html .= '<div><BR>';
    }

    public function detalle_factura()
    {
    	$this->html .= '<div style="text-align: left;">';
		$this->html .= '<table style="width: 100%;">';
			$this->html .= '<tr>';
				$this->html .= '<th align="center" style="border-bottom:1px solid black; width: 15%;">COD</th>';
				$this->html .= '<th align="left" style="border-bottom:1px solid black; width: 45%;">DESCRIPCIÓN</th>';
				$this->html .= '<th align="center" style="border-bottom:1px solid black; width: 10%;">CANT</th>';
				$this->html .= '<th align="center" style="border-bottom:1px solid black; width: 15%;">PU</th>';
				$this->html .= '<th align="center" style="border-bottom:1px solid black; width: 15%;">PT</th>';
			$this->html .= '</tr>';

		for ($i=0; $i < count($this->detalle); $i++) {
			$this->html .= '<tr>';
				$this->html .= '<td align="center" style="width: 15%;">' . $this->detalle[$i]['Codigo'] . '</td>';
				$this->html .= '<td align="left" style="width: 45%;">' . $this->detalle[$i]['Descripcion'] . '</td>';
				$this->html .= '<td align="center" style="width: 10%;">' . $this->detalle[$i]['Cantidad'] . '</td>';
				$this->html .= '<td align="center" style="width: 15%;">' . number_format($this->detalle[$i]['ValorUnitario'],2,'.',' ') . '</td>';
				$this->html .= '<td align="center" style="width: 15%;">' . number_format($this->detalle[$i]['SubTotal'],2,'.',' ') . '</td>';
			$this->html .= '</tr>';
		}
    	
			$this->html .= '<tr>';
				$this->html .= '<td style="border-top:1px solid black; width: 15%;"></td>';
				$this->html .= '<td style="border-top:1px solid black; width: 45%;"></td>';
				$this->html .= '<td style="border-top:1px solid black; width: 10%;"></td>';
				$this->html .= '<td style="border-top:1px solid black; width: 15%;"></td>';
				$this->html .= '<td style="border-top:1px solid black; width: 15%;"></td>';
			$this->html .= '</tr>';
			$this->html .= '<tr>';
				$this->html .= '<td style="width: 15%;">&nbsp;</td>';
				$this->html .= '<td style="width: 45%;">&nbsp;</td>';
				$this->html .= '<td style="width: 10%;">&nbsp;</td>';
				$this->html .= '<td style="width: 15%;">&nbsp;</td>';
				$this->html .= '<td style="width: 15%;">&nbsp;</td>';
			$this->html .= '</tr>';
			$this->html .= '<tr>';
				$this->html .= '<td style="width: 15%;"></td>';
				$this->html .= '<td style="width: 45%;"></td>';
				$this->html .= '<td style="width: 10%;"></td>';
				$this->html .= '<td style="width: 15%;">SUB TOTAL</td>';
				$this->html .= '<td style="width: 15%;">' . number_format($this->cabecera[0]['Subtotal'],2,'.',' ') . '</td>';
			$this->html .= '</tr>';
			$this->html .= '<tr>';
				$this->html .= '<td style="width: 15%;"></td>';
				$this->html .= '<td style="width: 45%;"></td>';
				$this->html .= '<td style="width: 10%;"></td>';
				$this->html .= '<td style="width: 15%;">IGV</td>';
				$this->html .= '<td style="width: 15%;"></td>';
			$this->html .= '</tr>';
			$this->html .= '<tr>';
				$this->html .= '<td style="width: 15%;"></td>';
				$this->html .= '<td style="width: 45%;"></td>';
				$this->html .= '<td style="width: 10%;"></td>';
				$this->html .= '<td style="width: 15%;">TOTAL</td>';
				$this->html .= '<td style="width: 15%;">' . number_format($this->cabecera[0]['Total'],2,'.',' ') . '</td>';
			$this->html .= '</tr>';
    	$this->html .= '</table>';
    }

    public function pie_factura()
    {
    	 $codigo = $this->cabecera[0]['Ruc'] . '|' . $this->cabecera[0]['NroSerie'] . '|' . trim($this->cabecera[0]['NroDocumento']) . '|' . $this->cabecera[0]['Subtotal'] . '|' . $this->cabecera[0]['IGV'] . '|' . $this->cabecera[0]['Total'];


    	$this->html .= '<div style="margin-top: 10%;text-align: center;">';
	    	$this->html .= '<img src="' . route('qrsimple',$codigo) . trim($codigo) . '" style="width: 50%;">';
        $this->html .= '</div>';
	    	$this->html .= '<hr><p>Resolucion: Nro. 0340050010017/SUNAT</p>';
	    	$this->html .= '<p>Consulta en: https://escondatagafe.page.link/bon</p>';
	    	$this->html .= '<h1>GRACIAS POR SU VISITA</h1>';
    	
    	$this->html .= '</body></html>';
    }

/*
    public function generar_ticket($idorden)
    {
        $this->idorden = $idorden;

        $Caja = new Caja();
        $this->detalle = $Caja->Obtener_Datos_Factura_Detalle_Id($this->idorden);
        $this->cabecera = $Caja->Obtener_Datos_Factura_Cabecera_Id($this->idorden);

        switch ($this->cabecera[0]['IdTipoComprobante']) {
        	case 'F':
        		$this->nombre = 'FACTURA';
        		break;
        	
        	case 'B':
        		$this->nombre = 'BOLETA';
        		break;
        }

        $Sistema = new Sistema();
        $this->data_proveedor = $Sistema->Obtener_Proveedor($this->cabecera[0]['Ruc']);

        $this->fecha_emision = substr($this->cabecera[0]['FechaCobranza'], 0,10);
        list($anio, $mes, $dia) = explode("-", $this->fecha_emision);
        $this->fecha_emision = $dia."/".$mes."/".$anio;
    	$this->fpdf = new Fpdf();
        $this->fpdf->SetAutoPageBreak(true, 1);
        $this->fpdf->SetMargins(0, 0 , 0); 
        $this->fpdf->AddPage('P',array(64,150));
        $this->cabecera_ticket();
        $this->datos_receptor();
        $this->detalle_factura();
        $this->pie_factura();
		$this->fpdf->Output();
		$this->fpdf->Close();		
    }
	
	public function cabecera_ticket_()
    {
    	$this->fpdf->Image('svg/Logos/logo-factura.jpg',0,5,1);
		$this->fpdf->SetFont('Courier','B',8);
		$this->fpdf->SetXY(1,5);
		$this->fpdf->Cell(55,5,'HOSPITAL NACIONAL ARZOBISPO LOAYZA',0,1,'L');
		$this->fpdf->SetFont('Courier','',7);
		$this->fpdf->SetXY(2,9);
		$this->fpdf->Cell(64,4,strtoupper(utf8_decode('Av. Alfonso Ugarte 848 - Telefono 6144646')),0,1,'L');
		$this->fpdf->SetXY(20,12);
        $this->fpdf->Cell(30,4,'RUC: 20154996991',0,1,'L');
        $this->fpdf->SetXY(7,15);
        $this->fpdf->Cell(50,5,"TELF:6144650 PARA CITAS DE LUNES - VIERNES",0,1,'C');
        $this->fpdf->SetXY(0,25);
        $this->fpdf->SetFont('Courier','B',8);
        $this->fpdf->Cell(64,5, utf8_decode($this->nombre . " ELECTRÓNICA"),'TB',1,'C');
    }

    public function datos_receptor_()
    {
    	$this->fpdf->SetXY(3,32);
		$this->fpdf->SetFillColor(248,249,249);
		$this->fpdf->SetFont('Courier','',7);
		$this->fpdf->Cell(40,4,$this->cabecera[0]['NroSerie'] . "-" . $this->cabecera[0]['NroDocumento'],0, 0, 'L', True);
		// FECHA DE EMISION
		$this->fpdf->SetFont('Courier','',7);
		$this->fpdf->SetXY(45,32);
		$this->fpdf->Cell(110,4,$this->fecha_emision,0, 0, 'L', True);
		$this->fpdf->SetXY(3,36);
		$this->fpdf->Cell(40,4,'PACIENTE :',0, 0, 'L', True);
		$this->fpdf->SetXY(18,36);
		$this->fpdf->Cell(110,4,utf8_decode($this->cabecera[0]['ApellidoPaterno'] . ' ' . $this->cabecera[0]['ApellidoMaterno'] . ' ' . $this->cabecera[0]['PrimerNombre']),0, 0, 'L', True);
    }

    public function detalle_factura_()
    {
    	$this->fpdf->Ln(5);
    	$this->fpdf->SetX(0);
    	$this->fpdf->SetFont('Courier','B',7);
		$this->fpdf->Cell(8,5,utf8_decode('COD'),0,0,'C');
		$this->fpdf->Cell(34,5,utf8_decode('DESCRIPCIÓN'),0,0,'L');
		$this->fpdf->Cell(9,5,utf8_decode('CANT'),0,0,'C');
		$this->fpdf->Cell(7,5,utf8_decode('PU'),0,0,'C');
		$this->fpdf->Cell(7,5,utf8_decode('PT'),0,1,'C');

		$h = 0;
		$this->fpdf->SetFont('Courier','',7);
		for ($i=0; $i < count($this->detalle); $i++) { 

			$h =  $h + 1;
			$this->fpdf->SetX(0);
			$this->fpdf->Cell(8,5,trim($this->detalle[$i]['Codigo']),'',0,'C');
			$this->fpdf->Cell(34,5,utf8_decode($this->detalle[$i]['Descripcion']),'',0,'L');
			$this->fpdf->Cell(9,5,$this->detalle[$i]['Cantidad'],'',0,'C');
			$this->fpdf->Cell(7,5,number_format($this->detalle[$i]['ValorUnitario'],2,'.',' '),'',0,'C');
			$this->fpdf->Cell(7,5,number_format($this->detalle[$i]['SubTotal'],2,'.',' '),'',1,'C');

			if ($h == 39) {
				$this->fpdf->AddPage('P','A4');
			}
		}
		$this->fpdf->SetX(0);
		$this->fpdf->Cell(8,1,"",'B',0,'C');
		$this->fpdf->Cell(34,1,"",'B',0,'C');
		$this->fpdf->Cell(9,1,"",'B',0,'L');
		$this->fpdf->Cell(7,1,"",'B',0,'C');
		$this->fpdf->Cell(7,1,"",'B',1,'C');

		// Datos Adicionales
		$this->fpdf->Ln(1);
		$this->fpdf->SetX(0);
		$this->fpdf->Cell(57,5,utf8_decode('Sub Total: '),0,0,'R');
		$this->fpdf->Cell(7,5,number_format($this->cabecera[0]['Subtotal'],2,'.',' '),0,1,'C');
		$this->fpdf->SetX(0);
		$this->fpdf->Cell(57,5,utf8_decode('Adelantos: '),0,0,'R');
		$this->fpdf->Cell(7,5,number_format('0.00',2,'.',' '),0,1,'C');
		$this->fpdf->SetX(0);
		$this->fpdf->Cell(57,5,utf8_decode('Total: '),0,0,'R');
		$this->fpdf->Cell(7,5,number_format($this->cabecera[0]['Total'],2,'.',' '),0,1,'C');

		
    }

    public function pie_factura_()
	{
	    $this->fpdf->SetY(-17);
	    $this->fpdf->SetX(0);
	    $codigo = $this->cabecera[0]['Ruc'] . '|' . $this->cabecera[0]['NroSerie'] . '|' . trim($this->cabecera[0]['NroDocumento']) . '|' . $this->cabecera[0]['Subtotal'] . '|' . $this->cabecera[0]['IGV'] . '|' . $this->cabecera[0]['Total'];

	    $this->fpdf->Image(route('qrsimple',$codigo) . trim($codigo),20,103,25,25,'PNG');
	    $this->fpdf->SetX(0);
		$this->fpdf->Cell(40,4,'Resolucion: Nro. 0340050010017/SUNAT',0, 0, 'L', True);
		$this->fpdf->Ln(3);
		$this->fpdf->SetX(0);
		$this->fpdf->Cell(40,4,'Consulta en: https://escondatagafe.page.link/bon',0, 0, 'L', True);
		$this->fpdf->Ln(3);
		$this->fpdf->SetX(0);
		$this->fpdf->SetFont('Courier','B',8);
		$this->fpdf->Cell(40,4,'Gracias por su visita',0, 0, 'L', True);
	}*/
}
