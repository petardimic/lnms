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
        if ( ! defined('FPING_MAX_NODES') ) {
            define('FPING_MAX_NODES', '10');
        }

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
        //$systemOids = array( 'sysDescr', 'sysObjectID',
        //                     'sysUpTime', 'sysContact',
        //                     'sysName', 'sysLocation', );

        $systemOids = array( 'sysDescr', 'sysObjectID', );

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
     *
     */
    static public function mapSnmpSystemToPollClass(Array $snmp_system)
    {
        switch ($snmp_system['sysObjectID']) {
         case '.1.3.6.1.4.1.8072.3.2.10':
            return 'Netsnmp\Linux';
            break;

         case '.1.3.6.1.4.1.9.6.1.83.10.1': // Cisco SG300-10    10-Port Gigabit Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.10.2': // Cisco SG300-10P   10-Port Gigabit PoE Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.10.3': // Cisco SG300-10MP  10-Port Gigabit Max-PoE Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.10.5': // Cisco SG300-10SFP 10-port Gigabit Managed SFP Switch
         case '.1.3.6.1.4.1.9.6.1.83.20.1': // Cisco SG300-20    20-Port Gigabit Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.28.1': // Cisco SG300-28    28-Port Gigabit Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.28.2': // Cisco SG300-28P   28-Port Gigabit PoE Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.28.3': // Cisco SG300-28MP  28-port Gigabit Max-PoE Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.28.5': // Cisco SG300-28SFP 28-port Gigabit SFP Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.52.1': // Cisco SG300-52    52-Port Gigabit Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.52.2': // Cisco SG300-52P   52-port Gigabit PoE Managed Switch
         case '.1.3.6.1.4.1.9.6.1.83.52.3': // Cisco SG300-52MP  52-port Gigabit Max-PoE Managed Switch
            return 'Cisco\Sg300';
            break;

//         case '.1.3.6.1.4.1.9.1.431'; // catalyst355012G    WS-C3550-12G    10 GBIC + 2 10/100/1000
//         case '.1.3.6.1.4.1.9.1.368'; // catalyst355012T    WS-C3550-12T    10 10/100/1000 + 2 GBIC
//         case '.1.3.6.1.4.1.9.1.366'; // catalyst355024     WS-C3550-24     24 10/100 + 2 GBIC
//         case '.1.3.6.1.4.1.9.1.367'; // catalyst355048     WS-C3550-48     48 10/100 + 2 GBIC
//         case '.1.3.6.1.4.1.9.1.485'; // catalyst355024PWR  WS-C3550-24PWR  24 10/100 PWR + 2 GBIC
//            // Cisco Catalyst 3550 Series Switches
//            return 'Cisco\Cat3550';
//            break;
//
//         case '.1.3.6.1.4.1.9.1.550'; // cisco1701
//         case '.1.3.6.1.4.1.9.1.538'; // cisco1711
//         case '.1.3.6.1.4.1.9.1.539'; // cisco1712
//         case '.1.3.6.1.4.1.9.1.444'; // cisco1721
//         case '.1.3.6.1.4.1.9.1.326'; // cisco1751
//         case '.1.3.6.1.4.1.9.1.416'; // cisco1760
//            // Cisco 1700 Series Modular Access Routers
//            return 'Cisco\R1700';
//            break;
//
//
//         case '.1.3.6.1.4.1.9.1.638'; // cisco1801
//         case '.1.3.6.1.4.1.9.1.639'; // cisco1802
//         case '.1.3.6.1.4.1.9.1.640'; // cisco1803
//         case '.1.3.6.1.4.1.9.1.641'; // cisco1811
//         case '.1.3.6.1.4.1.9.1.642'; // cisco1812
//         case '.1.3.6.1.4.1.9.1.620'; // cisco1841
//         case '.1.3.6.1.4.1.9.1.1065'; // cisco1861
//            // Cisco 1800 Series Integrated Services Routers
//            return 'Cisco\R1800';
//            break;
//
//         case '.1.3.6.1.4.1.9.1.1192'; // cisco1905k9
//         case '.1.3.6.1.4.1.9.1.1191'; // cisco1921k9
//         case '.1.3.6.1.4.1.9.1.1095'; // cisco1941W
//         case '.1.3.6.1.4.1.9.1.1172'; // cisco1941WEK9
//         case '.1.3.6.1.4.1.9.1.1173'; // cisco1941WPK9
//         case '.1.3.6.1.4.1.9.1.1174'; // cisco1941WNK9
//            // Cisco 1900 Series Integrated Services Routers
//            return 'Cisco\R1900';
//            break;

         case '.1.3.6.1.4.1.9.1.507'; // ciscoAIRAP1100
         case '.1.3.6.1.4.1.9.1.618'; // ciscoAIRAP1130
            // Cisco Aironet 1100 Series
            return 'Cisco\Ap1100';
            break;


         case '.1.3.6.1.4.1.9.1.474'; // ciscoAIRAP1200
         case '.1.3.6.1.4.1.9.1.525'; // ciscoAIRAP1210
         case '.1.3.6.1.4.1.9.1.685'; // ciscoAIRAP1240
         case '.1.3.6.1.4.1.9.1.758'; // ciscoAIRAP1250
            // Cisco Aironet 1200 Series
            return 'Cisco\Ap1200';
            break;

         default:
            return 'Generic\Snmp';
            break;
        }
    }

    /**
     * find node poll_class
     *
     * @return poll class name
     */
    static public function findPollClass($id)
    {
        $node = \App\Node::findOrFail($id);

        $node_pingable = self::execPing($node->ip_address);

        if ($node_pingable) {
            $snmp_system = self::snmpget_system($node->ip_address, $node->snmp_comm_ro);

            if ($snmp_system) {
                return self::mapSnmpSystemToPollClass($snmp_system);
            } else {
                return 'Generic\Ping';
            }
        } else {
            return 'Generic\Unmanage';
        }
    }

    /**
     * display discover
     *
     * @return view
     */
    public function discover($id)
    {
        $node = \App\Node::findOrFail($id);
        $discover_result = self::execDiscover($id);

        return view('nodes.discover', compact('node', 'discover_result'));
    }
    
    /**
     * run snmp to discover node
     *
     * @return string
     */
    static public function execDiscover($id)
    {
        $node = \App\Node::findOrFail($id);

        $discover_result = '';

        $node_poll_class = self::findPollClass($id);

        // new node_poll_class object
        $node_class_name = '\App\Lnms\\' . $node_poll_class . '\Node';
        $node_object = new $node_class_name();

        $node->poll_class = $node_poll_class;
        $node->save();

        $discover_result .= 'Node Class: ' . $node_poll_class . ', ';

        // init result          
        $poller_result = [];

        foreach ($node_object->pollers() as $table_name => $pollers) {

            // reset result
            $poller_result = [];

            foreach ($pollers as $poller_name => $poller_params) {

                if ($poller_params['initial'] == 'Y') {

                    $poller_class  = '\App\Lnms\\' . $node_poll_class . '\\' . $poller_params['class'];
                    $poller_method = $poller_params['method'];

                    $poller_object = new $poller_class($node);
                    $poller_result = $poller_object->$poller_method();
                    $discover_result .= $poller_params['class'] . '::' . $poller_params['method'] . ' ' . count($poller_result) . ' records, ';

                    for ($i=0; $i<count($poller_result); $i++) {
                        switch ($poller_result[$i]['action']) {

                         case 'insert':
                            // insert new
                            if ( isset($poller_result[$i]['key']) ) {
                                \DB::table($poller_result[$i]['table'])
                                            ->insert(array_merge($poller_result[$i]['key'], $poller_result[$i]['data']));
                            } else {
                                \DB::table($poller_result[$i]['table'])
                                            ->insert($poller_result[$i]['data']);
                            }
                            break;

                         case 'sync':
                         case 'update':

                            // query existing data by key
                            $poll_db = \DB::table($poller_result[$i]['table']);

                            foreach ($poller_result[$i]['key'] as $poll_key => $poll_value) {
                                $poll_db = $poll_db->where($poll_key, $poll_value);
                            }

                            if ($poll_db->count() > 0) {
                                // update
                                \DB::table($poller_result[$i]['table'])
                                            ->where('id', $poll_db->first()->id)
                                            ->update($poller_result[$i]['data']);
                            } else {
                                if  ($poller_result[$i]['action'] == 'sync') {
                                    // just insert for 'sync'
                                    if ( isset($poller_result[$i]['key']) ) {
                                        \DB::table($poller_result[$i]['table'])
                                                ->insert(array_merge($poller_result[$i]['key'], $poller_result[$i]['data']));
                                    } else {
                                        \DB::table($poller_result[$i]['table'])
                                                ->insert($poller_result[$i]['data']);
                                    }
                                }
                            }

                            // TODO : detect and delete removed Port from DB
                            break;
                        }
                    }
                }

                // update pollings table
                for ($i=0; $i<count($poller_result); $i++) {

                    $i_poll_class  = $poller_params['class'];
                    $i_poll_method = $poller_params['method'];
                    $i_table_name  = $table_name;
                    $i_status      = $poller_params['default'];
                    $i_interval    = $poller_params['interval'];

                    // query existing data by key
                    $poll_db = \DB::table($i_table_name);

                    foreach ($poller_result[$i]['key'] as $poll_key => $poll_value) {
                        $poll_db = $poll_db->where($poll_key, $poll_value);
                    }

                    $i_table_id = $poll_db->first()->id;

                    $polling_db = \Db::table('pollings')
                                        ->where('poll_class', $i_poll_class)
                                        ->where('poll_method', $i_poll_method)
                                        ->where('table_name', $i_table_name)
                                        ->where('table_id', $i_table_id);

                    if ($polling_db->count() == 0) {
                        // if not exists, insert new pollings

                        $i_polling_data = [
                            'poll_class' => $i_poll_class,
                            'poll_method'=> $i_poll_method,
                            'table_name' => $i_table_name,
                            'table_id'   => $i_table_id,
                            'status'     => $i_status,
                            'interval'   => $i_interval,
                        ];
    
                        \DB::table('pollings')
                                    ->insert($i_polling_data);
                    }
                    // TODO: delete unused pollings records
                }
            }

        }

        return $discover_result;

    }

    /**
     *
     */
	public function discover_update($id)
    {
        return redirect('nodes/' . $id . '/discover');
    }

    /**
     *
     */
	public function ports_update($id)
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

        return redirect('nodes/' . $id . '/ports');
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
        $ports = \App\Port::where('node_id', $id)->orderBy('ifIndex')->paginate(100);

        return view('nodes.ports', compact('node', 'ports'));
    }

    /**
     * display vlans in node
     *
     */
    public function vlans($id)
    {
        $node = \App\Node::findOrFail($id);
        $vlans = \App\Vlan::where('node_id', $id)->orderBy('vlanIndex')->paginate(10);

        return view('nodes.vlans', compact('node', 'vlans'));
    }

    /**
     * display macs in node
     *
     */
    public function macs($id)
    {
        $node = \App\Node::findOrFail($id);
        $macs = \App\Mac::where('node_id', $id)->orderBy('macAddress')->paginate(10);

        return view('nodes.macs', compact('node', 'macs'));
    }

    /**
     * display ips in node
     *
     */
    public function ips($id)
    {
        $node = \App\Node::findOrFail($id);
        $ips = \App\Ip::where('node_id', $id)->orderBy('ipAddress')->paginate(10);

        return view('nodes.ips', compact('node', 'ips'));
    }

    /**
     * display arps in node
     *
     */
    public function arps($id)
    {
        $node = \App\Node::findOrFail($id);
        $arps = \App\Arp::where('node_id', $id)->orderBy('ipAddress')->paginate(10);

        return view('nodes.arps', compact('node', 'arps'));
    }

    /**
     * display routes in node
     *
     */
    public function routes($id)
    {
        $node = \App\Node::findOrFail($id);
        $routes = \App\Route::where('node_id', $id)->orderBy('routeDest')->paginate(10);

        return view('nodes.routes', compact('node', 'routes'));
    }

    /**
     * display bssids in node
     *
     */
    public function bssids($id)
    {
        $node = \App\Node::findOrFail($id);
        $bssids = \App\Bssid::where('node_id', $id)->orderBy('bssidIndex')->paginate(10);

        return view('nodes.bssids', compact('node', 'bssids'));
    }

    /**
     * display bssid_clients in node
     *
     */
    public function bssid_clients($id)
    {
        $node = \App\Node::findOrFail($id);
        $clients = \App\Bd::where('node_id', $id)->orderBy('clientMacAddress')->paginate(10);

        return view('nodes.bssid_clients', compact('node', 'clients'));
    }
}
