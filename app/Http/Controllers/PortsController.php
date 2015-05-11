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

    public function pollings($id) {
        $port  = \App\Port::findOrFail($id);

        // new node_poll_class object
        $node_class_name = '\App\Lnms\\' . $port->node->poll_class . '\Node';
        $node_object = new $node_class_name();
        $node_pollers = $node_object->pollers();

        $pollings = [];

        if ( isset($node_pollers['ports']) ) {
            foreach ($node_pollers['ports'] as $poller_name => $poller_params) {
    
                $pollings[] = \App\Polling::where('poll_class',  $poller_params['class'])
                                          ->where('poll_method', $poller_params['method'])
                                          ->where('table_name',  'ports')
                                          ->where('table_id',    $id)
                                          ->first();
            }
        }

        return view('ports.pollings', compact('port', 'pollings'));
    }

    /**
     *
     */
	public function pollings_update($id)
    {
        $input = \Request::all();
        $port  = \App\Port::findOrFail($id);

        // new node_poll_class object
        $node_class_name = '\App\Lnms\\' . $port->node->poll_class . '\Node';
        $node_object = new $node_class_name();
        $node_pollers = $node_object->pollers();

        foreach ($node_pollers['ports'] as $poller_name => $poller_params) {

            $polling = \App\Polling::where('poll_class',  $poller_params['class'])
                                   ->where('poll_method', $poller_params['method'])
                                   ->where('table_name',  'ports')
                                   ->where('table_id',    $id)
                                   ->first();

            if ( isset($input['status'][$polling->id]) ) {
                $polling->status = 'Y';
            } else {
                $polling->status = 'N';
            }

            $polling->save();
        }

        \Session::flash('flash_message', 'polling status updated.');

        return redirect('/ports/' . $id . '/pollings');
    }

}
