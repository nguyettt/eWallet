<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class EditProfileRequest extends FormRequest
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
        $id = auth()->user()->id;
        return [
            'username' => [
                'sometimes',
                'required',
                'min:4',
                'max:255',
                Rule::unique('users')->ignore($id),
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'password_current' => 'required|min:8',
            'firstName' => 'sometimes|required|min:4',
            'lastName' => 'sometimes|required|min:5',
            'dob' => 'sometimes|required|before:today',
            'gender' => 'sometimes|required',
            'file' => 'sometimes|mimetypes:image/bmp,image/jpeg,image/png',
            'password' => 'sometimes|required|min:4|confirmed'
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

    public function messages()
    {
        return [
            
        ];
    }
}
