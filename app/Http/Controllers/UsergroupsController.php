<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UsergroupsController extends Controller {

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
        $usergroups = \App\Usergroup::orderBy('name')->paginate(10);

        return view('usergroups.index', compact('usergroups'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $usergroup = new \App\Usergroup();

        return view('usergroups.create', compact('usergroup'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateUsergroupRequest $request)
	{
		//
        $input = $request->all();

        \Session::flash('flash_message', 'usergroup ' . $input['name'] . ' created.');

        // create
        \App\Usergroup::create($input);

        return redirect('usergroups');
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
        $usergroup = \App\Usergroup::findOrFail($id);
        return view('usergroups.show', compact('usergroup'));
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
        $usergroup = \App\Usergroup::findOrFail($id);
        return view('usergroups.edit', compact('usergroup'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreateUsergroupRequest $request)
	{
		//
        $usergroup = \App\Usergroup::findOrFail($id);
        $input = $request->all();
        \Session::flash('flash_message', 'usergroup ' . $usergroup->name . ' updated.');
        
        // update
        $usergroup->update($input);

        return redirect('usergroups/' . $usergroup->id);
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
        $usergroup = \App\Usergroup::findOrFail($id);
        \Session::flash('flash_message', 'usergroup ' . $usergroup->name . ' deleted.');

        // delete
        $usergroup->delete();

        return redirect('usergroups');
	}

}
