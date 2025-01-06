<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
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
            'id' =>'nullable',
            'code' => 'required',
            'firstname' => 'required',
            'lastname' => 'required',
            'telephone1' => 'required',
            'telephone2' => 'nullable',
            'adresse' => 'nullable',
            'genre' => 'required',
            'year_or_month' => 'required',
            'age' => 'required | integer',
            'profession' => 'nullable',
            'birthday' => 'nullable||date'
        ];
    }
}
