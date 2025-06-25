<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Filters\FilterBuilder;
use App\Facades\Helper;

class LineIncomeMonthly extends Model
{
    use HasFactory;
    protected $table = 'line_incomes_monthly';

    protected $fillable = [
        'user_id',
        'pricing_type',
        'price',
        'price_ordinaria',
        'price_semplificata',
        'price_corrispettivi_semplificata',
        'price_paghe_semplificata',
        'milestone',
        'pricing_date',
        'sync_total',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pricing_date' => 'date:Y-m-d',
    ];

    /**
     * Get the user that owns the pricing.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function line()
    {
        return $this->belongsTo(Line::class, 'user_id', 'client_id');
    }
    public function lineIncomeStatuses()
    {
        return $this->hasMany(LineIncomeStatusHistory::class, 'line_income_monthly_id')->orderBy('created_at', 'desc')->orderBy('id','desc');
    }
    public function latestLineIncomeStatus()
    {
        return $this->hasOne(LineIncomeStatusHistory::class, 'line_income_monthly_id')->latest('created_at')->latest('id');
    }
    /**
     * Interact with the invoice number.
     *
     * @return  \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function invoiceNumber(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => str_replace('/', '', $attributes['invoice_number'] ?? ''),
        );
    }
    protected function totalAmount(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => ($attributes['total_bonus'] + $attributes['price']),
        );
    }
    protected function invoiceDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::parse($value)->translatedFormat('d M Y') : 'N/A'
        );
    }
    protected function invoiceDueDate(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::parse($value)->translatedFormat('d M Y') : 'N/A'
        );
    }
    protected function invoiceReference(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => ($this->pricing_date->translatedFormat('M')),
        );
    }
    public function isInvoiceReady()
    {
        if ($this->pricing_date->lessThanOrEqualTo(Carbon::now()->firstOfMonth()->subMonth()) && $this->total_amount > 0) {
            return true;
        }
        return false;
    }
    protected function totalOutstanding(): Attribute
    {
        return Attribute::make(
            get: fn($value, $attributes) => ($this->total_amount - $this->total_paid),
        );
    }
    
    public function markPaid($request)
    {
        if ($this->isInvoiceReady()) {
            if ((!empty($this->invoice_number)) && $this->invoice_status !== 'cancel') {
                $outstandingAmount = $this->total_outstanding;
                if(isset($request['total_paid'])){
                    $totalPaid = ($request['total_paid'] > $outstandingAmount) ? $outstandingAmount : $request['total_paid'];
                    $this->total_paid += $totalPaid;
                }
                if(isset($request['bank_fees'])){
                    $this->bank_fees += $request['bank_fees'];
                }

                $status = (strval($this->total_paid) == strval($this->total_amount)) ? "paid" : "partially paid";
                $notesformat = "Paid: %s.Bank Fees: %s.Total Outstanding: %s";  
                $notes = sprintf($notesformat,html_entity_decode(Helper::formatAmount($totalPaid)),html_entity_decode(Helper::formatAmount($request['bank_fees'])),html_entity_decode(Helper::formatAmount($this->total_outstanding)));
                
                $this->invoice_status = $status;
                $this->save();
            }
            else{
                $status = "invoice number unavailable";
                $notes = "Si prega di generare prima un numero di fattura.";
            }
        }
        else{
            $status =  "not ready yet";
            $notes = "La fattura non è ancora pronta per essere contrassegnata come pagata.";
        }

        if(isset($status)){
            $this->createStatusHistory($status,$notes);
            return ($status=="paid" or $status=="partially paid") ? true : false;
        }
        return false;
    }
    public function markCancel(){
        $this->invoice_status = 'cancel';
        $this->save();
        return true;
    }
   
    public function createStatusHistory(string $status, string $notes = "",$authId = "")
    {
        $this->lineIncomeStatuses()->create([
            'status' => $status,
            'notes' => $notes,
            'user_id' =>Auth::id() ?? $authId,
        ]);
    }
    protected function statusClass(): Attribute
    {
        $class = '';
        if (!empty($this->invoice_status)) {           
            switch(strtolower($this->invoice_status)){
                case "paid":
                case "devpos invoice: success":
                    $class = "success"; 
                break;
                case "unpaid":
                    $class = "primary"; 
                break;
                case "partially paid":
                    $class = "warning";
                break;
                case "draft":
                    $class = "secondary";
                break;
                default :
                    $class = "danger";
            }   
        }
        return Attribute::make(
            get: fn ($value,$attributes) => ($class),
        );
    }
    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Filters\LineIncomeMonthlyFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
