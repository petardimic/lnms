<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateUserRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
        switch ($this->method()) {
         case 'POST':
		    return [
                'username' => 'required',
                'usergroup_id' => 'required',
                'password' => 'required|min:4|same:password_confirm',
		    ];
            break;

         case 'PUT':
         case 'PATCH':
		    return [
                'username'       => 'required|unique:users,username,' . $this->route('users') . ',id',
                'usergroup_id' => 'required',
		    ];
            break;
        }
	}
}
