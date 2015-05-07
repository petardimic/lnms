<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class LocationsController extends Controller {

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
            $locations = \App\Location::where('name', 'RLIKE', $q)
                                ->paginate(10);
        } else {
            $locations = \App\Location::paginate(10);
        }
        return view('locations.index', compact('locations', 'q'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $location = new \App\Location();

        return view('locations.create', compact('location'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateLocationRequest $request)
	{
		//
        $input = $request->all();

        \Session::flash('flash_message', 'location ' . $input['name'] . ' created.');

        // create
        \App\Location::create($input);

        return redirect('locations');
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
        $location = \App\Location::findOrFail($id);
        return view('locations.show', compact('location'));
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
        $location = \App\Location::findOrFail($id);
        return view('locations.edit', compact('location'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreateLocationRequest $request)
	{
		//
        $location = \App\Location::findOrFail($id);
        $input = $request->all();
        \Session::flash('flash_message', 'location ' . $location->name . ' updated.');
        
        // update
        $location->update($input);

        return redirect('locations/' . $location->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $location = \App\Location::findOrFail($id);
        \Session::flash('flash_message', 'location ' . $location->name . ' deleted.');

        // delete
        $location->delete();

        return redirect('locations');
	}

}
