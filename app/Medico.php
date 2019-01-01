<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Medico extends Model
{
    public function Obtener_Medicos($idTipoServicio)
    {
        $result = DB::table('Medicos')
                ->leftJoin('Empleados', 'Empleados.IdEmpleado', '=', 'Medicos.IdEmpleado')
                ->select('Medicos.*','Empleados.ApellidoPaterno','Empleados.ApellidoMaterno','Empleados.Nombres')
                ->get();

        return json_decode(json_encode($result), true);
    }
}
