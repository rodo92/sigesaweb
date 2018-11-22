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

    public function Mostrar_Cajas_Tipo_Comprobante()
    {
        $result = DB::select('exec SIGESA_CajaCajaTipoComprobante');

        return json_decode(json_encode($result), true);
    }

    public function Apertura_Caja($FechaApertura,$EstadoLote,$IdCaja,$IdTurno,$TotalCobrado,$IdCajero)
    {
    	$result = DB::raw('exec SIGESA_Apertura_Caja ?,?,?,?,?,?', [$FechaApertura,$EstadoLote,$IdCaja,$IdTurno,$TotalCobrado,$IdCajero]);

    	return json_decode(json_encode($result), true);
    }
}
