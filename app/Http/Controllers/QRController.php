<?php

namespace WebSigesa\Http\Controllers;

use Illuminate\Http\Request;
use QRCode;


class QRController extends Controller
{
	private $qrCode;
    public function qr_simple($cadena)
    {
    	$this->qrCode = QRCode::text($cadena)->png('mg.png');
		return response($this->qrCode)->header('Content-type','image/png');
    }
}
