<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContratDetailRequest extends FormRequest
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
            'contrat_id' => 'required|exists:contrats,id',
            'pourcentage' => 'required',
            'contrat_details_id' => 'nullable',
            'category_test_id' => 'required',
        ];
    }
}
