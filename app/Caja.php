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
    	$result = DB::insert('exec SIGESA_Apertura_Caja ?,?,?,?,?,?', [$FechaApertura,$EstadoLote,$IdCaja,$IdTurno,$TotalCobrado,$IdCajero]);

    	return json_decode(json_encode($result), true);
    }

    public function Medicamentos_Servicios_Filtrados($tipobusqueda,$filtro)
    {
        $result = DB::select('exec SIGESA_MEDICAMENTOS_SERVICIOS_FACTURACION_CAJA ?,?', [$tipobusqueda,$filtro]);

        return json_decode(json_encode($result), true);
    }
}
