<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
    	return view('inicio.index');
    }
}
