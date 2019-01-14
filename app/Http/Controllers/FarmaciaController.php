<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Farmacia;

class FarmaciaController extends Controller
{
    public function reportegestion()
    {
    	return view('farmacia.reportegestion');
    }

    public function reporalmacen()
    {
    	return view('farmacia.reporalmacen');
    }

    public function reporfarmacia()
    {
        return view('farmacia.reporfarmacia');
    }

    public function almacenes()
    {
    	$Farmacia = new Farmacia();
    	$almacenes = $Farmacia->Mostrar_Almacenes_Farmacia();

    	return response()->json(['data' => $almacenes]);
    }

    public function farmacias($tipo)
    {
        $Farmacia = new Farmacia();
        $almacenes = $Farmacia->Mostrar_Farmacias($tipo);

        return response()->json(['data' => $almacenes]);
    }

    public function notaingresoalmacen()
    {
        return view('farmacia.notaingresoalmacen');
    }

    public function notasalidaalmacen()
    {
        return view('farmacia.notasalidaalmacen');
    }
}
