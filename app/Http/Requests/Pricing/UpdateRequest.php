<?php

namespace App\Http\Requests\Pricing;

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
        return $this->user()->can('create', $this->pricing);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $pricing_types = array_keys(Helper::getPricingTypes());

        return [
            'pricing_type'=>['required', Rule::in($pricing_types)],
            'price'=>['required','numeric','gte:0'],
            'price_ordinaria'=>['required','numeric','gte:0'],
            'price_semplificata'=>['required','numeric','gte:0'],
            'milestone'=>['required','numeric','gte:0'],
        ];
    }
}
