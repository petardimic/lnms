<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateNodeRequest extends Request {

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
                'name'       => 'required',
                'ip_address' => 'required|ip|unique:nodes,ip_address',
		    ];
            break;

         case 'PUT':
         case 'PATCH':
		    return [
                'name'       => 'required',
                'ip_address' => 'required|ip|unique:nodes,ip_address,' . $this->route('nodes') . ',id'
		    ];
            break;
        }
	}
}
