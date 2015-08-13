<?php

namespace Dbs\Http\Requests;

use Dbs\Http\Requests\Request;

class EditUserRequest extends Request
{
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
        return [
            'name' => 'required|unique:users,name,'.\Request::get('id'),
            'email' => 'required|email',
            'password' => 'min:6'
        ];
    }
}
