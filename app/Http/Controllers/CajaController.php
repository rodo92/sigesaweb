<?php

namespace WebSigesa\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use WebSigesa\Caja;
use WebSigesa\Paciente;
use WebSigesa\Sistema;
use WebSigesa\Mantenimiento;
use Codedge\Fpdf\Fpdf\Fpdf;
use WebSigesa\Http\Controllers\PDFComprobantesController;

class CajaController extends Controller
{
    public function cajas()
    {
    	return view('caja.cajasc');
    }

    public function cajas_central()
    {
        return view('caja.cajas');
    }

    public function cajas_farmacia()
    {
        return view('caja.cajasf');
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
        $tipodocumento = $request->tipodocumento;



        $Caja = new Caja();
        $data = $Caja->Apertura_Caja($fechaapertura,$estadolote,$idcaja,$idturno,$totalcobrado,$idempleado);
        $data_documento = $Caja->Traer_Serie_Correlativo($idcaja,$tipodocumento);

        $nrodocumento_temp = (int)$data_documento[0]['NroDocumento'] + 1;
        $nrodocumento_temp = (string)$nrodocumento_temp;

        $contador = strlen($nrodocumento_temp);
        $ceros = 8 - $contador;
        $nuevo_ceros = '';
        for ($i=0; $i < $ceros; $i++) { 
            $nuevo_ceros .= '0';
        }
        
        $nrodocumento = $nuevo_ceros . $nrodocumento_temp;

        $data_documento[0] =  array(
            'NroSerie' => $data_documento[0]['NroSerie'],
            'NroDocumento' => $nrodocumento,
            'IdGestionCaja' => $data
        );

        // trayendo serie 


        return response()->json([
            'data'              => true,
            'data_documento'    => $data_documento
        ]);
    }

    public function correlativo($idcaja,$tipodocumento)
    {
        $Caja = new Caja();
        $data_documento = $Caja->Traer_Serie_Correlativo($idcaja,$tipodocumento);

        $nrodocumento_temp = (int)$data_documento[0]['NroDocumento'] + 1;
        $nrodocumento_temp = (string)$nrodocumento_temp;

        $contador = strlen($nrodocumento_temp);
        $ceros = 8 - $contador;
        $nuevo_ceros = '';
        for ($i=0; $i < $ceros; $i++) { 
            $nuevo_ceros .= '0';
        }
        
        $nrodocumento = $nuevo_ceros . $nrodocumento_temp;

        $data_documento[0] =  array(
            'NroSerie' => $data_documento[0]['NroSerie'],
            'NroDocumento' => $nrodocumento
        );

        return response()->json(['data' => $data_documento]);
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
            
            if (count($data) <= 0) {
                $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[1],$parametro);
            }
            // else{
            //     $data2 = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[1],$parametro);
            //     // $data = array_merge($data, $data2);
            //     if (count($data2) <= 0) {
            //         # code...
            //     }
            // }         
        }
        else{
            $TipoBusqueda = [1,3];
            $filtro = "and FactCatalogoServiciosHosp.IdTipoFinanciamiento = " . $seguro . " and FactCatalogoServicios.Codigo = '" . $parametro . "'";

            $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[0],$filtro);
            // if (count($data) == 0) {
            //     $data = $caja->Medicamentos_Servicios_Filtrados($TipoBusqueda[1],$parametro);
            // }
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
                    'IdPartida' => $data[$i]['codPartida'],
                    'Precio'    => str_replace(' ','',number_format($data[$i]['PrecioUnitario'],2,'.',' ')),
                    'Subtotal'  => str_replace(' ','',number_format($data[$i]['Precio'],2,'.',' ')) ,
                    'Impuesto'  => str_replace(' ','',number_format($data[$i]['IGV'],2,'.',' '))
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
        $verificacion = $caja->Buscar_Boleta_Factura($serio . '-' . (int)$ndocumento);


        if (count($verificacion) > 0) {
            return response()->json(['data' => 'yafacturada']);
        }

        else {
            // $caja = new Caja();
            // $data = $caja->Datos_X_Codigo_Para_Facturar($serio,$ndocumento,$idOrden);
            $data = $caja->Datos_X_Codigo_Para_Facturar($serio,$ndocumento,'0');

            if (count($data) > 0) {
                $paciente       = $data[0]['RazonSocial'];
                $idpaciente     = $data[0]['IdPaciente'];
                $subtotal       = str_replace(' ','',number_format($data[0]['SubTotal'],2,'.',' '));
                $igv            = str_replace(' ','',number_format($data[0]['IGV'],2,'.',' '));
                $total          = str_replace(' ','',number_format($data[0]['Total'],2,'.',' '));
                $comprobante    = strtoupper($serio . '-' . $data[0]['Comprobante']);

                for ($i=0; $i < count($data); $i++) { 
                    $productos[] = array(
                        'Comprobante'   => strtoupper($serio . '-' . $data[$i]['Comprobante']),
                        'Codigo'        => $data[$i]['Codigo'],
                        'Producto'      => $data[$i]['Producto'],
                        'Cantidad'      => $data[$i]['Cantidad'],
                        'IdPartida'     => $data[$i]['CodigoPresupuestal'],
                        'Precio'        => number_format($data[$i]['Precio'],2,'.',' '),
                        'Subtotal'      => number_format($data[$i]['Precio'] * $data[$i]['Cantidad'],2,'.',' ') ,
                        'Impuesto'      => number_format($data[$i]['IGVUNITARIO'] * $data[$i]['Cantidad'],2,'.',' '),
                        'TotalUnitario' => number_format($data[$i]['TotalUnitario'],2,'.',' ')
                    );
                }

                $response = array(
                    'paciente'      => $paciente,
                    'idpaciente'    => $idpaciente,
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
    }

    public function buscar_boleta_x_cuenta($cuenta)
    {
        $caja = new Caja();

        $verificacion = $caja->Buscar_Boleta_Factura($cuenta);

        if (count($verificacion) > 0) {
            return response()->json(['data' => 'yafacturada']);
        }

        else{
            $data_cabecera = $caja->Datos_X_Cuenta_Cabecera($cuenta);
            $data_detalle =  $caja->Datos_X_Cuenta_Detalle($cuenta);

            if (count($data_cabecera) > 0) {
                $paciente       = $data_cabecera[0]['ApellidoPaterno'] . ' ' . $data_cabecera[0]['ApellidoMaterno'] . ' ' . $data_cabecera[0]['PrimerNombre'];
                $idpaciente     = $data_cabecera[0]['IdPaciente'];
                $idSeguro       = $data_cabecera[0]['idFuenteFinanciamiento'];
                $seguro         = $data_cabecera[0]['dFuenteFinanciamiento'];

                for ($i=0; $i < count($data_detalle); $i++) { 
                    $productos[] = array(
                        'Comprobante'       => $data_detalle[$i]['IdCuentaAtencion'],
                        'Codigo'            => $data_detalle[$i]['Codigo'],
                        'Producto'          => strtoupper($data_detalle[$i]['Nombre']),
                        'Cantidad'          => $data_detalle[$i]['cantidad'],
                        'IdPartida'         => $data_detalle[$i]['codPartida'],
                        'Precio'            => number_format($data_detalle[$i]['PrecioUnitario'],2,'.',' '),
                        'SubTotal'          => number_format($data_detalle[$i]['SubTotal'],2,'.',' '),
                        'Impuesto'          => number_format($data_detalle[$i]['Impuesto'],2,'.',' '),
                        'TotalUnitario'     => number_format($data_detalle[$i]['Total'],2,'.',' ')
                    );
                }

                $response = array(
                    'paciente'      => $paciente,
                    'idpaciente'    => $idpaciente,
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

    public function registro_factura(Request $request)
    {
        
        
        // cabecera
        $FechaCobranza      = date('Y-m-d H:i:s') . '.000';
        $NroSerie           = $request->NroSerie;
        $NroDocumento       = $request->NroDocumento;
        $Ruc                = $request->Ruc;
        $RazonSocial        = $request->RazonSocial;
        $IdTipoComprobante  = $request->IdTipoComprobante;
        $IdCajero           = session()->get('id_empleado');
        $Subtotal           = $request->Subtotal;
        $IGV                = $request->IGV;
        $Total              = $request->Total;
        $IdPaciente         = $request->IdPaciente;
        $Observacion1       = $request->Observacion1;
        $Observacion2       = $request->Observacion2;
        $Concepto           = $request->Concepto;
        $IdCuentaAtencion   = $request->IdCuentaAtencion;
        $IdCaja             = $request->idcaja;
        $productos          = $request->productos;

        $caja = new Caja();
        $sistema = new Sistema();
 
        // Obteniendo tipo de comprobante
        $IdTipoComprobante_temp = $sistema->Obtener_Tipo_Comprobante($IdTipoComprobante);

        $IdTipoComprobante = strtoupper($IdTipoComprobante_temp[0]['Descripcion']);
        $IdTipoComprobante = $IdTipoComprobante[0];
        
        $id_cabecera = $caja->Generar_Factura_Cabecera($FechaCobranza,$NroSerie,$NroDocumento,$Ruc,$RazonSocial,$IdTipoComprobante,$IdCajero,$Subtotal,$IGV,$Total,$IdPaciente,$Observacion1,$Observacion2,$Concepto);

        // todo correcto
        if ($id_cabecera > 0) {
            // se genera el detalle
            for ($i=0; $i < count($productos); $i++) { 
                // $caja->Generar_Factura_Detalle($id_cabecera,$IdCuentaAtencion,$IdPartida,$Codigo,$Cantidad,$ValorUnitario,$SubTotal,$IGV,$Total)

                if($productos[$i]['Codigo'] == $productos[$i]['Comprobante']) {$comprobante_dp = '';}
                else {$comprobante_dp = $productos[$i]['Comprobante'];}

                $caja->Generar_Factura_Detalle($id_cabecera,$IdCuentaAtencion,$productos[$i]['IdPartida'],$productos[$i]['Codigo'],$productos[$i]['Producto'],$productos[$i]['Cantidad'],$productos[$i]['Precio'],$productos[$i]['Subtotal'],$productos[$i]['Impuesto'],$productos[$i]['TotalUnitario'],$comprobante_dp);
            }

            // se actualiza Número de Documento
            $caja->Actualizar_Nro_Documento($IdCaja,$NroSerie,$NroDocumento);

            // $generar_pdf = new PDFComprobantesController();
            // $generar_pdf->generar($id_cabecera);
            return response()->json(['data' => 'correcto','idcabecera' => $id_cabecera]);
        }

        // problemas a la hora de generar
        else {
            return response()->json(['data' => 'sindatos']);
        }
    }

    public function cierre_caja($IdGestionCaja,$EstadoLote,$FechaCierre,$TotalCobrado)
    {
        $FechaCierreServer = date('Y-m-d H:i:s') . '.000';
        $Caja =new Caja();
        $Caja->Cierre_Caja($IdGestionCaja,$EstadoLote,$FechaCierreServer,$TotalCobrado);
    }

    public function datos_orden_farmacia($idorder)
    {
        
        $idorder = substr($idorder, 0, -1);

        $Caja =new Caja();
        $data_cabecera = $Caja->Datos_farmacia_x_orden_cabecera($idorder);
        $data_detalle = $Caja->Datos_farmacia_x_orden_detalle($idorder);

        if (count($data_cabecera) > 0) 
        { 
            
            $paciente       = $data_cabecera[0]['nombpaciente'];
            $idpaciente     = $data_cabecera[0]['idPaciente'];
            $subtotal       = number_format(0,2,'.',' ');
            $igv            = number_format(0,2,'.',' ');
            $total          = number_format(0,2,'.',' ');
            $comprobante    = $data_cabecera[0]['idPreventa'];

            for ($i=0; $i < count($data_detalle); $i++) { 
                $productos[] = array(
                    'Comprobante'       => $data_detalle[$i]['idPreventa'],
                    'Codigo'            => $data_detalle[$i]['Codigo'],
                    'Producto'          => strtoupper($data_detalle[$i]['Nombre']),
                    'Cantidad'          => $data_detalle[$i]['Cantidad'],
                    'IdPartida'         => '',
                    'Precio'            => number_format($data_detalle[$i]['Precio'],2,'.',' '),
                    'Impuesto'          => number_format(($data_detalle[$i]['Precio'] - ($data_detalle[$i]['Precio'] / 1.18)) * $data_detalle[$i]['Cantidad'],2,'.',' '),
                    'SubTotal'          => number_format(($data_detalle[$i]['Precio'] / 1.18) * $data_detalle[$i]['Cantidad'],2,'.',' '),
                    'TotalUnitario'     => number_format($data_detalle[$i]['Importe'],2,'.',' ')
                );

                $subtotal_temp = number_format(($data_detalle[$i]['Precio'] / 1.18) * $data_detalle[$i]['Cantidad'],2,'.',' ');
                $igv_temp = number_format(($data_detalle[$i]['Precio'] - ($data_detalle[$i]['Precio'] / 1.18)) * $data_detalle[$i]['Cantidad'],2,'.',' ');
                $total_temp = number_format($data_detalle[$i]['Importe'],2,'.',' ');

                $subtotal = $subtotal + $subtotal_temp;
                $igv = $igv + $igv_temp;
                $total = $total + $total_temp;
            }

            $response = array(
                'paciente'      => $paciente,
                'idpaciente'    => $idpaciente,
                'subtotal'      => number_format($subtotal,2,'.',' '),
                'igv'           => number_format($igv,2,'.',' '),
                'total'         => number_format($total,2,'.',' '),
                'comprobante'   => $comprobante,
                'productos'     => $productos
            );

            return response()->json(['data' => $response]);
        }
        else {
            return response()->json(['data' => 'sindatos']);
        }
    }

    public function datos_orden_caja($idorden)
    {
        $Caja =new Caja();
        $data_cabecera = $Caja->Datos_Cajas_x_Orden($idorden,'0');
        $data_detalle = $Caja->Datos_Cajas_x_Orden($idorden,'1');

        if (count($data_cabecera) > 0) 
        {
            $paciente       = $data_cabecera[0]['nombpaciente'];
            $idpaciente     = $data_cabecera[0]['idPaciente'];
            $subtotal       = number_format(0,2,'.',' ');
            $igv            = number_format(0,2,'.',' ');
            $total          = number_format(0,2,'.',' ');
            $comprobante    = $data_cabecera[0]['idOrdenPago'];

            for ($i=0; $i < count($data_detalle); $i++) { 
                $productos[] = array(
                    'Comprobante'       => $data_detalle[$i]['idOrdenPago'],
                    'Codigo'            => $data_detalle[$i]['Codigo'],
                    'Producto'          => strtoupper($data_detalle[$i]['Nombre']),
                    'Cantidad'          => $data_detalle[$i]['Cantidad'],
                    'IdPartida'         => '',
                    'Precio'            => number_format($data_detalle[$i]['Precio'],2,'.',' '),
                    'Impuesto'          => number_format(0,2,'.',' '),
                    'SubTotal'          => number_format($data_detalle[$i]['Precio'] * $data_detalle[$i]['Cantidad'],2,'.',' '),
                    'TotalUnitario'     => number_format($data_detalle[$i]['Total'],2,'.',' ')
                );

                $subtotal_temp = number_format($data_detalle[$i]['Precio'] * $data_detalle[$i]['Cantidad'],2,'.',' ');
                $igv_temp = number_format(0,2,'.',' ');
                $total_temp = number_format($data_detalle[$i]['Total'],2,'.',' ');

                $subtotal = $subtotal + $subtotal_temp;
                $igv = $igv + $igv_temp;
                $total = $total + $total_temp;
            }

            $response = array(
                'paciente'      => $paciente,
                'idpaciente'    => $idpaciente,
                'subtotal'      => number_format($subtotal,2,'.',' '),
                'igv'           => number_format($igv,2,'.',' '),
                'total'         => number_format($total,2,'.',' '),
                'comprobante'   => $comprobante,
                'productos'     => $productos
            );

            return response()->json(['data' => $response]);

        } else {
            return response()->json(['data' => 'sindato']);
        }
    }

    public function nuevo_protocolo($nombre,$precio)
    {
        $mantenimiento = new Mantenimiento();
        $data = $mantenimiento->Obtener_Ultimo_Protocolo();

        $ultimo_codigo_protocolo = str_replace('PRT', '', $data[0]['Codigo']);
        $nuevo_codigo_protocolo = $ultimo_codigo_protocolo + 1;
        $nuevo_codigo_protocolo = 'PRT' . $nuevo_codigo_protocolo;

        // echo $nuevo_codigo_protocolo . ' | ' . strtoupper($nombre);exit();

        $data_cabecera = $mantenimiento->Nuevo_Protocolo_Cabecera($nuevo_codigo_protocolo,strtoupper($nombre));

        if ($data_cabecera > 1) {
            $data_detalle = $mantenimiento->Nuevo_Protocolo_Detalle($data_cabecera,$precio);

            if ($data_detalle > 1) {
                return response()->json(
                    [
                        'data'      => 'Protocolo registrado con éxito.',
                        'codigo'    => 1
                    ]
                );
            } else {
                return response()->json(
                    [
                        'data'      => 'No se pudo registrar precio, contacte a sistemas por favor <br> CODIGO: ' . $data_cabecera,
                        'codigo'    => 2
                    ]
                );
            }
        }
        else {
            return response()->json(
                [
                    'data'      => 'No se pudo registrar, intentenlo nuevamente.',
                    'codigo'    => 2
                ]
            );
        }
    }

    public function Crear_Nuevo_Proveedor($Ruc,$RazonSocial,$Direccion)
    {
        $sistema = new Sistema();
        $idProveedor = $sistema->Insertar_Proveedor($Ruc,$RazonSocial,$Direccion);

        if ($idProveedor > 0) {
            $data = $sistema->Obtener_Proveedor_id($idProveedor);

            $response = array(
                'idProveedor'   => $data[0]['idProveedor'],
                'Ruc'           => $data[0]['Ruc'],
                'RazonSocial'   => strtoupper($data[0]['RazonSocial']),
                'Direccion'     => strtoupper($data[0]['Direccion'])
            );

            return response()->json(
                [
                    'data'      => $response,
                    'codigo'    => 1
                ]
            );

        } else {
            return response()->json(
                [
                    'data'      => 'No se pudo registrar, intentenlo nuevamente.',
                    'codigo'    => 2
                ]
            );
        }
    }

    public function Listar_Facturas_Reportes($FechaInicio,$FechaFin)
    {
        $IdCajero = session()->get('id_empleado');
        $Caja = new Caja();
        $data = $Caja->Listar_Facturas($IdCajero,$FechaInicio . ' 00:00:00',$FechaFin . ' 23:59:59');
        
        if (count($data) > 0) {
            for ($i=0; $i < count($data); $i++) { 
                $response[] = array(
                    'IDFACTURA'         => $data[$i]['IDFACTURA'],
                    'TIPOCOMPROBANTE'   => $data[$i]['TIPOCOMPROBANTE'],
                    'FECHA'             => $data[$i]['FECHA'],
                    'HORA'              => $data[$i]['HORA'],
                    'SERIE'             => $data[$i]['NroSerie'],
                    'DOCUMENTO'         => $data[$i]['NroDocumento'],
                    'NCOMPROBANTE'      => $data[$i]['NroSerie'] . '-' . $data[$i]['NroDocumento'],
                    'RUC'               => $data[$i]['Ruc'],
                    'RAZONSOCIAL'       => $data[$i]['RazonSocial'],
                    'SUBTOTAL'          => number_format($data[$i]['Subtotal'],2,'.',' '),
                    'IGV'               => number_format($data[$i]['IGV'],2,'.',' '),
                    'TOTAL'             => number_format($data[$i]['Total'],2,'.',' '),
                    'ESTADO'            => $data[$i]['ESTADO']
                );
            }

            return response()->json([ 'data'      => $response]);
        } else {
            return response()->json([ 'data'      => 'sindatos' ]);
        }
    }

    public function Eliminacion_Facturas_Reporte($IdCajaFacturacion)
    {
        $IdCajero = session()->get('id_empleado');
        $Caja = new Caja();
        $data = $Caja->Eliminar_Factura($IdCajero,$IdCajaFacturacion);
    }
}
