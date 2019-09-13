<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
            'password_current' => 'required',
            'password' => 'required|min:4|confirmed',
        ];
    }

    /**
     * Check for current password before update any change
     *
     * @param Validator $validator
     * @return array
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $user = auth()->user();
            if (!Hash::check($this->password_current, $user->password)) {
                $validator->errors()->add('password_current', 'Wrong current password');
            }
        });
    }
}
