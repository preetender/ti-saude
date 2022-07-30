<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => 'nullable|min:4|max:60',
            'code' => [
                'nullable',
                'max:8',
                Rule::unique('patients')->ignore($this->route('id')),
            ],
            'plans.*.id' => 'nullable|exists:plans,id',
            'plans.*.contract_number' => [
                'required_with:plans.*.id',
                Rule::unique('patient_plan')->ignore($this->route('id'), 'patient_id'),
            ],
        ];
    }
}
