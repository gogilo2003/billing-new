<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PayInvoiceRequest extends FormRequest
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
        return [
            "invoice" => ["required", "numeric", "integer", "exists:invoices,id"],
            "particulars" => ["required", "string"],
            "amount" => ["required", "numeric"],
            "method" => ["required", "string"],
            "receipt_no" => ["nullable", "string"],
        ];
    }
}
