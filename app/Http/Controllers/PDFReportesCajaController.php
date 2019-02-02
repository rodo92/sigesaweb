<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use Codedge\Fpdf\Fpdf\Fpdf;

class PDFReportesCajaController extends Controller
{
    private $idorden = '';
    private $fpdf;
    private $detalle;
    private $cabecera;
    private $nombre;
    private $data_proveedor;
    private $fecha_emision;
    private $html;

    public function parte_diario($fechainicio, $fechafin)
    {
    	echo $fechainicio, $fechafin;
    }
}
