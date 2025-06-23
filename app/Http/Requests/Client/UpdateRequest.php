<?php

namespace App\Http\Requests\Client;

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
        return $this->user()->can('update', $this->client);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
		if(!$this->user()->isSuperAdmin()){
            $this->request->remove('primary_account_manager_id');
            $this->request->remove('secondary_account_manager_id');
            $this->request->remove('pricing_type');
            $this->request->remove('price');
            $this->request->remove('milestone');
            $this->request->remove('price_ordinaria');
            $this->request->remove('price_semplificata');
            $this->request->remove('price_corrispettivi_semplificata');
            $this->request->remove('apply_price_change');
            $this->request->remove('price_change_start_date');
            $this->request->remove('price_change_end_date');
            $this->request->remove('price_level_id');
            $this->request->remove('base_price_level_id');
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
        $date_format = config('constant.DATE_FORMAT');
        
        return [
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users,email,' . $this->segment(3) . ',user_id'],
            'address' => ['required', 'max:255'],
            'city' => ['required', 'max:255'],
            'postal_code' => ['required', 'max:255'],
            'country_id' => ['required', 'integer'],
            'password' => ['nullable', 'min:6', 'max:255'],
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
            'pricing_type'=>['sometimes','required', Rule::in($pricing_types)], 
            'price'=>['sometimes','required','gte:0'],
            'milestone'=>['sometimes','required','numeric','gte:0'],
            'price_ordinaria'=>['sometimes','required','numeric','gte:0'],
            'price_semplificata'=>['sometimes','required','numeric','gte:0'],
            'price_paghe_semplificata'=>['sometimes','required','numeric','gte:0'],
            'price_change_start_date'=>['required_if_accepted:apply_price_change','date_format:' . $date_format],
            'price_change_end_date'=>['required_if_accepted:apply_price_change','date_format:' . $date_format],
            'vat_number'=>['required', 'max:255'],
            'activation_date' => ['nullable','date_format:m/Y'],
            'price_level_id'  => ['required_with:activation_date'], 
            'base_price_level_id'  => ['required_with:activation_date'], 
        ];
    }
}
