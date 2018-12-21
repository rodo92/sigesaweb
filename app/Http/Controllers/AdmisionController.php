<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;

class AdmisionController extends Controller
{
    public function AdmisionCitas()
    {
    	return view('consultaexterna.admision');
    }
}
