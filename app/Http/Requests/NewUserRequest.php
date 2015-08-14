<?php

namespace Dbs\Http\Requests;

use Dbs\Http\Requests\Request;

class NewUserRequest extends Request
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
            'name' => 'required|unique:users',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];
    }
}
