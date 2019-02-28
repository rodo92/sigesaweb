<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Sunat;

class SunatController extends Controller
{
    public function index()
    {
    	return view('sunat.envio');
    }

    public function envio_informacion($fecha)
    {
    	$response = array();

    	$sunat = New Sunat();
    	$data = $sunat->SIGESA_TRAMA_LISTA_IDCOMPROBANTES_XFECHA($fecha);

    	if (count($data) > 0) {
    		for ($i=0; $i < count($data); $i++) {
    				$trama = $this->generar_facturas($data[$i]['IDCOMPROBANTE']);
    				$response[] = array(
                    'trama'                    => $trama
                	);
    		}

    		return response()->json(['data' => $response]);
    	} else {
    		return response()->json(['data' => 'No hay ningun documento para la fecha indicada']);
    	}
    }

    public function generar_facturas($idcomprobante)
    {
    	$sunat = New Sunat();
    	$trama = null;
        $trama = new \stdClass();
    	//TRAEMOS LOS DATOS DE LA CABECERA
    	$cabecera = $sunat->SIGESA_TRAMA_CABECERA_X_CODIGO($idcomprobante);
    	//TRAEMOS LOS DATOS DEL DETALLE
    	$detalle = $sunat->SIGESA_TRAMA_DETALLE_X_CODIGO($idcomprobante);	

    	$leyenda[] = array(
                    'codigo'      => '1000', 
                    'descripcion'   => ''
        );

        $totalImpuestos[] = array(
            'idImpuesto'      => $cabecera[0]['CODIGO_IMPUESTO'], 
            'montoImpuesto'   => number_format($cabecera[0]['TOTAL_IMPUESTO'],2,'.',' ')
        );

        $totalImpustosDocumento=0;
        $ValorTotal = 0; 

        for ($i=0; $i < count($detalle); $i++) { 

            if ($cabecera[0]['CAB']=='INAFECTAS') {
                $tipoAfectacion = "30";
                $porcentaje = "0";
            }else{
                $tipoAfectacion = "10";
                $porcentaje = "18";
                if ($detalle[$i]['TOTALIMPUESTO']<0.49) {
                    $TOTALIMPUESTO = 0.01;
                    $VALORUNITARIO = $detalle[$i]['VALORUNITARIO'] - $TOTALIMPUESTO;
                }else{
                    $TOTALIMPUESTO = $detalle[$i]['TOTALIMPUESTO'];
                    $VALORUNITARIO = $detalle[$i]['VALORUNITARIO'];
                }
            }

            $totalImpuestos2[0] = array(
                'idImpuesto'        =>  $cabecera[0]['CODIGO_IMPUESTO'], 
                'montoImpuesto'     =>  number_format($TOTALIMPUESTO,2, '.', ' '), 
                'tipoAfectacion'    =>  $tipoAfectacion,
                'montoBase'         =>  number_format($detalle[$i]['TOTALPAGAR']-$TOTALIMPUESTO,2,'.',' '),
                'porcentaje'        => $porcentaje
            );

            $data_detalles[] = array(
                'numeroItem'            => strval('00' . $i + 1), 
                'codigoProducto'        => $detalle[$i]['IDPRODUCTO'], 
                'descripcionProducto'   => $detalle[$i]['NOMBRE'], 
                'cantidadItems'         => $detalle[$i]['CANTIDADPAGAR'], 
                'unidad'                => 'NIU', 
                'valorUnitario'         => number_format($VALORUNITARIO,2,'.',' '), 
                'precioVentaUnitario'   => number_format($detalle[$i]['PRECIOVENTA'],2,'.',' '),
                'totalImpuestos'        => $totalImpuestos2,
                'valorVenta'            => number_format($VALORUNITARIO * $detalle[$i]['CANTIDADPAGAR'],2,'.',' ') , //number_format($detalle[$i]['TOTALPAGAR'],2,'.',' '),
                'montoTotalImpuestos'   => number_format($TOTALIMPUESTO,2, '.', ' ')    
            );

            $ValorTotal = $ValorTotal + ($detalle[$i]['PRECIOVENTA'] * $detalle[$i]['CANTIDADPAGAR']);

            $totalImpustosDocumento = $totalImpustosDocumento+number_format($TOTALIMPUESTO,2, '.', ' '); 
        }

        if ($cabecera[0]['CODTIPODOCUMENTO'] < 10) {
            $codTipoDocumento = '0' . $cabecera[0]['CODTIPODOCUMENTO'];
        } else {
            $codTipoDocumento = $cabecera[0]['CODTIPODOCUMENTO'];
        }

        $trama->boleta = array(
            'IDE' => array(
                'numeracion'        => $cabecera[0]['NUMERACION'],
                'fechaEmision'      => $cabecera[0]['FECHAEMISION'],
                'horaEmision'       => $cabecera[0]['HORAEMISION'],
                'codTipoDocumento'  => $codTipoDocumento,
                'tipoMoneda'        => $cabecera[0]['TIPOMONEDA'] 
            ),
            'EMI' => array(
                'tipoDocId'     => trim($cabecera[0]['TIPODOCIDEMI']),
                'numeroDocId'   => $cabecera[0]['NUMERODOCIDEMI'],
                'razonSocial'   => $cabecera[0]['RAZONSOCIALEMI'],
                'direccion'     => $cabecera[0]['DIRECCIONEMI'],
                'codigoAsigSUNAT' => '0000'
            ),
            'REC' => array(
                'tipoDocId'     => $cabecera[0]['TIPODOCRELACIONADO'],
                'numeroDocId'   => $cabecera[0]['NUMERODOCID'],
                'razonSocial'   => $cabecera[0]['RAZONSOCIAL'],
                'direccion'     => $cabecera[0]['DIRECCION']
            ),
            'CAB' => array(
                strtolower($cabecera[0]['CAB']) => array(
                    'codigo'        => $cabecera[0]['CAB_CODIGO'], 
                    'totalVentas'   => number_format($cabecera[0]['CAB_TOTALVENTAS'],2,'.',' ')
                ),
                'totalImpuestos' => $totalImpuestos,
                'importeTotal' => number_format($cabecera[0]['IMPORTE_TOTAL'],2,'.',' '), //number_format($ValorTotal,2,'.',' '),
                'tipoOperacion' => '0101',  //boleta de venta
                'montoTotalImpuestos' => number_format($totalImpustosDocumento,2,'.',' ')

                //'leyenda' => $leyenda
            ),	
            'DET' => $data_detalles
        );

        return $trama;
    }
}
