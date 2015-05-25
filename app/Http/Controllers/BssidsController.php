<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class BssidsController extends Controller {

	//
    /**
     *
     */
    public function index() {

        if ( \Request::has('bssidName') ) {
            $q_bssidName = trim(\Request::get('bssidName'));

            $bssids = \App\Bssid::where('bssidName', $q_bssidName)
                                ->paginate(10);
        } else {
            $bssids = \App\Bssid::where('bssidName', '<>', '')
                                ->paginate(10);
        }

        return view('bssids.index', compact('bssids'));
    }
}
