<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestOrderRequest extends FormRequest
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
            'patient_id' => 'required',
            'doctor_id' => ['required', 'exists:doctors,name'],
            'hospital_id' => ['required', 'exists:hospitals,name'],
            'prelevement_date' => 'required',
            'reference_hopital' => 'nullable',
            'contrat_id' => 'required',
            'is_urgent' => 'nullable',
            'examen_reference_select' => 'nullable',
            'examen_reference_input' => 'nullable',
            'type_examen' => ['required', 'exists:type_orders,id']
        ];
    }
}
