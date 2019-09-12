<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            'username' => 'required|unique:users,username|min:4|max:255',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8|confirmed',
            'firstName' => 'required|min:4',
            'lastName' => 'required|min:5',
            'dob' => 'required|before:today',
            'gender' => 'required'
        ];
    }

    /**
     *  Messages to return
     * 
     *  @return array
     */
    public function messages()
    {
        return [
            'dob.before' => 'The birthday must be a valid day'
        ];
    }
}
