<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class NodegroupsController extends Controller {

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
        $nodegroups = \App\Nodegroup::orderBy('name')->paginate(10);

        return view('nodegroups.index', compact('nodegroups'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $nodegroup = new \App\Nodegroup();

        return view('nodegroups.create', compact('nodegroup'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateNodegroupRequest $request)
	{
		//
        $input = $request->all();

        \Session::flash('flash_message', 'nodegroup ' . $input['name'] . ' created.');

        // create
        \App\Nodegroup::create($input);

        return redirect('nodegroups');
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
        $nodegroup = \App\Nodegroup::findOrFail($id);
        return view('nodegroups.show', compact('nodegroup'));
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
        $nodegroup = \App\Nodegroup::findOrFail($id);
        return view('nodegroups.edit', compact('nodegroup'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreateNodegroupRequest $request)
	{
		//
        $nodegroup = \App\Nodegroup::findOrFail($id);
        $input = $request->all();
        \Session::flash('flash_message', 'nodegroup ' . $nodegroup->name . ' updated.');
        
        // update
        $nodegroup->update($input);

        return redirect('nodegroups/' . $nodegroup->id);
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
        $nodegroup = \App\Nodegroup::findOrFail($id);
        \Session::flash('flash_message', 'nodegroup ' . $nodegroup->name . ' deleted.');

        // delete
        $nodegroup->delete();

        return redirect('nodegroups');
	}

}
