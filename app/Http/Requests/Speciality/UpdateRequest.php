<?php

namespace App\Http\Requests\Speciality;

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
            'name' => 'nullable|min:4|max:100',
            'code' => [
                'nullable',
                'max:8',
                Rule::unique('specialities')->ignore($this->route('id')),
            ],
        ];
    }
}
