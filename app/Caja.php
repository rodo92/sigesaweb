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
    	$result = DB::insert('exec SIGESA_Apertura_Caja ?,?,?,?,?,?', [$FechaApertura,$EstadoLote,$IdCaja,$IdTurno,$TotalCobrado,$IdCajero]);

    	return json_decode(json_encode($result), true);
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

    public function Generar_Factura_Cabecera($FechaCobranza,$NroSerie,$NroDocumento,$Ruc,$RazonSocial,$IdTipoComprobante,$IdCajero,$Subtotal,$IGV,$Total,$IdPaciente,$Observacion1,$Observacion2)
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
                'Observacion2'      => $Observacion2
            );
        $IdCajaFacturacion = DB::table('CajaFacturacion')->insertGetId($datos);
        // exit();
        return json_decode(json_encode($IdCajaFacturacion), true);
    }

    public function Generar_Factura_Detalle($IdCajaFacturacion,$IdCuentaAtencion,$IdPartida,$Codigo,$Cantidad,$ValorUnitario,$SubTotal,$IGV,$Total)
    {
        $datos = array(
                'IdCajaFacturacion' => $IdCajaFacturacion,
                'IdCuentaAtencion'  => $IdCuentaAtencion,
                'IdPartida'         => $IdPartida,
                'Codigo'            => $Codigo,
                'Cantidad'          => $Cantidad,
                'ValorUnitario'     => $ValorUnitario,
                'SubTotal'          => $SubTotal,
                'IGV'               => $IGV,
                'Total'             => $Total
            );
        DB::table('CajaFacturacionDetalle')->insert($datos);
    }
}
