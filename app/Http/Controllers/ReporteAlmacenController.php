<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;

class ReporteAlmacenController extends Controller
{
    public function reporte_traslados()
    {
    	$messages = [
            'inicio.required' 		=> 'Ingrese una fecha de inicio.',
            'fin.required' 			=> 'Ingrese una fecha fin.',
            'almacenid.required' 	=> 'Seleccione un almacen.'
        ];

        $rules = [
            'inicio' 	=> 'required',
            'fin'		=> 'required',
            'almacenid'	=> 'required'
        ];

        $this->validate($request,$rules,$messages);
    }
}
