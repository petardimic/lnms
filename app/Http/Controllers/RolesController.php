<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class RolesController extends Controller {

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
		//
        $q = \Request::get('q');

        if ($q <> '') {
            $roles = \App\Role::where('name', 'RLIKE', $q)
                                ->paginate(10);
        } else {
            $roles = \App\Role::paginate(10);
        }
        return view('roles.index', compact('roles', 'q'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $role = new \App\Role();

        return view('roles.create', compact('role', 'usergroups', 'locations', 'projects' ));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateRoleRequest $request)
	{
		//
        $input = $request->all();

        \Session::flash('flash_message', 'role created.');

        // create
        \App\Role::create($input);

        return redirect('roles');
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
        $role = \App\Role::findOrFail($id);
        return view('roles.show', compact('role'));
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
        $role = \App\Role::findOrFail($id);
        return view('roles.edit', compact('role'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreateRoleRequest $request)
	{
		//
        $role = \App\Role::findOrFail($id);
        $input = $request->all();
        \Session::flash('flash_message', 'role ' . $role->name . ' updated.');
        
        // update
        $role->update($input);

        return redirect('roles/' . $role->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $role = \App\Role::findOrFail($id);
        \Session::flash('flash_message', 'role ' . $role->name . ' deleted.');

        // delete
        $role->delete();

        return redirect('roles');
	}

}
