<?php

namespace App\Http\Requests\Line;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Line;
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
        return $this->user()->can('create', Line::class);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
		if($this->petty_cash_book_type == 'Banca'){
            if($this->petty_cash_bank_id > 0){
                $this->merge(['petty_cash_other_bank' => null]);
            }
        }
        else{
            $this->merge(['petty_cash_bank_id' => null]);
            $this->merge(['petty_cash_other_bank' => null]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        $payment_register_month_id  = (!empty($this->payment_register_month_id)) ? implode(',',$this->payment_register_month_id) : null;
        $petty_cash_book_month_id   = (!empty($this->petty_cash_book_month_id)) ? implode(',',$this->petty_cash_book_month_id) : null;

        $this->merge([
            'payment_register_month_id' =>$payment_register_month_id,
            'petty_cash_book_month_id'=>$petty_cash_book_month_id,
            "register_date"=>Helper::convertDateFormat($this->register_date)
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $date_format = config('constant.DATE_FORMAT');
        $datetime_format = config('constant.DATE_TIME_FORMAT');
        $petty_cash_book_type = array_keys(Helper::getPettyCashBookTypes());
        $months = array_keys(Helper::getMonths());

        return [
            'client_id' => [
                'required',
                Rule::exists('companies', 'user_id')->where('company_id',$this->company_id)
            ],
            'company_id' => [
                'required',
                Rule::exists('companies', 'company_id'),
            ],
            'register_date' => ['required',"date_format:".$date_format],
            'purchase_invoice_from' => ['nullable','numeric','gte:0'],
            'purchase_invoice_to' => ['nullable','numeric','gte:0'],
            'purchase_invoice_lines' => ['nullable','numeric','gte:0'],
            'purchase_invoice_registrations' => ['nullable','numeric','gte:0'],
            'sales_invoice_from' => ['nullable','numeric','gte:0'],
            'sales_invoice_to' => ['nullable','numeric','gte:0'],
            'sales_invoice_lines' => ['nullable','numeric','gte:0'],
            'sales_invoice_registrations' => ['nullable','numeric','gte:0'],
            'payment_register_month_id'=>[
                'nullable',
                'array',
            ],
            'payment_register_month_id.*' => Rule::in($months),
            'payment_register_day'=>['nullable','numeric','gte:0'],
            'payment_register_daily_records' => ['nullable','numeric','gte:0'],
            'payment_register_type' => Rule::in(["Paghe","Corrispettivi"]),
            'payment_register_lines' => ['nullable','numeric','gte:0'],
            'petty_cash_book_type'=>['nullable', Rule::in($petty_cash_book_type)],
            'petty_cash_bank_id'=>['requiredIf:petty_cash_book_type,Banca'],
            'petty_cash_other_bank'=>['requiredIf:petty_cash_bank_id,0'],
            'petty_cash_book_month_id'=>[
                'nullable',
                'array',
            ],
            'petty_cash_book_month_id.*' => Rule::in($months),
            'petty_cash_book_lines' => ['nullable','numeric','gte:0'],
            'petty_cash_book_registrations' => ['nullable','numeric','gte:0'],
            'time_spent_start_time'=>['nullable','date_format:'.$datetime_format],
            'time_spent_end_time'=>['nullable','date_format:'.$datetime_format],
            'overtime_extra'=>['nullable','numeric','gte:0'],
        ];  
    }
}
