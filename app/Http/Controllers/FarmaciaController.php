<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;

class FarmaciaController extends Controller
{
    public function reportegestion()
    {
    	return view('farmacia.reportegestion');
    }
}
