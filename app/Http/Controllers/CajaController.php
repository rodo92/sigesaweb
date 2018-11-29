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
        $caja = new Caja();

        if(!ctype_digit($parametro))
        {
            
            $TipoBusqueda = [0,2];
            $filtro = "and FactCatalogoServiciosHosp.IdTipoFinanciamiento = " . $seguro . " and FactCatalogoServicios.Nombre like '%" . $parametro . "%'";

            $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[0],$filtro);
            //echo $filtro;exit();

            if (count($data) == 0) {
                $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[1],$parametro);
            }
            else{
                $data2 = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[1],$parametro);
                $data = array_merge($data, $data2);

            }         
        }
        else{
            $TipoBusqueda = [1,3];
            $filtro = "and FactCatalogoServiciosHosp.IdTipoFinanciamiento = " . $seguro . " and FactCatalogoServicios.Codigo = '" . $parametro . "'";

            $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[0],$filtro);
            if (count($data) == 0) {
                $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[1],$parametro);
            }
        }


        if (count($data) == 0) {
            return response()->json(['data' => 'sindatos']);
        }
        else {
            
            $data  = array_values($data);
            // return response()->json($data);exit();

            for ($i=0; $i < count($data); $i++) { 
                $productos[] = array(
                    'Codigo'    => $data[$i]['Codigo'],
                    'Nombre'    => $data[$i]['Nombre'],
                    'Cantidad'  => 0,
                    'Precio'    => number_format($data[$i]['Precio'],4,'.',' ')
                );
                // return response()->json($data[$i]['Codigo']);
            }
            // exit();
            return response()->json(['data' => $productos]);
        }
    }

    public function buscar_detalle_boleta_x_codigo($serio,$ndocumento,$idOrden='')
    {
        $caja = new Caja();
        $data = $caja->Datos_X_Codigo_Para_Facturar($serio,$ndocumento,$idOrden);

        if (count($data) > 0) {
            $paciente       = $data[0]['RazonSocial'];
            $subtotal       = number_format($data[0]['SubTotal'],4,'.',' ');
            $igv            = number_format($data[0]['IGV'],4,'.',' ');
            $total          = number_format($data[0]['Total'],4,'.',' ');
            $comprobante    = $data[0]['Comprobante'];

            for ($i=0; $i < count($data); $i++) { 
                $productos[] = array(
                    'Comprobante'   => $data[$i]['Comprobante'],
                    'Codigo'        => $data[$i]['Codigo'],
                    'Producto'      => $data[$i]['Producto'],
                    'Cantidad'      => $data[$i]['Cantidad'],
                    'Precio'        => number_format($data[$i]['Precio'],4,'.',' '),
                    'TotalUnitario' => number_format($data[$i]['TotalUnitario'],4,'.',' ')
                );
            }

            $response = array(
                'paciente'      => $paciente,
                'subtotal'      => $subtotal,
                'igv'           => $igv,
                'total'         => $total,
                'comprobante'   => $comprobante,
                'productos'     => $productos
            );

            return response()->json(['data' => $response]);
        }
        else{
            return response()->json(['data' => 'sindatos']);
        }
        
    }

    public function buscar_boleta_x_cuenta($cuenta)
    {
        $caja = new Caja();
        $data_cabecera = $caja->Datos_X_Cuenta_Cabecera($cuenta);
        $data_detalle =  $caja->Datos_X_Cuenta_Detalle($cuenta);

        if (count($data_cabecera) > 0) {
            $paciente       = $data_cabecera[0]['ApellidoPaterno'] . ' ' . $data_cabecera[0]['ApellidoMaterno'] . ' ' . $data_cabecera[0]['PrimerNombre'];
            $idSeguro       = $data_cabecera[0]['idFuenteFinanciamiento'];
            $seguro         = $data_cabecera[0]['dFuenteFinanciamiento'];

            for ($i=0; $i < count($data_detalle); $i++) { 
                $productos[] = array(
                    'Comprobante'   => $data_detalle[$i]['IdCuentaAtencion'],
                    'Codigo'        => $data_detalle[$i]['Codigo'],
                    'Producto'        => strtoupper($data_detalle[$i]['Nombre']),
                    'Cantidad'      => $data_detalle[$i]['cantidad'],
                    'Precio'        => number_format($data_detalle[$i]['PrecioUnitario'],4,'.',' '),
                    'SubTotal'        => number_format($data_detalle[$i]['SubTotal'],4,'.',' '),
                    'Impuesto'        => number_format($data_detalle[$i]['Impuesto'],4,'.',' '),
                    'TotalUnitario' => number_format($data_detalle[$i]['Total'],4,'.',' ')
                );
            }

            $response = array(
                'paciente'      => $paciente,
                'idseguro'      => $idSeguro,
                'seguro'        => $seguro,
                'productos'     => $productos
            );

            return response()->json(['data' => $response]);
        }
        else {
            return response()->json(['data' => 'sindatos']);
        }
    }
}
