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

    public function almacenes()
    {
    	$Farmacia = new Farmacia();
    	$almacenes = $Farmacia->Mostrar_Almacenes_Farmacia();

    	return response()->json(['data' => $almacenes]);
    }
}
