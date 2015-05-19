<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UsersController extends Controller {

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
        $users = \App\User::orderBy('username')->paginate(10);

        return view('users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
        $user = new \App\User();

        return view('users.create', compact('user'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Requests\CreateUserRequest $request)
	{
		//
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        \Session::flash('flash_message', 'user ' . $input['username'] . ' created.');

        // create
        \App\User::create($input);

        return redirect('users');
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
        $user = \App\User::findOrFail($id);
        return view('users.show', compact('user'));
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
        $user = \App\User::findOrFail($id);
        return view('users.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, Requests\CreateUserRequest $request)
	{
		//
        $user = \App\User::findOrFail($id);
        $input = $request->all();

        if ($input['password'] <> '') {

            $this->validate($request, [
                'usergroup_id' => 'required',
                'password' => 'required|min:4|same:password_confirm',
            ]);

            $input['password'] = bcrypt($input['password']);
        } else {
            // not update password field
            unset($input['password']);
        }

        \Session::flash('flash_message', 'user ' . $user->username . ' updated.');
        
        // update
        $user->update($input);

        return redirect('users/' . $user->id);
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
        $user = \App\User::findOrFail($id);
        \Session::flash('flash_message', 'user ' . $user->username . ' deleted.');

        // delete
        $user->delete();

        return redirect('users');
	}
}
