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


    /**
     *
     */
    public function octets($id) {

        if ( \Request::has('date') ) {
            $q_date = \Request::get('date');
        } else {
            $q_date = strftime("%Y-%m-%d");
        }

        $port  = \App\Port::findOrFail($id);
        $octets = \App\Octet::where('port_id', $id)
                            ->where('timestamp', '>', $q_date)
                            ->orderBy('timestamp')
                            ->paginate(288);

        return view('ports.octets', compact('port', 'octets', 'q_date'));
    }

    /**
     *
     */
    public function octets_data($id) {

        if ( \Request::has('date') ) {
            $q_date = \Request::get('date');
        } else {
            $q_date = strftime("%Y-%m-%d");
        }

        $port  = \App\Port::findOrFail($id);
        $octets = \App\Octet::where('port_id', $id)
                            ->where('timestamp', '>', $q_date)
                            ->orderBy('timestamp')
                            ->limit(288)
                            ->get();

        //            
        $prev_timestamp   = 0;
        $prev_input  = 0;
        $prev_output = 0;

        $avg_input  = [];
        $avg_output = [];

        foreach ($octets as $data) {

            $diff_timestamp   = strtotime($data->timestamp) - $prev_timestamp;
            $diff_input  = $data->input  - $prev_input;
            $diff_output = $data->output - $prev_output;

            if ($diff_input < 0) {
                $diff_input = $diff_input + 4294967296;
            }

            if ($diff_output < 0) {
                $diff_output = $diff_output + 4294967296;
            }

            if ($prev_input <> 0
                && $diff_timestamp > 0
                && $diff_input >= 0
                && $diff_output >= 0
                ) {



                // convert byte to bit
                $in_avg5  = (int) (8 * ($diff_input / $diff_timestamp));
                $out_avg5 = (int) (8 * ($diff_output / $diff_timestamp));

                $avg_input[]  = array((strtotime($data->timestamp) * 1000), $in_avg5);
                $avg_output[] = array((strtotime($data->timestamp) * 1000), $out_avg5);

            }

            // next
            $prev_timestamp  = strtotime($data->timestamp);
            $prev_input  = $data->input;
            $prev_output = $data->output;
        }


        $response[] = array('data'  => $avg_input,
                            'label' => 'Inbound',
                            'color' => 'lightgreen',
                            'lines'  => array('show' => 'true',
                                              'fill' => 'true',
                                              'fillColor' => 'lightgreen')
                            );
        $response[] = array('data'  => $avg_output,
                            'label' => 'Outbound',
                            'color' => 'darkblue',
                                    );
        // Response::json($response, $statusCode)->setCallback(Input::get('callback'));i
        return response()->json($response);

    }

}
