<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Archivo extends Model
{
    public function Reporte_Archivo_Conserjeria($turno, $fecha)
    {
        $result = DB::select('exec SIGESA_ARCHIVO_CONSERJERIA ?,?', [$turno, $fecha]);

        return json_decode(json_encode($result), true);
    }

    public function Reporte_Archivo_Listado_Citas($turno, $fecha, $serieinicial, $seriefinal)
    {
        $result = DB::select('exec SIGESA_ARCHIVO_LISTADO_CITADOS ?,?,?,?', [$turno, $fecha, $serieinicial, $seriefinal]);

        return json_decode(json_encode($result), true);
    }

    public function Archivero_por_Dni($DNI)
    {
        $result = DB::table('Empleados')
                ->where('Empleados.DNI','=',$DNI)
                ->select('Empleados.*')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Listar_Archiveros()
    {
        $result = DB::table('ArchivoDigitoTerminal')
                ->leftJoin('Empleados', 'Empleados.IdEmpleado', '=', 'ArchivoDigitoTerminal.IdEmpleado')
                ->select('ArchivoDigitoTerminal.*','Empleados.ApellidoPaterno','Empleados.ApellidoMaterno','Empleados.Nombres','Empleados.DNI')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Listar_Archiveros_Dni($DNI)
    {
        $result = DB::table('ArchivoDigitoTerminal')
                ->leftJoin('Empleados', 'Empleados.IdEmpleado', '=', 'ArchivoDigitoTerminal.IdEmpleado')
                ->select('ArchivoDigitoTerminal.*','Empleados.ApellidoPaterno','Empleados.ApellidoMaterno','Empleados.Nombres','Empleados.DNI')
                ->where('Empleados.DNI','=',$DNI)
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Elimnar_Archiveros($IdArchivoDigitoTerminal)
    {
        $result = DB::table('ArchivoDigitoTerminal')
                ->where('IdArchivoDigitoTerminal',$IdArchivoDigitoTerminal)->delete();

        return json_decode(json_encode($result), true);
    }

    public function Insertar_Digito_Terminal($DigitoInicial,$DigitoFinal,$IdEmpleado)
    {
        $datos = array(
            'DigitoInicial'  => $DigitoInicial,
            'DigitoFinal'    => $DigitoFinal,
            'IdEmpleado'     => $IdEmpleado,
            'FechaCreacion'  => date('Y-m-d h:i:s')
            );
        $IdArchivoDigitoTerminal = DB::table('ArchivoDigitoTerminal')->insertGetId($datos);
        return json_decode(json_encode($IdArchivoDigitoTerminal), true);
    }
}
