<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePettyCashRequest extends FormRequest
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
            'number' => 'required|string|unique:petty_cashes,number',

            'branch_id' => 'required',

            'currency' => 'required|string',
            'rate' => 'required|numeric|min:0',

            'grand_total' => 'required|numeric|min:0',

            'balance' => 'required',

            'source' => 'required',

            'note' => 'nullable',

            'transaction_type' => 'required',

            'created_by' => 'required|string',

            'details' => 'required|array',
            'details.*.destination' => 'required|string',
            'details.*.amount' => 'required',
            'details.*.subtotal' => 'required',

            'details.*.is_ppn_available' => 'nullable|boolean',
            'details.*.ppn' => 'nullable',
            'details.*.ppn_type' => 'required_with:details.*.is_ppn_available|sometimes',
            'details.*.ppn_percentage' => 'required_with:details.*.is_ppn_available|nullable|sometimes|numeric|min:0|not_in:0|max:100',

            'details.*.is_pph_available' => 'nullable|boolean',
            'details.*.pph' => 'nullable',
            'details.*.pph_type' => 'required_with:details.*.is_pph_available|sometimes',
            'details.*.pph_percentage' => 'required_with:details.*.is_pph_available|nullable|sometimes|numeric|min:0|not_in:0|max:100',

            'details.*.transaction_date' => 'required',

            'details.*.note' => 'nullable|string'
        ];
    }
}
