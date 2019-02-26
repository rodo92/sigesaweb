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
}
