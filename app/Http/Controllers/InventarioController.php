<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use WebSigesa\Farmacia;

class InventarioController extends Controller
{
    public function index()
    {
    	return view('farmacia.inventario');
    }

    public function farmTipoInventarioSeleccionarTodos()
    {
    	$Farmacia = new Farmacia();
    	$tipos = $Farmacia->farmTipoInventarioSeleccionarTodos();

    	return response()->json(['data' => $tipos]);
    }
}
