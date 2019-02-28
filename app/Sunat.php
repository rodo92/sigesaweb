<?php

namespace WebSigesa;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sunat extends Model
{
    public function SIGESA_TRAMA_LISTA_IDCOMPROBANTES_XFECHA($fecha)
    {
		$result = DB::select('exec SIGESA_TRAMA_LISTA_IDCOMPROBANTES_XFECHA ?',[$fecha]);                      
    	return json_decode(json_encode($result), true);
    }

    public function SIGESA_TRAMA_CABECERA_X_CODIGO($idcomprobante)
    {
    	$result = DB::select('exec SIGESA_TRAMA_CABECERA_X_CODIGO ?',[$idcomprobante]);                      
    	return json_decode(json_encode($result), true);
    }

    public function SIGESA_TRAMA_DETALLE_X_CODIGO($idcomprobante)
    {
    	$result = DB::select('exec SIGESA_TRAMA_DETALLE_X_CODIGO ?',[$idcomprobante]);                      
    	return json_decode(json_encode($result), true);	
    }
}
