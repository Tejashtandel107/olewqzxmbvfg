<?php

namespace App\Http\Requests\Company;

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
        return $this->user()->can('create', $this->company);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $client_role_id = config('constant.ROLE_CLIENT_ID');
        $company_types = array_keys(Helper::getCompanyTypes());
        $business_types = array_keys(Helper::getBusinessTypes());

        return [
            'user_id' => [
                'required',
                Rule::exists('users','user_id')->where('role_id', $client_role_id),
            ],
            'company_name' => ['required', 'max:255'],
            'company_type' => ['required', Rule::in($company_types)],
            'vat_tax' => ['required', 'max:255'],
            'business_type' => ['required', Rule::in($business_types)],
        ];
    }
}
