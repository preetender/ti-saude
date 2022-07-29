<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'required|min:4|max:60',
            'code' => 'nullable|max:8|unique:patients',
            'birth_date' => 'required|date',
            'plans.*.id' => 'nullable|exists:plans,id',
            'plans.*.contract_number' => 'required_with:plans.*.id|unique:patient_plan|max:6'
        ];
    }
}
