<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\User;
use Helper;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', User::class);
    }
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
		if($this->user()->isAccountManager()){
			$this->merge([
                'primary_account_manager_id' => $this->user()->user_id,
                'pricing_type' => 'per_registrazioni',
                'price' => 0,
                'milestone'=>0,
                'price_ordinaria' => 0.40,
                'price_semplificata' => 0.50,
                'price_corrispettivi_semplificata' => 10.00,
                'price_paghe_semplificata'=>3.00
            ]);
            $this->request->remove('base_price_level_id');
            $this->request->remove('price_level_id');
		}
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $account_manager_role_id = config('constant.ROLE_ACCOUNT_MANAGER_ID');
        $operator_role_id = config('constant.ROLE_OPERATOR_ID');
        $pricing_types = array_keys(Helper::getStudioPricingTypes());
        
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'max:255'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'photo' => ["nullable", "image"],
            'account_manager_id' => [
                'nullable',
                Rule::exists('users', 'user_id')->where('role_id', $account_manager_role_id)
            ],
            'operator_id' => [
                'nullable',
                Rule::exists('users', 'user_id')->where('role_id', $operator_role_id),
            ],
            'pricing_type'=>['required', Rule::in($pricing_types)], 
            'price'=>['required','numeric','gte:0'],
            'milestone'=>['required','numeric','gte:0'],
            'price_ordinaria'=>['required','numeric','gte:0'],
            'price_semplificata'=>['required','numeric','gte:0'],
            'price_paghe_semplificata'=>['required','numeric','gte:0'],
            'vat_number'=>['required', 'max:255'],
            'activation_date' => ['nullable','date_format:m/Y'],
            'price_level_id'  => ['required_with:activation_date'], 
            'base_price_level_id'  => ['required_with:activation_date'],
            'address' => ['required', 'max:255'],
            'city' => ['required', 'max:255'],
            'postal_code' => ['required', 'max:255'],
            'country_id' => ['required', 'integer']
        ];
    }
}
