<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mantenimiento extends Model
{
    // ingreso de nuevos protocolos para facturacion electronica - caja economia

    public function Obtener_Ultimo_Protocolo()
    {
        $result = DB::table('FactCatalogoServicios')
                ->select('Codigo')
                ->where('Codigo','like','%AD%')
                ->orderBy('Codigo','desc')
                ->take(1)
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Nuevo_Protocolo_Cabecera($codigo,$nombre)
    {
    	$datos = array(
            'Codigo' => $codigo,
			'Nombre' => $nombre,
			'IdServicioGrupo' => 5,
			'IdServicioSubGrupo' => 24,
			'IdServicioSeccion' => 78,
			'IdPartida' => 1060,
			'IdCentroCosto' => 999,
			'CodMINSA' => '',
			'CodMINSAnoActualiza' => 0,
			'EsCPT' => 0,
			'NombreMINSA' => $nombre,
			'idEstado' => 1,
			'codigoSIS' => $codigo
            );

        $idProtocolo = DB::table('FactCatalogoServicios')->insertGetId($datos);
        return json_decode(json_encode($idProtocolo), true);
    }

    public function Nuevo_Protocolo_Detalle($IdProducto,$precio)
    {
    	$datos = array(
            'PrecioUnitario' => $precio,
			'IdProducto' => $IdProducto,
			'IdTipoFinanciamiento' => 1,
			'Activo' => 1,
			'SeUsaSinPrecio' => 1
            );

        $idProtocoloDetalle = DB::table('FactCatalogoServiciosHosp')->insertGetId($datos);
        return json_decode(json_encode($idProtocoloDetalle), true);
    }

    // fin ingreso de nuevos protocolos para facturacion electronica - caja economia
}
