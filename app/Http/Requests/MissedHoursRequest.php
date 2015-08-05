<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MissedHoursRequest extends Request
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
            'time' => 'required|numeric|min:0',
            'hour_type' => 'required' 
        ];
    }

    public function messages(){
        return ['hour_type_id.required' => 'Please select the pay type'];
    }
}
