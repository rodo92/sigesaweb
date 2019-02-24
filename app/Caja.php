<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Caja extends Model
{
    public function Mostrar_Cajas()
    {
        $result = DB::select('exec SIGESA_CajaCajaSeleccionarTodos');

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Caja($IdCaja)
    {
        $result = DB::table('CajaCaja')
                ->where('IdCaja','=',$IdCaja)->get();

        return json_decode(json_encode($result), true);
    }

    public function Mostrar_Cajas_Tipo_Comprobante()
    {
        $result = DB::select('exec SIGESA_CajaCajaTipoComprobante');

        return json_decode(json_encode($result), true);
    }

    public function Apertura_Caja($FechaApertura,$EstadoLote,$IdCaja,$IdTurno,$TotalCobrado,$IdCajero)
    {
    	$datos = array(
            'FechaApertura' => $FechaApertura,
            'EstadoLote'    => $EstadoLote,
            'IdCaja'        => $IdCaja,
            'IdTurno'       => $IdTurno,
            'TotalCobrado'  => $TotalCobrado,
            'IdCajero'      => $IdCajero
            );
        $IdGestionCaja = DB::table('CajaGestion')->insertGetId($datos);
        return json_decode(json_encode($IdGestionCaja), true);
    }

    public function Cierre_Caja($IdGestionCaja,$EstadoLote,$FechaCierre,$TotalCobrado)
    {
        $datos = array(
            // 'IdGestionCaja' => $IdGestionCaja,
            'EstadoLote'    => $EstadoLote,
            'FechaCierre'   => $FechaCierre,
            'TotalCobrado'  => $TotalCobrado
        );

        DB::table('CajaGestion')
            ->where('IdGestionCaja', $IdGestionCaja)
            ->update($datos);
    }

    public function Actualizar_Nro_Documento($IdCaja,$NroSerie,$NroDocumento)
    {
        DB::table('CajaNroDocumento')
            ->where('IdCaja', $IdCaja)
            ->where('NroSerie', $NroSerie)
            ->update(['NroDocumento' => $NroDocumento]);
    }

    public function Traer_Serie_Correlativo($IdCaja,$TipoDocumento)
    {
        $result = DB::table('CajaNroDocumento')
                ->select(['NroSerie', 'NroDocumento'])
                ->where('IdCaja','=',$IdCaja)
                ->where('IdTipoComprobante','=',$TipoDocumento)->get();

        return json_decode(json_encode($result), true);
    }

    public function Medicamentos_Servicios_Filtrados($tipobusqueda,$filtro)
    {
        $result = DB::select('exec SIGESA_MEDICAMENTOS_SERVICIOS_FACTURACION_CAJA ?,?', [$tipobusqueda,$filtro]);

        return json_decode(json_encode($result), true);
    }

    public function Datos_X_Codigo_Para_Facturar($serio,$ndocumento,$idOrden)
    {
        $result = DB::select('exec SIGESA_CAJA_DATOS_X_CODIGO_PARA_FACTURACION ?,?,?', [$serio,$ndocumento,$idOrden]);

        return json_decode(json_encode($result), true);
    }

    public function Datos_X_Cuenta_Cabecera($cuenta)
    {
        $result = DB::select('exec SIGESA_DATOS_FACTURACION_X_CUENTA_CABECERA ?', [$cuenta]);

        return json_decode(json_encode($result), true);
    }

    public function Datos_X_Cuenta_Detalle($cuenta)
    {
        $result = DB::select('exec SIGESA_DATOS_FACTURACION_X_CUENTA_DETALLE ?', [$cuenta]);

        return json_decode(json_encode($result), true);
    }

    public function Generar_Factura_Cabecera($FechaCobranza,$NroSerie,$NroDocumento,$Ruc,$RazonSocial,$IdTipoComprobante,$IdCajero,$Subtotal,$IGV,$Total,$IdPaciente,$Observacion1,$Observacion2,$Concepto)
    {
        $datos = array(
                'FechaCobranza'     => $FechaCobranza,
                'NroSerie'          => $NroSerie,
                'NroDocumento'      => $NroDocumento,
                'Ruc'               => $Ruc,
                'RazonSocial'       => $RazonSocial,
                'IdTipoComprobante' => $IdTipoComprobante,
                'IdCajero'          => $IdCajero,
                'Subtotal'          => $Subtotal,
                'IGV'               => $IGV,
                'Total'             => $Total,
                'IdPaciente'        => $IdPaciente,
                'Observacion1'      => $Observacion1,
                'Observacion2'      => $Observacion2,
                'Concepto'          => $Concepto
            );
        $IdCajaFacturacion = DB::table('CajaFacturacion')->insertGetId($datos);
        // exit();
        return json_decode(json_encode($IdCajaFacturacion), true);
    }

    public function Generar_Factura_Detalle($IdCajaFacturacion,$IdCuentaAtencion,$IdPartida,$Codigo,$Descripcion,$Cantidad,$ValorUnitario,$SubTotal,$IGV,$Total,$DocumentoProcedencia)
    {
        $datos = array(
                'IdCajaFacturacion'     => $IdCajaFacturacion,
                'IdCuentaAtencion'      => $IdCuentaAtencion,
                'IdPartida'             => $IdPartida,
                'Codigo'                => $Codigo,
                'Descripcion'           => $Descripcion,
                'Cantidad'              => $Cantidad,
                'ValorUnitario'         => $ValorUnitario,
                'SubTotal'              => $SubTotal,
                'IGV'                   => $IGV,
                'Total'                 => $Total,
                'DocumentoProcedencia'  => $DocumentoProcedencia
            );
        DB::table('CajaFacturacionDetalle')->insert($datos);
    }

    public function Obtener_Datos_Factura_Cabecera_Id($IdCajaFacturacion)
    {
        $result = DB::table('CajaFacturacion')
                ->leftJoin('Pacientes', 'Pacientes.IdPaciente', '=', 'CajaFacturacion.IdPaciente')
                ->select('CajaFacturacion.*', 'Pacientes.ApellidoPaterno', 'Pacientes.ApellidoMaterno','Pacientes.PrimerNombre')
                ->where('IdCajaFacturacion','=',$IdCajaFacturacion)
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Datos_Factura_Detalle_Id($IdCajaFacturacion)
    {
        $result = DB::table('CajaFacturacionDetalle')
                ->where('IdCajaFacturacion','=',$IdCajaFacturacion)
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Buscar_Boleta_Factura($IdCuentaAtencion)
    {
        $result = DB::table('CajaFacturacionDetalle')
                ->where('DocumentoProcedencia','=',trim($IdCuentaAtencion))
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Obtener_Partidas_Presupuestales()
    {
        $result = DB::table('FactPartidasPresupuestales')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Datos_farmacia_x_orden_cabecera($idOrden)
    {
        $result = DB::select('exec SIGESA_farmPreVentaSeleccionarPorId ?', [$idOrden]);

        return json_decode(json_encode($result), true);
    }

    public function Datos_farmacia_x_orden_detalle($idOrden)
    {
        $result = DB::select('exec SIGESA_FarmPreVentaDetalleDevuelveTodosItems ?', [$idOrden]);

        return json_decode(json_encode($result), true);
    }

    public function Datos_Cajas_x_Orden($idorden,$tipo)
    {
        $result = DB::select('exec SIGESA_CAJA_DATOS_X_ORDEN ?,?', [$idorden,$tipo]);

        return json_decode(json_encode($result), true);
    }

    public function Listar_Facturas($idCajero,$FechaInicio,$FechaFin)
    {
        $result = DB::select('exec SIGESA_CAJAS_REPORTES ?,?,?', [$idCajero,$FechaInicio,$FechaFin]);

        return json_decode(json_encode($result), true);
    }

    public function Eliminar_Factura($idCajero,$IdCajaFacturacion)
    {
        $datos = array(
            'IdUsuarioActualiza' => $idCajero,
            'Estado'            => 'A'
        );

        DB::table('CajaFacturacion')
            ->where('IdCajaFacturacion', $IdCajaFacturacion)
            ->update($datos);
    }

    public function Listar_Cajeros()
    {
        $result = DB::select('exec CajerosSeleccionarTodos');

        return json_decode(json_encode($result),true);
    }

    public function reporte_resumen_por_cajeros($fechainicio,$fechafin,$idcajero)
    {
        $result = DB::select('exec Hnal_resumen_cajero ?,?,?', [$fechainicio,$fechafin,$idcajero]);

        return json_decode(json_encode($result),true);
    }
}
