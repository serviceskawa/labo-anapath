<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
            'name' => 'required',
            'id2' => 'nullable',
            'email' => 'nullable',
            'role' => 'nullable',
            'telephone' => 'nullable',
            'commission' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
