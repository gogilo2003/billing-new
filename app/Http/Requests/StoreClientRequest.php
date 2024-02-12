<?php

namespace App\Http\Requests;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $phones = [];
        try {
            foreach (explode(',', request()->phone) as $key => $phone) {
                $numberPrototype = $phoneUtil->parse($phone, "KE");

                if ($phoneUtil->isValidNumber($numberPrototype)) {
                    array_unshift($phones, $phoneUtil->format($numberPrototype, PhoneNumberFormat::E164));
                }
            }
            request()->merge(['phone' => implode(',', $phones)]);
        } catch (NumberParseException $e) {
            request()->merge(['phone' => null]);
        }

        return [
            'name' => 'required|unique:clients',
            'phone' => 'nullable|unique:clients',
            'email' => 'required|email|unique:clients',
        ];
    }
}
