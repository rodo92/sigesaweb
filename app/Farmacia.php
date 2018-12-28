<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Farmacia extends Model
{
    public function ICI_Formato($fechainicio, $fechafin)
    {
        $result = DB::select('exec SIGESA_TRAMA_ICI_FORMATO ?,?', [$fechainicio, $fechafin]);

        return json_decode(json_encode($result), true);
    }

    public function ICI_Formdet($fechainicio, $fechafin)
    {
        $result = DB::select('exec SIGESA_TRAMA_ICI_FORMDET ?,?', [$fechainicio, $fechafin]);

        return json_decode(json_encode($result), true);
    }

    public function ICI_Formdtlm($fechainicio, $fechafin)
    {
        $result = DB::select('exec SIGESA_TRAMA_ICI_FORMATLM ?,?', [$fechainicio, $fechafin]);

        return json_decode(json_encode($result), true);
    }

    public function Mostrar_Almacenes_Farmacia()
    {
        $result = DB::select('exec SIGESA_FARMACIA_ALMACENES');

        return json_decode(json_encode($result), true);
    }

    public function Mostrar_Farmacias($tipo)
    {
        $result = DB::table('farmAlmacen')
                ->where('farmAlmacen.idTipoLocales','=',$tipo)
                ->select('farmAlmacen.*')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Reporte_Almacen_Traslado($fechainicio, $fechafin, $idAlmacen)
    {
        $result = DB::select('exec SIGESA_FARMACIA_REPORTES_ALMACEN_TRASLADO ?,?,?', [$fechainicio, $fechafin, $idAlmacen]);

        return json_decode(json_encode($result), true);
    }

    public function Reporte_Almacen_Ingresos_Almacen($fechainicio, $fechafin, $idProveedor)
    {
        $result = DB::select('exec SIGESA_FARMACIA_INGRESOS_ALMACEN ?,?,?', [$fechainicio, $fechafin, $idProveedor]);

        return json_decode(json_encode($result), true);
    }

    public function Reporte_Almacen_Entrada_Salida_Documentos($fechainicio, $fechafin, $idAlmacenOrigen, $movTipo)
    {
        $result = DB::select('exec SIGESA_FARMACIA_REPORTE_ES_DOCUMENTOS ?,?,?,?', [$fechainicio, $fechafin, $movTipo, $idAlmacenOrigen]);

        return json_decode(json_encode($result), true);
    }

    public function Reporte_Almacen_Reporte_Por_Usuario($fechainicio, $fechafin, $idAlmacenOrigen)
    {
        $result = DB::select('exec SIGESA_FARMACIA_REPORTE_POR_USUARIO ?,?,?', [$fechainicio, $fechafin, $idAlmacenOrigen]);

        return json_decode(json_encode($result), true);
    }
}
