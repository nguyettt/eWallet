<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Category;

class CategoryFormRequest extends FormRequest
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
        $cat = Category::where('user_id', auth()->user()->id)->pluck('id')->all();
        return [
            'type' => [
                'required',
                Rule::in(['income', 'outcome']),
            ],
            'name' => [
                'required',
                Rule::unique('categories')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                }),
            ],
            'parent_id' => [
                'required',
                Rule::in($cat),
            ],
        ];
    }
}
