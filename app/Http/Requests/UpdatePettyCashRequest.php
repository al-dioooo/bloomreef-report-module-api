<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePettyCashRequest extends FormRequest
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
            'number' => 'required|string|exists:petty_cashes,number',

            'status' => 'nullable|string',

            'balance' => 'nullable',

            'note' => 'nullable|string',

            'updated_by' => 'nullable|string'
        ];
    }
}
