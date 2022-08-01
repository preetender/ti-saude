<?php

namespace App\Http\Requests\Procedure;

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
            'name' => 'required|min:4|max:60',
            'code' => [
                'nullable',
                Rule::unique('procedures')->ignore($this->route('id')),
            ],
            'value' => 'required|numeric|min:1',
        ];
    }
}
