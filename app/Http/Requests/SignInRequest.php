<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SignInRequest extends Request
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
            'job_id' => 'required',
            'hour_type_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'job_id.required' => 'The Job Number field is required.',
            'hour_type_id.required' => 'The Hour Type field is required.'
        ];
    }
}
