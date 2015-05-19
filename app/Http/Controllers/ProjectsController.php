<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ProjectsController extends Controller {

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
        $projects = \App\Project::orderBy('name')->paginate(10);

        return view('projects.index', compact('projects'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $project = new \App\Project();

        return view('projects.create', compact('project'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateProjectRequest $request)
	{
		//
        $input = $request->all();

        \Session::flash('flash_message', 'project ' . $input['name'] . ' created.');

        // logo file
        if ( isset($input['image']) ) {
            $input['logo_file_name'] = basename(\Request::file('image')->getRealPath());
            $input['logo_file_type'] = \Request::file('image')->getMimeType();
            \Request::file('image')->move('/tmp');
        }

        // create
        \App\Project::create($input);

        return redirect('projects');
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
        $project = \App\Project::findOrFail($id);
        return view('projects.show', compact('project'));
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
        $project = \App\Project::findOrFail($id);
        return view('projects.edit', compact('project'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreateProjectRequest $request)
	{
		//
        $project = \App\Project::findOrFail($id);
        $input = $request->all();
        \Session::flash('flash_message', 'project ' . $project->name . ' updated.');

        // logo file
        if ( isset($input['image']) ) {
            $input['logo_file_name'] = basename(\Request::file('image')->getRealPath());
            $input['logo_file_type'] = \Request::file('image')->getMimeType();
            \Request::file('image')->move('/tmp');
        }

        // update
        $project->update($input);

        return redirect('projects/' . $project->id);
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
        $project = \App\Project::findOrFail($id);
        \Session::flash('flash_message', 'project ' . $project->name . ' deleted.');

        // delete
        $project->delete();

        return redirect('projects');
	}

    /**
     *
     */
	public function logo($id)
    {
        $project = \App\Project::findOrFail($id);

        if ($project->logo_file_name == '') {
            die();
        } else {
            header('Content-Type: ' . $project->logo_file_type . '');
            readfile('/tmp/' . $project->logo_file_name);
            die();
        }
    }
}
