<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PollingsController extends Controller {

    /**
     *
     * @return view
     */
    public function ds($id) {
        $polling = \App\Polling::findOrFail($id);
        $pds = \App\Pd::where('polling_id', $id)->orderBy('timestamp', 'desc')->paginate(10);

        return view('pollings.ds', compact('polling', 'pds'));
    }
}
