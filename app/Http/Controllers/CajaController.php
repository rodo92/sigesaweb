<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function cajas()
    {
    	return view('caja.cajas');
    }
}
