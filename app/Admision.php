<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Admision extends Model
{
    public function Progamacion_Filtrado($fecha, $filtro)
    {
        $result = DB::select('exec SIGESA_PROGRAMACION_CITAS_FILTRADOS ?,?', [$fecha, $filtro]);

        return json_decode(json_encode($result), true);
    }

    public function Cupos_Tomados($idProgramacion)
    {
        $result = DB::table('Citas')
                ->where('Citas.IdProgramacion','=',$idProgramacion)
                ->select('Citas.*')
                ->get();

        return json_decode(json_encode($result), true);
    }
}
