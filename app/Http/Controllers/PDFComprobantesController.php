<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;
use WebSigesa\Caja;
use WebSigesa\Sistema;

class PDFComprobantesController extends Controller
{
    private $idorden = '';
    private $fpdf;
    private $detalle;
    private $cabecera;
    private $nombre;
    private $data_proveedor;

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
        /*echo '<pre>';
        print_r($this->data_proveedor);exit();*/
    	$this->fpdf = new Fpdf();
        $this->fpdf->AddPage('P','A4');
        $this->cabecera_factura();
        $this->datos_receptor();
        $this->detalle_factura();
        $this->pie_factura();
		$this->fpdf->Output();
		$this->fpdf->Close();
    }
	
	public function cabecera_factura()
    {
    	$this->fpdf->Image('svg/Logos/logo-factura.jpg',10,8,33);
		$this->fpdf->SetFont('Arial','B',8);
		$this->fpdf->Cell(35);
		$this->fpdf->Cell(65,5,'HOSPITAL NACIONAL ARZOBISPO LOAYZA',0,1,'L');
		$this->fpdf->SetXY(48,14);
		$this->fpdf->SetFont('Arial','',8);
		$this->fpdf->Cell(65,4,'Av. Alfonso Ugarte 848 - Cercado de Lima',0,1,'L');
		$this->fpdf->SetXY(57,18);
		$this->fpdf->Cell(65,4,'Prov. de Lima - Lima - Peru',0,1,'L');
		$this->fpdf->SetXY(140,5);
		$this->fpdf->MultiCell(50,4,"\n" . $this->nombre . " ELECTRONICA \n R.U.C  Nro. 20154996991\n" . $this->cabecera[0]['NroSerie'] . "-" . $this->cabecera[0]['NroDocumento'] . "\n ",1,'C');
		$this->fpdf->Ln(5);
		$this->fpdf->Line(10, 30, 210-10, 30);
		$this->fpdf->Ln(8);
    }

    public function datos_receptor()
    {
    	$this->fpdf->SetFont('Arial','',7);
    	$this->fpdf->Cell(30,4,utf8_decode('SEÑOR(ES):'),0,0,'L');
    	$this->fpdf->SetFont('Arial','B',7);
    	$this->fpdf->Cell(110,4,utf8_decode($this->cabecera[0]['RazonSocial']),0,0,'L');
    	$this->fpdf->SetFont('Arial','',7);    	
    	$this->fpdf->Cell(30,4,utf8_decode('FECHA DE EMISIÓN:'),0,0,'L');
    	$this->fpdf->Cell(20,4,utf8_decode('23/08/2018'),0,1,'L');

    	$this->fpdf->Cell(30,4,utf8_decode('R.U.C.:'),0,0,'L');
    	$this->fpdf->SetFont('Arial','B',7);
    	$this->fpdf->Cell(110,4,utf8_decode($this->cabecera[0]['Ruc']),0,0,'L');
    	$this->fpdf->SetFont('Arial','',7);
    	$this->fpdf->Cell(30,4,utf8_decode('C.I.I.U NRO:'),0,0,'L');
    	$this->fpdf->Cell(20,4,utf8_decode('85111'),0,1,'L');

    	$this->fpdf->Cell(30,4,utf8_decode('DIRECCIÓN:'),0,0,'L');
    	$this->fpdf->SetFont('Arial','B',7);
    	$this->fpdf->MultiCell(110,4,utf8_decode($this->data_proveedor[0]['Direccion']),0,'L');
    	// $this->fpdf->Cell(30,4,utf8_decode('C.I.I.U NRO:'),1,0,'L');
    	// $this->fpdf->Cell(20,4,utf8_decode('85111'),1,1,'L');

    	$this->fpdf->SetFont('Arial','',7);
    	$this->fpdf->Cell(30,4,utf8_decode('PACIENTE:'),0,0,'L');
    	$this->fpdf->Cell(160,4,utf8_decode($this->cabecera[0]['ApellidoPaterno'] . ' ' . $this->cabecera[0]['ApellidoMaterno'] . ' ' . $this->cabecera[0]['PrimerNombre']),0,1,'L');
    	// $this->fpdf->Cell(30,4,utf8_decode('C.I.I.U NRO:'),1,0,'L');
    	// $this->fpdf->Cell(20,4,utf8_decode('85111'),1,1,'L');

    	$this->fpdf->Cell(30,4,utf8_decode('OBSERVACIÓN 1:'),0,0,'L');
    	$this->fpdf->MultiCell(160,4,utf8_decode($this->cabecera[0]['Observacion1']),0,'L',false);
    	// $this->fpdf->Cell(30,4,utf8_decode('C.I.I.U NRO:'),1,0,'L');
    	// $this->fpdf->Cell(20,4,utf8_decode('85111'),1,1,'L');

    	$this->fpdf->Cell(30,4,utf8_decode('OBSERVACIÓN 2:'),0,0,'L');
    	$this->fpdf->MultiCell(160,4,utf8_decode($this->cabecera[0]['Observacion2']),0,'L',false);
    	// $this->fpdf->Cell(30,5,utf8_decode('C.I.I.U NRO:'),1,0,'L');
    	// $this->fpdf->Cell(20,5,utf8_decode('85111'),1,1,'L');
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
	    // Position at 1.5 cm from bottom
	    $this->fpdf->SetY(-30);
	    // Arial italic 8
	    $this->fpdf->SetFont('Arial','I',7);
	    // Page number
	    $this->fpdf->MultiCell(0,4,utf8_decode("Esta es una representación impresa de la factura electrónica, generada por el Hospital Nacional Arzobispo Loayza."),1,'C');
	    $this->fpdf->SetFont('Arial','',7);
	    $this->fpdf->Cell(175,5,utf8_decode('Fecha de Impresión: '),0,0,'R');
	    $this->fpdf->Cell(15,5,date('d-m-Y'),0,1,'C');
	}
}
