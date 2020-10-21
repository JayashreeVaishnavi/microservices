<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserDetailRequest extends FormRequest
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
            'first_name' => 'sometimes|required|max:40',
            'last_name' => 'sometimes|required|max:40',
            'email' => 'sometimes|required|email|unique:user_details,email',
            'phone_number' => 'sometimes|required|min:10|max:10',
            'address' => 'sometimes|string',
            'accounts.*.id' => 'required',
            'accounts.*.bank_name' => 'sometimes|required|string',
            'accounts.*.account_number' => 'sometimes|required|min:9|max:18',
            'accounts.*.amount' => 'sometimes|required|min:1|max:10',
        ];
    }
}
