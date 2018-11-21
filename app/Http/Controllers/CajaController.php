<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Caja;

class CajaController extends Controller
{
    public function cajas()
    {
    	return view('caja.cajas');
    }

    public function listar_cajas()
    {
    	$Caja = new Caja();
    	$cajas = $Caja->Mostrar_Cajas();

    	for ($i=0; $i < count($cajas); $i++) { 
    		$data[$i] = array(
    			'IdCaja'		=> $cajas[$i]['IdCaja'],
    			'Descripcion'	=> ucwords(strtolower($cajas[$i]['Descripcion']))
    		);
    	}

    	return response()->json(['data' => $data]);
    }

    public function listar_caja_tipo_documento()
    {
    	$Caja = new Caja();
    	$cajas = $Caja->Mostrar_Cajas_Tipo_Comprobante();

    	for ($i=0; $i < count($cajas); $i++) { 
    		$data[$i] = array(
    			'IdTipoComprobante'	=> $cajas[$i]['IdTipoComprobante'],
    			'Descripcion'		=> ucwords(strtolower($cajas[$i]['Descripcion']))
    		);
    	}

    	return response()->json(['data' => $data]);
    }

    public function aperturar_caja(Request $request)
    {
        $messages = [
            'idcaja.required'           => 'Debe seleccionar una caja',
            'tipodocumento.required'    => 'Debe seleccionar un tipo de documento'
        ];

        $rules = [
            'idcaja'        => 'required',
            'tipodocumento' => 'required'
        ];

        $this->validate($request,$rules,$messages);

        $fechaapertura = date('Y-m-d H:i:s') . '.000';
        $estadolote = 'A';
        $idturno = 1;
        $totalcobrado = 0;
        $idempleado = session()->get('id_empleado');
        $idcaja = $request->idcaja;



        $Caja = new Caja();
        $data = $Caja->Apertura_Caja($fechaapertura,$estadolote,$idcaja,$idturno,$totalcobrado,$idempleado);

        return response()->json(['data' => 'Apertura de Caja Correcto']);
    }
}
