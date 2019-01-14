<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;

class RutasController extends Controller
{
    public function index()
    {
    	return view('archivo.ruta');
    }
}
