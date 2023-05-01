<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContratRequest extends FormRequest
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
            'type' => 'required',
            'description' => 'required',
            'status' => 'nullable',
            'id' => 'nullable',
            'nbr_examen' => 'nullable',
        ];
    }
}
