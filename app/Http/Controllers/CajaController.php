<?php

namespace WebSigesa\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use WebSigesa\Caja;
use WebSigesa\Paciente;
use WebSigesa\Sistema;
use Codedge\Fpdf\Fpdf\Fpdf;

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
            'NroDocumento' => $nrodocumento
        );

        // trayendo serie 


        return response()->json([
            'data'              => $data,
            'data_documento'    => $data_documento
        ]);
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
                    'IdPartida' => $data[$i]['codPartida'],
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
                    'Precio'            => number_format($data_detalle[$i]['PrecioUnitario'],4,'.',' '),
                    'SubTotal'          => number_format($data_detalle[$i]['SubTotal'],4,'.',' '),
                    'Impuesto'          => number_format($data_detalle[$i]['Impuesto'],4,'.',' '),
                    'TotalUnitario'     => number_format($data_detalle[$i]['Total'],4,'.',' ')
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
        $IdCuentaAtencion   = $request->IdCuentaAtencion;
        $IdCaja             = $request->idcaja;
        $productos          = $request->productos;

        $caja = new Caja();
        $sistema = new Sistema();
 
        // Obteniendo tipo de comprobante
        $IdTipoComprobante_temp = $sistema->Obtener_Tipo_Comprobante($IdTipoComprobante);

        $IdTipoComprobante = strtoupper($IdTipoComprobante_temp[0]['Descripcion']);
        $IdTipoComprobante = $IdTipoComprobante[0];
        
        $id_cabecera = $caja->Generar_Factura_Cabecera($FechaCobranza,$NroSerie,$NroDocumento,$Ruc,$RazonSocial,$IdTipoComprobante,$IdCajero,$Subtotal,$IGV,$Total,$IdPaciente,$Observacion1,$Observacion2);

        // todo correcto
        if ($id_cabecera > 0) {
            // se genera el detalle
            for ($i=0; $i < count($productos); $i++) { 
                // $caja->Generar_Factura_Detalle($id_cabecera,$IdCuentaAtencion,$IdPartida,$Codigo,$Cantidad,$ValorUnitario,$SubTotal,$IGV,$Total)
                $caja->Generar_Factura_Detalle($id_cabecera,$IdCuentaAtencion,$productos[$i]['IdPartida'],$productos[$i]['Codigo'],$productos[$i]['Cantidad'],$productos[$i]['Precio'],$productos[$i]['Subtotal'],$productos[$i]['Impuesto'],$productos[$i]['TotalUnitario']);
            }

            // se actualiza Número de Documento
            $caja->Actualizar_Nro_Documento($IdCaja,$NroSerie,$NroDocumento);

            // return response()->json(['data' => 'correcto']);
            /*$fpdf = new Fpdf();
            $fpdf->AddPage();
            $fpdf->Image('svg/Logos/logo-factura.jpg',10,8,33);
            $fpdf->SetFont('Helvetica','B',8);
            $fpdf->Cell(35);
            $fpdf->Cell(65,5,'HOSPITAL NACIONAL ARZOBISPO LOAYZA',0,1,'L');
            $fpdf->SetXY(48,15);
            $fpdf->SetFont('Helvetica','',8);
            $fpdf->Cell(65,4,'Av. Alfonso Ugarte 848 - Cercado de Lima',0,1,'L');
            $fpdf->SetXY(57,19);
            $fpdf->Cell(65,4,'Prov. de Lima - Lima - Peru',0,1,'L');
            $fpdf->SetXY(140,12);
            $fpdf->MultiCell(50,4,"FACTURA ELECTRONICA \n R.U.C  Nro. 20154996991\n Nro. FF10-00000050",1,'C');
            $fpdf->Ln(5);
            $fpdf->Cell(0,0,'','B',2,'C');
            $fpdf->Ln(20);

            $fpdf->SetFont('Helvetica','',7);
            $fpdf->SetFillColor(248,249,249);
            $fpdf->SetXY(150,32);
            $fpdf->Cell(26,4,'FECHA DE EMSION :',0, 0, 'L', True);
            $fpdf->SetXY(176,32);
            $fpdf->Cell(25,4,'23/08/2018',0, 0, 'L', True);
            $fpdf->SetXY(150,36);
            $fpdf->Cell(15,4,'C.I.I.U Nro:',0, 0, 'L', True);
            $fpdf->SetXY(176,36);
            $fpdf->Cell(30,4,'85111',0, 0, 'L', True);
            $fpdf->SetXY(150,40);
            $fpdf->Cell(425,4,'TIPO DE MONEDA :',0, 0, 'L', True);
            $fpdf->SetXY(176,40);
            $fpdf->Cell(110,4,'SOLES',0, 0, 'L', True);
            $fpdf->SetXY(10,36);
            $fpdf->Cell(40,4,utf8_decode('SEÑOR(ES) :'),0, 0, 'L', True);
            $fpdf->SetXY(51,36);
            $fpdf->SetFont('Helvetica','B',7);
            $fpdf->Cell(90,4,'ACCESORIOS GENERALES D&J MOTOR\'S EIRL',0, 0, 'L', True);
            $fpdf->SetXY(10,40);
            $fpdf->SetFont('Helvetica','',7);
            $fpdf->Cell(40,4,'R.U.C. :',0, 0, 'L', True);
            $fpdf->SetXY(51,40);
            $fpdf->SetFont('Helvetica','B',7);
            $fpdf->Cell(60,4,'20518324005',0, 0, 'L', True);
            $fpdf->SetFont('Helvetica','',7);
            $fpdf->SetXY(10,44);
            $fpdf->Cell(40,4,'DIRECCION DEL CLIENTE :',0, 0, 'L', True);
            $fpdf->SetXY(51,44);
            $fpdf->SetFont('Helvetica','B',7);
            $fpdf->Cell(110,4,'AV. CENTRAL, POSTA MOTUPE LIMA-LIMA-SAN JUAN DE LURIGANCHO',0, 0, 'L', True);
            $fpdf->SetFont('Helvetica','',7);
            $fpdf->SetXY(10,48);
            $fpdf->Cell(40,4,'PACIENTE :',0, 0, 'L', True);
            $fpdf->SetXY(51,48);
            $fpdf->SetFont('Helvetica','B',7);
            $fpdf->Cell(90,4,'SALCEDO PRADA ERNESTO',0, 0, 'L', True);
            $fpdf->SetFont('Helvetica','',7);
            $fpdf->SetXY(10,52);
            $fpdf->Cell(40,4,'OBSERVACION 1 :',0, 0, 'L', True);
            $fpdf->SetXY(51,52);
            $fpdf->Cell(110,4,'CONTENIDO DE LA OBSERVACION 1',0, 0, 'L', True);
            $fpdf->SetXY(10,56);
            $fpdf->Cell(40,4,'OBSERVACION 2 :',0, 0, 'L', True);
            $fpdf->SetXY(51,56);
            $fpdf->Cell(110,4,'CONTENIDO DE LA OBSERVACION 2',0, 0, 'L', True);
            $fpdf->SetXY(10,63); 

            $fpdf->Ln(5);
            $fpdf->SetFont('Helvetica','B',7);
            $fpdf->Cell(20,5, 'CODIGO',1, 0 , 'C' );
            $fpdf->Cell(15,5, 'CANTIDAD',1, 0 , 'C' );
            $fpdf->Cell(125,5, 'DESCRIPCION',1, 0 , 'L' );
            $fpdf->Cell(10,5, 'IGV',1, 0 , 'C' );
            $fpdf->Cell(20,5, 'SUBTOTAL',1, 0 , 'C' );
            
            $fpdf->SetFont('Helvetica','',7);
            for ($i=0; $i < 10; $i++) { 

                $fpdf->Ln(5);
                $fpdf->Cell(20,5, 'CODIGO' . $i,'LR', 0 , 'C' );
                $fpdf->Cell(15,5, 'CANTIDAD' . $i,'LR', 0 , 'C' );
                $fpdf->Cell(125,5, 'DESCRIPCION' . $i,'LR', 0 , 'L' );
                $fpdf->Cell(10,5, 'IGV' . $i,'LR', 0 , 'C' );
                $fpdf->Cell(20,5, 'SUBTOTAL' . $i,'LR', 0 , 'C' );
            }
            $pathFile = storage_path(). '/factura.pdf';
            $fpdf->Output('F',$pathFile);
            $headers = ['Content-Type' => 'application/pdf'];
            return response()->file($pathFile, $headers);*/

            // generando pdf
            
        }

        // problemas a la hora de generar
        else {
            return response()->json(['data' => 'sindatos']);
        }
    }

    public function generar_pdf_documento()
    {
        $fpdf = new Fpdf();
        $fpdf->AddPage();
        $fpdf->Image('svg/Logos/logo-factura.jpg',10,8,33);
        $fpdf->SetFont('Helvetica','B',8);
        $fpdf->Cell(35);
        $fpdf->Cell(65,5,'HOSPITAL NACIONAL ARZOBISPO LOAYZA',0,1,'L');
        $fpdf->SetXY(48,15);
        $fpdf->SetFont('Helvetica','',8);
        $fpdf->Cell(65,4,'Av. Alfonso Ugarte 848 - Cercado de Lima',0,1,'L');
        $fpdf->SetXY(57,19);
        $fpdf->Cell(65,4,'Prov. de Lima - Lima - Peru',0,1,'L');
        $fpdf->SetXY(140,12);
        $fpdf->MultiCell(50,4,"FACTURA ELECTRONICA \n R.U.C  Nro. 20154996991\n Nro. FF10-00000050",1,'C');
        $fpdf->Ln(5);
        $fpdf->Cell(0,0,'','B',2,'C');
        $fpdf->Ln(20);

        $fpdf->SetFont('Helvetica','',7);
        $fpdf->SetFillColor(248,249,249);
        $fpdf->SetXY(150,32);
        $fpdf->Cell(26,4,'FECHA DE EMSION :',0, 0, 'L', True);
        $fpdf->SetXY(176,32);
        $fpdf->Cell(25,4,'23/08/2018',0, 0, 'L', True);
        $fpdf->SetXY(150,36);
        $fpdf->Cell(15,4,'C.I.I.U Nro:',0, 0, 'L', True);
        $fpdf->SetXY(176,36);
        $fpdf->Cell(30,4,'85111',0, 0, 'L', True);
        $fpdf->SetXY(150,40);
        $fpdf->Cell(425,4,'TIPO DE MONEDA :',0, 0, 'L', True);
        $fpdf->SetXY(176,40);
        $fpdf->Cell(110,4,'SOLES',0, 0, 'L', True);
        $fpdf->SetXY(10,36);
        $fpdf->Cell(40,4,utf8_decode('SEÑOR(ES) :'),0, 0, 'L', True);
        $fpdf->SetXY(51,36);
        $fpdf->SetFont('Helvetica','B',7);
        $fpdf->Cell(90,4,'ACCESORIOS GENERALES D&J MOTOR\'S EIRL',0, 0, 'L', True);
        $fpdf->SetXY(10,40);
        $fpdf->SetFont('Helvetica','',7);
        $fpdf->Cell(40,4,'R.U.C. :',0, 0, 'L', True);
        $fpdf->SetXY(51,40);
        $fpdf->SetFont('Helvetica','B',7);
        $fpdf->Cell(60,4,'20518324005',0, 0, 'L', True);
        $fpdf->SetFont('Helvetica','',7);
        $fpdf->SetXY(10,44);
        $fpdf->Cell(40,4,'DIRECCION DEL CLIENTE :',0, 0, 'L', True);
        $fpdf->SetXY(51,44);
        $fpdf->SetFont('Helvetica','B',7);
        $fpdf->Cell(110,4,'AV. CENTRAL, POSTA MOTUPE LIMA-LIMA-SAN JUAN DE LURIGANCHO',0, 0, 'L', True);
        $fpdf->SetFont('Helvetica','',7);
        $fpdf->SetXY(10,48);
        $fpdf->Cell(40,4,'PACIENTE :',0, 0, 'L', True);
        $fpdf->SetXY(51,48);
        $fpdf->SetFont('Helvetica','B',7);
        $fpdf->Cell(90,4,'SALCEDO PRADA ERNESTO',0, 0, 'L', True);
        $fpdf->SetFont('Helvetica','',7);
        $fpdf->SetXY(10,52);
        $fpdf->Cell(40,4,'OBSERVACION 1 :',0, 0, 'L', True);
        $fpdf->SetXY(51,52);
        $fpdf->Cell(110,4,'CONTENIDO DE LA OBSERVACION 1',0, 0, 'L', True);
        $fpdf->SetXY(10,56);
        $fpdf->Cell(40,4,'OBSERVACION 2 :',0, 0, 'L', True);
        $fpdf->SetXY(51,56);
        $fpdf->Cell(110,4,'CONTENIDO DE LA OBSERVACION 2',0, 0, 'L', True);
        $fpdf->SetXY(10,63); 

        $fpdf->Ln(5);
        $fpdf->SetFont('Helvetica','B',7);
        $fpdf->Cell(20,5, 'CODIGO',1, 0 , 'C' );
        $fpdf->Cell(15,5, 'CANTIDAD',1, 0 , 'C' );
        $fpdf->Cell(125,5, 'DESCRIPCION',1, 0 , 'L' );
        $fpdf->Cell(10,5, 'IGV',1, 0 , 'C' );
        $fpdf->Cell(20,5, 'SUBTOTAL',1, 0 , 'C' );
        
        $fpdf->SetFont('Helvetica','',7);
        for ($i=0; $i < 10; $i++) { 

            $fpdf->Ln(5);
            $fpdf->Cell(20,5, 'CODIGO' . $i,'LR', 0 , 'C' );
            $fpdf->Cell(15,5, 'CANTIDAD' . $i,'LR', 0 , 'C' );
            $fpdf->Cell(125,5, 'DESCRIPCION' . $i,'LR', 0 , 'L' );
            $fpdf->Cell(10,5, 'IGV' . $i,'LR', 0 , 'C' );
            $fpdf->Cell(20,5, 'SUBTOTAL' . $i,'LR', 0 , 'C' );
        }
        
        $fpdf->Output();
        exit;
    }
}
