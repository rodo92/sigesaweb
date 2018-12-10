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

        $Sistema = new Sistema();
        $this->data_proveedor = $Sistema->Obtener_Proveedor($this->cabecera[0]['Ruc']);

        $this->fecha_emision = substr($this->cabecera[0]['FechaCobranza'], 0,10);
        list($anio, $mes, $dia) = explode("-", $this->fecha_emision);
        $this->fecha_emision = $dia."/".$mes."/".$anio;
    	$this->fpdf = new Fpdf();
        $this->fpdf->SetAutoPageBreak(true, 1);
        $this->fpdf->AddPage('P',array(74,150));
        $this->cabecera_ticket();
        $this->datos_receptor();
        $this->detalle_factura();
        $this->pie_factura();
		$this->fpdf->Output();
		$this->fpdf->Close();		
    }
	
	public function cabecera_ticket()
    {
    	$this->fpdf->Image('svg/Logos/logo-factura.jpg',0,5,20);
		$this->fpdf->SetFont('Arial','B',7);
		$this->fpdf->SetXY(17,5);
		$this->fpdf->Cell(55,5,'HOSPITAL NACIONAL ARZOBISPO LOAYZA',0,1,'L');
		$this->fpdf->SetFont('Arial','',7);
		$this->fpdf->SetXY(20,9);
		$this->fpdf->Cell(64,4,'Av. Alfonso Ugarte 848 - Telefono 6144646',0,1,'L');
		$this->fpdf->SetXY(30,13);
        $this->fpdf->Cell(30,4,'RUC: 20154996991',0,1,'L');
        $this->fpdf->SetXY(20,16);
        $this->fpdf->Cell(50,5,"TELF:6144650 para citas de lunes-viernes",0,1,'C');
        $this->fpdf->SetXY(4,25);
        $this->fpdf->SetFont('Arial','B',8);
        $this->fpdf->Cell(66,5, utf8_decode($this->nombre . " ELECTRÓNICA"),'TB',1,'C');
    }

    public function datos_receptor()
    {
    	$this->fpdf->SetXY(5,32);
		$this->fpdf->SetFillColor(248,249,249);
		$this->fpdf->SetFont('Arial','B',9);
		$this->fpdf->Cell(40,4,$this->cabecera[0]['NroSerie'] . "-" . $this->cabecera[0]['NroDocumento'],0, 0, 'L', True);
		// FECHA DE EMISION
		$this->fpdf->SetFont('Arial','',7);
		$this->fpdf->SetXY(40,32);
		$this->fpdf->Cell(110,4,$this->fecha_emision,0, 0, 'L', True);
		$this->fpdf->SetXY(5,36);
		$this->fpdf->Cell(40,4,'Usuario :',0, 0, 'L', True);
		$this->fpdf->SetXY(20,36);
		$this->fpdf->Cell(110,4,utf8_decode($this->cabecera[0]['ApellidoPaterno'] . ' ' . $this->cabecera[0]['ApellidoMaterno'] . ' ' . $this->cabecera[0]['PrimerNombre']),0, 0, 'L', True);
    }

    public function detalle_factura()
    {
    	$this->fpdf->Ln(5);
    	$this->fpdf->SetX(2);
    	$this->fpdf->SetFont('Arial','B',7);
		$this->fpdf->Cell(8,5,utf8_decode('COD'),0,0,'C');
		$this->fpdf->Cell(38,5,utf8_decode('DESCRIPCIÓN'),0,0,'L');
		$this->fpdf->Cell(9,5,utf8_decode('CANT'),0,0,'C');
		$this->fpdf->Cell(7,5,utf8_decode('PU'),0,0,'C');
		$this->fpdf->Cell(7,5,utf8_decode('PT'),0,1,'C');

		$h = 0;
		$this->fpdf->SetFont('Arial','',7);
		for ($i=0; $i < count($this->detalle); $i++) { 

			$h =  $h + 1;
			$this->fpdf->SetX(2);
			$this->fpdf->Cell(8,5,trim($this->detalle[$i]['Codigo']),'',0,'C');
			$this->fpdf->Cell(38,5,utf8_decode($this->detalle[$i]['Descripcion']),'',0,'L');
			$this->fpdf->Cell(9,5,$this->detalle[$i]['Cantidad'],'',0,'C');
			$this->fpdf->Cell(7,5,number_format($this->detalle[$i]['ValorUnitario'],2,'.',' '),'',0,'C');
			$this->fpdf->Cell(7,5,number_format($this->detalle[$i]['SubTotal'],2,'.',' '),'',1,'C');

			if ($h == 39) {
				$this->fpdf->AddPage('P','A4');
			}
		}
		$this->fpdf->SetX(2);
		$this->fpdf->Cell(8,1,"",'B',0,'C');
		$this->fpdf->Cell(38,1,"",'B',0,'C');
		$this->fpdf->Cell(9,1,"",'B',0,'L');
		$this->fpdf->Cell(7,1,"",'B',0,'C');
		$this->fpdf->Cell(7,1,"",'B',1,'C');

		// Datos Adicionales
		$this->fpdf->Ln(1);
		$this->fpdf->SetX(2);
		$this->fpdf->Cell(62,5,utf8_decode('Sub Total: '),0,0,'R');
		$this->fpdf->Cell(7,5,number_format($this->cabecera[0]['Subtotal'],2,'.',' '),0,1,'C');
		$this->fpdf->SetX(2);
		$this->fpdf->Cell(62,5,utf8_decode('Adelantos: '),0,0,'R');
		$this->fpdf->Cell(7,5,number_format('0.00',2,'.',' '),0,1,'C');
		$this->fpdf->SetX(2);
		$this->fpdf->Cell(62,5,utf8_decode('Total: '),0,0,'R');
		$this->fpdf->Cell(7,5,number_format($this->cabecera[0]['Total'],2,'.',' '),0,1,'C');

		
    }

    public function pie_factura()
	{
	    $this->fpdf->SetY(-17);
	    $this->fpdf->SetX(2);
	    $codigo = $this->cabecera[0]['Ruc'] . '|' . $this->cabecera[0]['NroSerie'] . '|' . trim($this->cabecera[0]['NroDocumento']) . '|' . $this->cabecera[0]['Subtotal'] . '|' . $this->cabecera[0]['IGV'] . '|' . $this->cabecera[0]['Total'];

	    $this->fpdf->Image(route('qrsimple',$codigo) . trim($codigo),25,103,25,25,'PNG');
	    $this->fpdf->SetX(2);
		$this->fpdf->Cell(40,4,'Resolucion: Nro. 0340050010017/SUNAT',0, 0, 'L', True);
		$this->fpdf->Ln(3);
		$this->fpdf->SetX(2);
		$this->fpdf->Cell(40,4,'Consulta en: https://escondatagafe.page.link/bon',0, 0, 'L', True);
		$this->fpdf->Ln(3);
		$this->fpdf->SetX(2);
		$this->fpdf->SetFont('Arial','B',8);
		$this->fpdf->Cell(40,4,'Gracias por su visita',0, 0, 'L', True);
	}
}
