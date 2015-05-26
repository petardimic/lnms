<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PermissionsController extends Controller {

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
            $permissions = \App\Permission::where('name', 'RLIKE', $q)
                                ->paginate(10);
        } else {
            $permissions = \App\Permission::paginate(10);
        }
        return view('permissions.index', compact('permissions', 'q'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $permission = new \App\Permission();

        return view('permissions.create', compact('permission'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreatePermissionRequest $request)
	{
		//
        $input = $request->all();

        \Session::flash('flash_message', 'permission ' . $input['name'] . ' created.');

        // create
        \App\Permission::create($input);

        return redirect('permissions');
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
        $permission = \App\Permission::findOrFail($id);
        return view('permissions.show', compact('permission'));
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
        $permission = \App\Permission::findOrFail($id);
        return view('permissions.edit', compact('permission'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreatePermissionRequest $request)
	{
		//
        $permission = \App\Permission::findOrFail($id);
        $input = $request->all();
        \Session::flash('flash_message', 'permission ' . $permission->name . ' updated.');
        
        // update
        $permission->update($input);

        return redirect('permissions/' . $permission->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $permission = \App\Permission::findOrFail($id);
        \Session::flash('flash_message', 'permission ' . $permission->name . ' deleted.');

        // delete
        $permission->delete();

        return redirect('permissions');
	}

}
