<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;
//use Request;

class NodesController extends Controller {

    /*
     * Constructor
     *
     */
    public function __construct()
    {
        // must auth before
        $this->middleware('auth');
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $q = \Request::get('q');

        if ($q <> '') {
            $nodes = \App\Node::where('name', 'RLIKE', $q)
                                ->orWhere('ip_address', 'RLIKE', $q)
                                ->paginate(10);
        } else {
            $nodes = \App\Node::paginate(10);
        }
        return view('nodes.index', compact('nodes', 'q'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $node = new \App\Node();

        // default snmp version
        $node->snmp_version = 1;

        return view('nodes.create', compact('node'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateNodeRequest $request)
	{
		//
        $input = $request->all();

        if ($input['location_id'] == '') {
            $input['location_id'] = null;
        }

        \Session::flash('flash_message', 'node ' . $input['ip_address'] . ' created.');

        // create
        \App\Node::create($input);

        return redirect('nodes');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
        $node = \App\Node::findOrFail($id);
        return view('nodes.show', compact('node'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
        $node = \App\Node::findOrFail($id);
        return view('nodes.edit', compact('node'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreateNodeRequest $request)
	{
		//
        $node = \App\Node::findOrFail($id);
        $input = $request->all();

        if ($input['location_id'] == '') {
            $input['location_id'] = null;
        }

        \Session::flash('flash_message', 'node ' . $node->ip_address . ' updated.');
        
        // update
        $node->update($input);

        return redirect('nodes/' . $node->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
        $node = \App\Node::findOrFail($id);
        \Session::flash('flash_message', 'node ' . $node->ip_address . ' deleted.');

        // delete
        $node->delete();

        return redirect('nodes');
	}

    //
    public function test($id)
    {
        $node = \App\Node::findOrFail($id);
        return view('nodes.test', compact('node'));
    }

    /**
     * execute ping command
     *
     * @return Array of ip address. If ping fail, return false.
     */
    static public function execPing($arg_ip_addresses)
    {

        if ( is_array($arg_ip_addresses) ) {
            $ip_addresses = $arg_ip_addresses;
        } else {
            // cast to array
            $ip_addresses = [$arg_ip_addresses];
        }

        /**
         * Maximum nodes to fping in the same time
         */
        define('FPING_MAX_NODES', '10');;

        // targets to fping
        $targets = array_chunk($ip_addresses, FPING_MAX_NODES);

        // return fping output
        $fping_output = [];

        foreach ($targets as $target) {
            unset($exec_out);
            exec('fping -e ' . implode(' ', $target) . ' 2>&1', $exec_out, $exec_ret);

            for ($i=0; $i<count($exec_out); $i++) {
                if (preg_match('/ is alive/', $exec_out[$i])) {
                    // ping ok
                    $res_ip   = preg_replace('/ .*/', '', $exec_out[$i]);
                    $res_time = preg_replace('/.* is alive \(([^ ]+) ms\)/', '\\1', $exec_out[$i]);
                    $fping_output[$res_ip] = $res_time;
                } elseif (preg_match('/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3} is unreachable/', $exec_out[$i])) {
                    // ping fail
                    $res_ip = preg_replace('/ .*/', '', $exec_out[$i]);
                    $fping_output[$res_ip] = false;
                }
            }
        }

        foreach ($ip_addresses as $ip_address) {
            if ( ! isset($fping_output[$ip_address]) ) {
                $fping_output[$ip_address] = false;
            }
        }

        //
        if ( is_array($arg_ip_addresses) ) {
            return $fping_output;
        } else {
            return $fping_output[$ip_address];
        }

    }

    // api
    public function ping($id)
    {
        $node = \App\Node::findOrFail($id);

        exec('fping -e ' . $node->ip_address . ' 2>/dev/null', $exec_out1, $exec_ret1);

        if ($exec_ret1 == 0) {
            // ping ok
            $ping_success = 100;
        } else {
            // ping fail
            $ping_success = 0;
        }

        if ($ping_success <> $node->ping_success) {
            \Log::warning('nodes/' . $node->id . ' ping changed to ' . $ping_success);

            $node->ping_success = $ping_success;
            $node->ping_changed = \Carbon\Carbon::now();

            // update ping success changed
            $node->save();
        }

        return response()->json(['ping_success' => $ping_success]);
    }

    public function snmp($id)
    {
        $node = \App\Node::findOrFail($id);

        $snmp = new \App\Lnms\Snmp($node->ip_address, $node->snmp_comm_ro);
        $get = $snmp->get('.1.3.6.1.2.1.1.2.0');

        if ($snmp->getErrno() == 0) {
            // snmp ok
            $snmp_success = 100;
        } else {
            // snmp fail
            $snmp_success = 0;
        }

        if ($snmp_success <> $node->snmp_success) {
            \Log::warning('nodes/' . $node->id . ' snmp changed to ' . $snmp_success);

            $node->snmp_success = $snmp_success;
            $node->snmp_changed = \Carbon\Carbon::now();

            // update snmp success changed
            $node->save();
        }

        return response()->json(['snmp_success' => $snmp_success]);
    }

    /**
     * snmp get system mibs
     *
     * @return Array
     *
     */
    static public function snmpget_system($ip_address, $snmp_comm_ro) {

        $snmp = new \App\Lnms\Snmp($ip_address, $snmp_comm_ro);

        // get system
        $systemOids = array( 'sysDescr', 'sysObjectID',
                             'sysUpTime', 'sysContact',
                             'sysName', 'sysLocation', );

        $get_oids = array();

        foreach ($systemOids as $oid_name) {
            $get_oids[] = constant('OID_' . $oid_name);
        }

        $get_result = $snmp->get($get_oids);

        // get system error
        if ($snmp->getErrno() <> 0) {
            return false;
        }

        // return array
        $snmp_system = [];

        foreach ($systemOids as $oid_name) {
            $snmp_system[$oid_name] = $get_result[constant('OID_' . $oid_name)];
        }

        return $snmp_system;

    }

    /**
     * snmp get interfaces mibs
     *
     * @return Array
     *
     */
    static public function snmpget_interfaces($ip_address, $snmp_comm_ro) {
        
        // return
        $snmp_interfaces = [];

        $snmp = new \App\Lnms\Snmp($ip_address, $snmp_comm_ro);

        // walk ifDescr
        $walk_ifDescr = $snmp->walk(OID_ifDescr);
        foreach ($walk_ifDescr as $key1 => $value1) {
            $ifIndex = str_replace(OID_ifDescr . '.', '', $key1);
            $ifDescr = $value1;
            $snmp_interfaces[$ifIndex]['ifIndex'] = $ifIndex;
            $snmp_interfaces[$ifIndex]['ifDescr'] = $ifDescr;
        }

        $ifOids = array( 'ifType', 'ifSpeed', 'ifPhysAddress',
                         'ifAdminStatus', 'ifOperStatus',
                         'ifName', 'ifHighSpeed', 'ifAlias' );

        foreach ($ifOids as $oid_name) {
            $get_oids = array();
            foreach ($snmp_interfaces as $ifIndex => $value1) {
                $get_oids[] = constant('OID_' . $oid_name) . '.' . $ifIndex;
            }

            $get_result = $snmp->get($get_oids);

            foreach ($snmp_interfaces as $ifIndex => $value1) {
                $snmp_interfaces[$ifIndex][$oid_name] = $get_result[constant('OID_' . $oid_name) . '.' . $ifIndex];
            }
        }

        return $snmp_interfaces;
    }

    /**
     *
     */
    static public function poll_class(Array $snmp_system)
    {
        switch ($snmp_system['sysObjectID']) {
         case '.1.3.6.1.4.1.8072.3.2.10':
            return 'Netsnmp\Linux';
            break;

         default:
            return 'Generic\Snmp';
            break;
        }
    }

    /**
     * run snmp to discover node
     *
     */
    public function discover($id)
    {
        $node = \App\Node::findOrFail($id);

        $node_pingable = self::execPing($node->ip_address);

        if ($node_pingable) {
            $snmp_system = self::snmpget_system($node->ip_address, $node->snmp_comm_ro);

            if ($snmp_system) {
                $poll_class = self::poll_class($snmp_system);
                $discover_status = 'ping ok,snmp ok';
            } else {
                $poll_class = 'Generic\Ping';
                $discover_status = 'ping only';
            }
        } else {
            $poll_class = 'Generic\Ping';
            $discover_status = 'ping fail';
        }

        // new class
        $node_class_name = '\App\Lnms\\' . $poll_class . '\Node';
        $node_object = new $node_class_name();

        if (method_exists($node_object, 'pollers')) {
            dd($node_object->pollers());
            foreach ($node_object->pollers() as $poller) {
                print '<li>' . $poller;
            }
        }

        print '<hr>';
        die();



        return view('nodes.discover', compact('node', 'discover_status'));
    }


//$snmp_interfaces = self::snmpget_interfaces($node->ip_address, $node->snmp_comm_ro);
//        foreach ($snmp_interfaces as $ifIndex => $value1) {
//            $port = \App\Port::where('node_id', $id)->where('ifIndex', $ifIndex);
//            unset($value1['ifHighSpeed']);
//
//            if ($port->count() == 1) {
//                // update port
//                $port->update($value1);
//                $port = $port->firstOrFail();
//                $snmp_interfaces[$ifIndex]['poll_enabled'] = $port->poll_enabled;
//            } else {
//                // create port
//                \App\Port::create($value1);
//                $snmp_interfaces[$ifIndex]['poll_enabled'] = '';
//            }
//        }
//
//    }

	public function discover_update($id)
    {
        $input = \Request::all();
        $node = \App\Node::findOrFail($id);

        foreach ($node->ports as $key1 => $value1) {

            $port = \App\Port::where('node_id', $id)->where('ifIndex', $value1->ifIndex)->firstOrFail();

            if ( isset($input['poll_enabled'][$value1->ifIndex]) ) {
                $port->poll_enabled = 'Y';
            } else {
                $port->poll_enabled = 'N';
            }

            $port->save();
        }

        \Session::flash('flash_message', 'polling status updated.');

        return redirect('nodes/' . $id . '/discover');
    }

	/**
	 * Display graph ping
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function graph_ping($id)
	{
		//
        $node = \App\Node::findOrFail($id);
        $pings = \App\Ping::where('node_id', $id)->orderBy('timestamp', 'desc')->paginate(10);

        return view('nodes.graph_ping', compact('node', 'pings'));
	}

	/**
	 * Display graph snmp
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function graph_snmp($id)
	{
		//
        $node = \App\Node::findOrFail($id);
        $snmps = \App\Snmp::where('node_id', $id)->orderBy('timestamp', 'desc')->paginate(10);

        return view('nodes.graph_snmp', compact('node', 'snmps'));
	}

    /**
     * display ports in node
     *
     */
    public function ports($id)
    {
        $node = \App\Node::findOrFail($id);
        $ports = \App\Port::where('node_id', $id)->orderBy('ifIndex')->paginate(10);

        return view('nodes.ports', compact('node', 'ports'));
    }
}
