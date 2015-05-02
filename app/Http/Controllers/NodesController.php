<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;
//use Request;

class NodesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        //
        //$request = \Request::route()->uri();
        //dd($request);


		//
        $q = \Request::get('q');

        if ($q <> '') {
            $nodes = \App\Node::where('name', 'RLIKE', $q)
                                ->orWhere('ip_address', 'RLIKE', $q)
                                ->paginate(5);
        } else {
            $nodes = \App\Node::paginate(5);
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
		//
        return view('nodes.create');
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

    /*
     * run snmp to discover node
     */
    public function discover($id)
    {
        $node = \App\Node::findOrFail($id);

        $snmp = new \App\Lnms\Snmp($node->ip_address, $node->snmp_comm_ro);

        // get system
        $systemOids = array( 'sysDescr', 'sysObjectID',
                             'sysUpTime', 'sysContact',
                             'sysName', 'sysLocation', );

        $get_oids = array();

        foreach ($systemOids as $oid_name) {
            $get_oids[] = constant('OID_' . $oid_name);
        }

        $get_result = $snmp->get($get_oids);

        foreach ($systemOids as $oid_name) {
            $snmp_system[$oid_name] = $get_result[constant('OID_' . $oid_name)];
        }

        //
        $snmp_interfaces = array();

        // walk ifDescr
        $walk_ifDescr = $snmp->walk(OID_ifDescr);
        foreach ($walk_ifDescr as $key1 => $value1) {
            $ifIndex = str_replace(OID_ifDescr . '.', '', $key1);
            $ifDescr = $value1;
            $snmp_interfaces[$ifIndex]['ifDescr'] = $ifDescr;
            $snmp_interfaces[$ifIndex]['ifIndex'] = $ifIndex;
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

        return view('nodes.discover', compact('node', 'snmp_system', 'snmp_interfaces'));

    }

	public function discover_update($id)
    {
        $input = \Request::all();

        \Session::flash('flash_message', 'polling status updated.');

        return redirect('nodes/' . $id . '/discover');
    }
}
