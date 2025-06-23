<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\CompanyResource;

class LineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "accountId" => $this->line_id,
            "clientId" => $this->client_id,
            'companyId' => $this->company_id,
            "createdBy" => $this->operator_id,
            "accountManagerId" => $this->account_manager_id,
            "registerDate" => $this->register_date,
            'purchaseInvoiceFrom' => $this->purchase_invoice_from,
            'purchaseInvoiceTo' => $this->purchase_invoice_to,
            'purchaseInvoiceLines' => $this->purchase_invoice_lines,
            'purchaseInvoiceRegistrations' => $this->purchase_invoice_registrations,
            'salesInvoiceFrom' => $this->sales_invoice_from,
            'salesInvoiceTo' => $this->sales_invoice_to,
            'salesInvoicelines' => $this->sales_invoice_lines,
            'salesInvoiceRegistrations' => $this->sales_invoice_registrations,
            'paymentRegisterMonthId' => $this->payment_register_month_id,
            'paymentRegisterDay' => $this->payment_register_day,
            'paymentRegisterDailyRecords' => $this->payment_register_daily_records,
            'paymentRegisterLines' => $this->payment_register_lines,
            'pettyCashBookType'  => $this->petty_cash_book_type,
            'pettyCashBookMonthId' => $this->petty_cash_book_month_id,
            'pettyCashBookLines' => $this->petty_cash_book_lines,
            'timeSpentStartTime' => $this->time_spent_start_time,
            'timeSpentEndTime' => $this->time_spent_end_time,
            'overtimeExtra' => $this->overtime_extra,
            'overtimeNote' => $this->overtime_note,
        ];
    }
}
