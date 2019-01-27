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

    public function Listado_Historia_Archivero_Digito_Terminal($IdEmpleado, $Fecha)
    {
        // echo $Fecha;exit();
        $result = DB::select('exec SIGESA_HISTORIAS_ARCHIVERO_DIGITOTERMINAL ?,?', [$IdEmpleado, $Fecha]);

        return json_decode(json_encode($result), true);
    }

    public function Listado_Historia_Conserje($fecha, $IdEmpleado)
    {
        // echo $Fecha;exit();
        $result = DB::select('exec SIGESA_HISTORIAS_CONSERJE ?,?', [$fecha, $IdEmpleado]);

        return json_decode(json_encode($result), true);
    }

    public function Listado_Historia_Enrutado($fecha)
    {
        // echo $Fecha;exit();
        $result = DB::select('exec SIGESA_HISTORIAS_ARCHIVERO_ENRUTADO ?', [$fecha]);

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
                ->where('ArchivoDigitoTerminal.Estado','=','1')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Listar_Archiveros_Dni($DNI)
    {
        $result = DB::table('ArchivoDigitoTerminal')
                ->leftJoin('Empleados', 'Empleados.IdEmpleado', '=', 'ArchivoDigitoTerminal.IdEmpleado')
                ->select('ArchivoDigitoTerminal.*','Empleados.ApellidoPaterno','Empleados.ApellidoMaterno','Empleados.Nombres','Empleados.DNI')
                ->where('Empleados.DNI','=',$DNI)
                ->where('ArchivoDigitoTerminal.Estado','=','1')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Elimnar_Archiveros($IdArchivoDigitoTerminal)
    {
        $datos = array(
            'Estado'         => '0'
        );

        DB::table('ArchivoDigitoTerminal')
            ->where('IdArchivoDigitoTerminal', $IdArchivoDigitoTerminal)
            ->update($datos);
    }

    public function Insertar_Digito_Terminal($DigitoInicial,$DigitoFinal,$IdEmpleado)
    {
        $datos = array(
            'DigitoInicial'  => $DigitoInicial,
            'DigitoFinal'    => $DigitoFinal,
            'IdEmpleado'     => $IdEmpleado,
            'Estado'         => '1',
            'FechaCreacion'  => date('Y-m-d h:i:s')
            );
        $IdArchivoDigitoTerminal = DB::table('ArchivoDigitoTerminal')->insertGetId($datos);
        return json_decode(json_encode($IdArchivoDigitoTerminal), true);
    }

    public function Inserta_Nueva_Ruta($nombre)
    {
        $datos = array(
            'Nombre'  => $nombre
            );
        $IdRuta = DB::table('ArchivoRuta')->insertGetId($datos);
        return json_decode(json_encode($IdRuta), true);
    }

    public function Inserta_Nueva_Ruta_Servicios($IdRuta, $IdServicio)
    {
        $datos = array(
            'IdRuta'        => $IdRuta,
            'IdServicio'    => $IdServicio
            );
        $IdRutaServicio = DB::table('ArchivoRutaServicio')->insertGetId($datos);
        return json_decode(json_encode($IdRutaServicio), true);
    }

    public function Inserta_Nueva_Ruta_Conserje($IdRuta,$IdEmpleado)
    {
        $datos = array(
            'IdRuta'            => $IdRuta,
            'IdEmpleado'        => $IdEmpleado,
            'FechaCreacion'     => date('Y-m-d h:i:s')
            );
        $IdRutaConserje = DB::table('ArchivoRutaConserje')->insertGetId($datos);
        return json_decode(json_encode($IdRutaConserje), true);
    }

    public function Listar_Rutas()
    {
        $result = DB::table('ArchivoRuta')
                ->select('ArchivoRuta.*')
                ->orderBy('IdRuta','desc')
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Listar_Rutas_Detalle($IdRuta)
    {
        $result = DB::table('ArchivoRuta')
                ->leftJoin('ArchivoRutaServicio', 'ArchivoRutaServicio.IdRuta', '=', 'ArchivoRuta.IdRuta')
                ->leftJoin('Servicios', 'Servicios.IdServicio', '=', 'ArchivoRutaServicio.IdServicio')
                ->leftJoin('Especialidades', 'Especialidades.IdEspecialidad', '=', 'Servicios.IdEspecialidad')
                ->leftJoin('TiposServicio', 'TiposServicio.IdTipoServicio', '=', 'Servicios.IdTipoServicio')
                ->select('ArchivoRuta.IdRuta','ArchivoRuta.Nombre AS Ruta','ArchivoRutaServicio.IdServicio','Servicios.Nombre AS Servicio','Servicios.IdEspecialidad','Especialidades.Nombre AS Especialidad','Servicios.IdTipoServicio','TiposServicio.Descripcion as TipoServicio')
                ->where('ArchivoRuta.IdRuta','=',$IdRuta)
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Listar_Conserjes_Por_Ruta($IdRuta)
    {
        $result = DB::table('ArchivoRuta')
                ->leftJoin('ArchivoRutaConserje', 'ArchivoRutaConserje.IdRuta', '=', 'ArchivoRuta.IdRuta')
                ->leftJoin('Empleados', 'Empleados.IdEmpleado', '=', 'ArchivoRutaConserje.IdEmpleado')
                ->select('ArchivoRuta.IdRuta','ArchivoRuta.Nombre AS Ruta','ArchivoRutaConserje.IdEmpleado','Empleados.ApellidoPaterno','Empleados.ApellidoMaterno','Empleados.Nombres')
                ->where('ArchivoRuta.IdRuta','=',$IdRuta)
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Listar_Servicios_Ruta($IdRuta)
    {
        $result = DB::table('ArchivoRutaServicio')
                ->leftJoin('Servicios', 'Servicios.IdServicio', '=', 'ArchivoRutaServicio.IdServicio')
                ->select('ArchivoRutaServicio.IdServicio','Servicios.Nombre')
                ->where('ArchivoRutaServicio.IdRuta','=',$IdRuta)
                ->get();

        return json_decode(json_encode($result), true);
    }

    public function Eliminar_Ruta($IdRuta)
    {
        DB::table('ArchivoRutaConserje')->where('IdRuta', '=', $IdRuta)->delete();
        DB::table('ArchivoRutaServicio')->where('IdRuta', '=', $IdRuta)->delete();
        DB::table('ArchivoRuta')->where('IdRuta', '=', $IdRuta)->delete();
    }

    public function Eliminar_Servicios_Conserjes($IdRuta)
    {
        DB::table('ArchivoRutaConserje')->where('IdRuta', '=', $IdRuta)->delete();
        DB::table('ArchivoRutaServicio')->where('IdRuta', '=', $IdRuta)->delete();
        // DB::table('ArchivoRuta')->where('IdRuta', '=', $IdRuta)->delete();
    }

    public function No_Encontrado_Historia_Archivero($IdHistoriaSolicitada)
    {
        $datos = array(
            'SalidaArchivo'         => '0',
            'FechaSalidaArchivo'    => null
        );

        DB::table('SigesaGestionArchivo')
            ->where('IdHistoriaSolicitada', $IdHistoriaSolicitada)
            ->update($datos);
    }

    public function Encontrado_Historia_Archivero($IdHistoriaSolicitada,$fecha)
    {
        $datos = array(
            'SalidaArchivo'         => '1',
            'FechaSalidaArchivo'    => $fecha
        );

        DB::table('SigesaGestionArchivo')
            ->where('IdHistoriaSolicitada', $IdHistoriaSolicitada)
            ->update($datos);
    }

}
