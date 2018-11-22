<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Paciente extends Model
{
    public function Tipo_Seguro_DNI($dni) {
        $result = DB::select('exec SIGESA_Tipo_Seguro_x_DNI ?',[$dni]);                      
    	return json_decode(json_encode($result), true);
    }
}
