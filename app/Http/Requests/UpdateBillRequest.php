<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBillRequest extends FormRequest
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
            'number' => 'required|string|exists:bills,number',

            'branch_id' => 'nullable',

            'currency' => 'nullable|string',
            'rate' => 'nullable',

            'dpp' => 'nullable',
            'discount' => 'nullable',
            'ppn' => 'nullable',
            'ppn_percentage' => 'nullable',
            'advance_payment' => 'nullable',
            'grand_total' => 'nullable',

            'balance' => 'nullable',

            'note' => 'nullable|string',

            'type' => 'nullable|string|exists:types,code',
            'transaction_type' => 'nullable',

            'status' => 'nullable|string',

            'updated_by' => 'required',

            'transaction_date' => 'nullable|date',
            'due_date' => 'nullable|date',

            'reference_number' => 'nullable|array',

            'payor_or_payee_code' => 'nullable'
        ];
    }
}
