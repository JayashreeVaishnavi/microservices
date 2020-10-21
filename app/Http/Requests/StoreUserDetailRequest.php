<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserDetailRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:user_details,email',
            'phone_number' => 'required|min:10|max:10',
            'address' => 'sometimes|string',
            'accounts.*.bank_name' => 'required|string',
            'accounts.*.account_number' => 'required|min:9|max:18',
            'accounts.*.amount' => 'required|numeric',
        ];
    }
}
