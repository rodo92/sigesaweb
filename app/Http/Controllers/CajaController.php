<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Caja;
use WebSigesa\Paciente;

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

        return response()->json(['data' => $data]);
    }

    public function tipo_seguro_paciente($dni)
    {
        $data = false;
        $Paciente = new Paciente();
        $pre_data = $Paciente->Tipo_Seguro_DNI($dni);

        for ($i=0; $i < count($pre_data); $i++) { 
            $data = array(
                'IdCuentaAtencion' => $pre_data[$i]['IdCuentaAtencion'],
                'EstadoCuenta' => $pre_data[$i]['EstadoCuenta'],
                'IdPaciente' => $pre_data[$i]['IdPaciente'],
                'IdAtencion' => $pre_data[$i]['IdAtencion'],
                'FechaIngreso' => $pre_data[$i]['FechaIngreso'],
                'HoraIngreso' => $pre_data[$i]['HoraIngreso'],
                'NroHistoriaClinica' => $pre_data[$i]['NroHistoriaClinica'],
                'ApellidoPaterno' => $pre_data[$i]['ApellidoPaterno'],
                'ApellidoMaterno' => $pre_data[$i]['ApellidoMaterno'],
                'PrimerNombre' => $pre_data[$i]['PrimerNombre'],
                'SegundoNombre' => $pre_data[$i]['SegundoNombre'],
                'FechaEgreso' => $pre_data[$i]['FechaEgreso'],
                'HoraEgreso' => $pre_data[$i]['HoraEgreso'],
                'IdEstado' => $pre_data[$i]['IdEstado'],
                'ServicioIngreso' => $pre_data[$i]['ServicioIngreso'],
                'dTipoServicio' => $pre_data[$i]['dTipoServicio'],
                'Edad' => $pre_data[$i]['Edad'],
                'IdTipoNumeracion' => $pre_data[$i]['IdTipoNumeracion'],
                'IdTipoServicio' => $pre_data[$i]['IdTipoServicio'],           
                'idFuenteFinanciamiento' => $pre_data[$i]['idFuenteFinanciamiento'],           
                'Financiamiento' => $pre_data[$i]['Financiamiento'],           
                'IdServicioIngreso' => $pre_data[$i]['IdServicioIngreso']           
            );
        }

        return response()->json(['data' => $pre_data]);
    }

    public function servicios_medicamentos($seguro,$parametro)
    {
        echo $seguro . '<br>';
        echo $parametro . '<br>';

        // verificando si es cadena o codigo
        if(ctype_digit($parametro))
        {
            
            $TipoBusqueda = [1,3];

            // probando en servicios 
            $filtro = "and FactCatalogoServiciosHosp.IdTipoFinanciamiento = " . $seguro . " and FactCatalogoServicios.Codigo = '" . $parametro . "'";

            $caja = new Caja();
            $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[0],$filtro);

            if (count($data) == 0) {
               // probar en medicamentos
                $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[1],$parametro);
                echo '<pre>';
                print_r($data);
            }
            else{
                echo '<pre>';
                print_r($data);
            }

        }
        else{
            $filtro = [0,2];
        }
    }
}
