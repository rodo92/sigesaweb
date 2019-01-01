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
}
