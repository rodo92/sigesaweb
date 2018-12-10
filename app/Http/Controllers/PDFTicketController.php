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
        $this->fpdf->SetAutoPageBreak(true, 2);
        $this->fpdf->AddPage('P',array(74,150));
        $this->cabecera_ticket();
        $this->datos_receptor();
        // $this->detalle_factura();
        // $this->pie_factura();
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
    	
    }

    public function detalle_factura()
    {
    	$this->fpdf->Ln(2);
    	$this->fpdf->SetFont('Arial','',7);
		$this->fpdf->Cell(15,5,utf8_decode('CANTIDAD'),1,0,'C');
		$this->fpdf->Cell(15,5,utf8_decode('CÓDIGO'),1,0,'C');
		$this->fpdf->Cell(130,5,utf8_decode('DESCRIPCIÓN'),1,0,'L');
		$this->fpdf->Cell(15,5,utf8_decode('PRECIO'),1,0,'C');
		$this->fpdf->Cell(15,5,utf8_decode('SUBTOTAL'),1,1,'C');

		$h = 0;

		for ($i=0; $i < count($this->detalle); $i++) { 

			$h =  $h + 1;

			$this->fpdf->Cell(15,5,$this->detalle[$i]['Cantidad'],'L',0,'C');
			$this->fpdf->Cell(15,5,trim($this->detalle[$i]['Codigo']),'L',0,'C');
			$this->fpdf->Cell(130,5,utf8_decode(' '.$this->detalle[$i]['IdPartida'] . ' ' . $this->detalle[$i]['Descripcion']),'L',0,'L');
			$this->fpdf->Cell(15,5,number_format($this->detalle[$i]['ValorUnitario'],2,'.',' '),'L',0,'C');
			$this->fpdf->Cell(15,5,number_format($this->detalle[$i]['SubTotal'],2,'.',' '),'LR',1,'C');

			if ($h == 39) {
				$this->fpdf->AddPage('P','A4');
			}
		}

		$this->fpdf->Cell(15,5,"",'LB',0,'C');
		$this->fpdf->Cell(15,5,"",'LB',0,'C');
		$this->fpdf->Cell(130,5,"",'LB',0,'L');
		$this->fpdf->Cell(15,5,"",'LB',0,'C');
		$this->fpdf->Cell(15,5,"",'LBR',1,'C');

		// Datos Adicionales
		$this->fpdf->Ln(5);
    	$this->fpdf->SetFont('Arial','',7);
		$this->fpdf->Cell(15,5,'',0,0,'C');
		$this->fpdf->Cell(50,5,'Valor de venta de operaciones gratuitas',0,0,'L');
		$this->fpdf->Cell(15,5,'S/. 0.00',1,0,'C');
		$this->fpdf->Cell(95,5,utf8_decode('Sub Total: '),0,0,'R');
		$this->fpdf->Cell(15,5,number_format($this->cabecera[0]['Subtotal'],2,'.',' '),1,1,'C');

		$this->fpdf->Cell(175,5,utf8_decode('Anticipos: '),0,0,'R');
		$this->fpdf->Cell(15,5,'0.00','LBR',1,'C');
		$this->fpdf->Cell(175,5,utf8_decode('Descuentos: '),0,0,'R');
		$this->fpdf->Cell(15,5,'0.00','LBR',1,'C');
		$this->fpdf->Cell(175,5,utf8_decode('Valor Venta: '),0,0,'R');
		$this->fpdf->Cell(15,5,'0.00','LBR',1,'C');
		$this->fpdf->Cell(175,5,utf8_decode('ISC: '),0,0,'R');
		$this->fpdf->Cell(15,5,'0.00','LBR',1,'C');
		$this->fpdf->Cell(175,5,utf8_decode('IGV: '),0,0,'R');
		$this->fpdf->Cell(15,5,number_format($this->cabecera[0]['IGV'],2,'.',' '),'LBR',1,'C');

		$this->fpdf->Cell(15,5,'',0,0,'C');
		$this->fpdf->SetFont('Arial','B',7);
		$this->fpdf->Cell(65,5,'SON: ',0,0,'L');
		$this->fpdf->SetFont('Arial','',7);
		$this->fpdf->Cell(95,5,utf8_decode('Otros Cargos: '),0,0,'R');
		$this->fpdf->Cell(15,5,'0.00',1,1,'C');

		$this->fpdf->Cell(175,5,utf8_decode('Otros Tributos: '),0,0,'R');
		$this->fpdf->Cell(15,5,'0.00','LBR',1,'C');
		$this->fpdf->Cell(175,5,utf8_decode('Importe Total: '),0,0,'R');
		$this->fpdf->Cell(15,5,number_format($this->cabecera[0]['Total'],2,'.',' '),'LBR',1,'C');
    }

    public function pie_factura()
	{
	    $this->fpdf->SetY(-17);
	    $codigo = $this->cabecera[0]['Ruc'] . '|' . $this->cabecera[0]['NroSerie'] . '|' . trim($this->cabecera[0]['NroDocumento']) . '|' . $this->cabecera[0]['Subtotal'] . '|' . $this->cabecera[0]['IGV'] . '|' . $this->cabecera[0]['Total'];

	    $this->fpdf->Image(route('qrsimple',$codigo) . trim($codigo),10,255,25,25,'PNG');

	    $this->fpdf->SetFont('Arial','',6);
	    $this->fpdf->Cell(160,5,utf8_decode('FECHA DE IMPRESIÓN:'),0,0,'R');
	    $this->fpdf->Cell(30,5,date('d/m/Y h:i:s A'),0,1,'C');

	    $this->fpdf->SetFont('Arial','',7);
	    $this->fpdf->MultiCell(0,4,utf8_decode("Autorizado mediante resolución Nro. 0340050010017/SUNAT. Para consultar el comprobante ingresar a https://escondatagate.page.link/bon2. Representación impresa del Comprobante Electrónico."),'T','C');
	}
}
