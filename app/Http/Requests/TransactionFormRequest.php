<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Wallet;
use App\Category;

class TransactionFormRequest extends FormRequest
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
            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],
            'wallet_id' => [
                'required',
                'numeric',
                Rule::exists('wallets', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
            ],
            'type' => 'required|in:1,2,3',
            'cat_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
            ],
            'benefit_wallet' => [
                'required_if:type,==,3',
                'numeric',
                'different:wallet_id',
                Rule::exists('wallets', 'id')->where(function ($query) {
                    return $query->where('user_id', auth()->user()->id);
                })
            ]
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'You need to enter the amount of money',
            'amount.numeric' => 'The amount of money must be a number',
            'amount.gte' => 'The amount of money must be greater than 0',
            'wallet_id.required' => 'Choose the wallet',
            'wallet_id.exists' => 'Choose the correct wallet again',
            'cat_id.required' => 'Choose the category',
            'cat_id.exists' => 'Choose the correct category',
            'benefit_wallet.required_id' => 'Choose the wallet you wish to transfer money to',
            'benefit_wallet.different' => 'The targe wallet can not be the same as the origin wallet',
            'benefit_wallet.exists' => 'Choose the correct benefit wallet',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $type = Category::find($this->cat_id)->type;
            $balance = Wallet::find($this->wallet_id)->balance;
            if ($type != 1 && $this->amount > $balance) {
                $validator->errors()->add('amount', 'There is not enough money in wallet');
            }
        });
    }
}
