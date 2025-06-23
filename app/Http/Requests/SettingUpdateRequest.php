<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string'],
            'company_address' =>['required', 'string'],
            'company_city' => ['required', 'string'],
            'company_postal_code' => ['required', 'string'],
            'country_name' => ['required', 'string'],
            'devpos_tenant' =>['required', 'string'],
            'devpos_username' =>['required', 'string'],
            'devpos_password' =>['required', 'string'],
            'devpos_business_unit_code' =>['required', 'string'],
            'devpos_operator_code' =>['required', 'string'],
        ];
    }
}
