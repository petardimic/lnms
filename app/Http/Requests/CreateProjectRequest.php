<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateProjectRequest extends Request {

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
                'name'      => 'required',
		    ];
            break;

         case 'PUT':
         case 'PATCH':
		    return [
                'name'      => 'required|unique:projects,name,' . $this->route('projects') . ',id',
		    ];
            // 'image'     => 'mimes:jpeg,bmp,png',
            break;
        }
	}
}
