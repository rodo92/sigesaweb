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
        $data = $Caja->Reporte_resumen_por_cajeros($fechainicio, $fechafin, $idcajero)
    // echo count($data); exit;
        if (count($data) > 0) { 
            
            for ($i=0; $i < count($data); $i++) { 
                $response[] = array(
                    'CAJERO'                    => $data[$i]['Cajero'],
                );
            }
            return response()->json(
                [
                    'data' => $response
                ]); 
        } else { return response()->json(['data' => 'sindatos']); }
    }
}
