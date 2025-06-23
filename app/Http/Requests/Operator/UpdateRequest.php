<?php

namespace App\Http\Requests\Operator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Helper;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->operator);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $client_role_id = config('constant.ROLE_CLIENT_ID');
        $account_manager_role_id = config('constant.ROLE_ACCOUNT_MANAGER_ID');
        $pricing_types = array_keys(Helper::getOperatorPricingTypes());
        
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users,email,' . $this->operator->user_id . ',user_id'],
            'password' => ['nullable', 'min:6', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'pricing_type'=>['required', Rule::in($pricing_types)],
            'price'=>['required','numeric','gte:0'],
            'price_righe_ordinaria'=>['required','numeric','gte:0'],
            'price_righe_semplificata'=>['required','numeric','gte:0'],
            'price_righe_corrispettivi_semplificata'=>['required','numeric','gte:0'],
            'price_righe_paghe_semplificata'=>['required','numeric','gte:0'],
            'price_righe_am_ordinaria'=>['required','numeric','gte:0'],
            'price_righe_am_semplificata'=>['required','numeric','gte:0'],
            'price_righe_am_corrispettivi_semplificata'=>['required','numeric','gte:0'],
            'price_righe_am_paghe_semplificata'=>['required','numeric','gte:0'],
            'bonus_target'=>['required','numeric','gte:0'],
            'photo' => ["nullable", "image"],
            'account_manager_id' => [
                'required',
                Rule::exists('users', 'user_id')->where('role_id', $account_manager_role_id),
            ],
            'client_id' => [
                'required_if:status,1',
                Rule::exists('users', 'user_id')->where('role_id', $client_role_id),
            ],
        ];
    }
}
