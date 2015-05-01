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
        $node->update($input);

        return redirect('nodes');
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
        $node->delete();
        return redirect('nodes');
	}

}
