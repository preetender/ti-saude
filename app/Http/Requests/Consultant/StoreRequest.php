<?php

namespace App\Http\Requests\Consultant;

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
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'hour' => 'required|date_format:H:i:s',
            'private' => 'required|boolean',
            'procedures.*' => 'nullable|exists:procedures,id',
        ];
    }
}
