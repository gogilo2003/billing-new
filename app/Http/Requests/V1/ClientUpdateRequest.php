<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ClientUpdateRequest extends FormRequest
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
            'id' => 'required|integer|exists:clients,id',
            'name' => 'required|unique:clients,name,' . request()->input('id'),
            'phone' => 'unique:clients,phone,' . request()->input('id'),
            'email' => 'required|email|unique:clients,email,' . request()->input('id'),
        ];
    }
}
