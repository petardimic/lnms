<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PortsController extends Controller {

	//
    public function show($id) {
        $port = \App\Port::find($id);
        return view('ports.show', compact('port'));
    }

    public function octets($id) {
        $port   = \App\Port::find($id);
        $octets = \App\Octet::where('port_id', $id)->orderBy('timestamp', 'desc')->paginate(10);
        return view('ports.octets', compact('port', 'octets'));
    }

}
