<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBillRequest extends FormRequest
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
            'bill_number' => 'required|string|unique:bills,number',

            'branch_id' => 'required',

            'currency' => 'required|string',
            'rate' => 'required',

            'dpp' => 'required',
            'discount' => 'nullable',
            'ppn' => 'required',
            'ppn_percentage' => 'required',
            'advance_payment' => 'nullable',
            'grand_total' => 'required',

            'balance' => 'nullable',

            'note' => 'nullable|string',

            'type' => 'required|string',
            'transaction_type' => 'required',

            'status' => 'required|string',

            'created_by' => 'required',

            'transaction_date' => 'required|date',
            'due_date' => 'required|date',

            'reference_number' => 'nullable|array',

            'payor_or_payee_code' => 'required'
        ];
    }
}
