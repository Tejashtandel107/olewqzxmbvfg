<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Filters\FilterBuilder;
use Carbon\Carbon;
use Helper;

class Line extends Model
{
    use HasFactory;

    protected $primaryKey = 'line_id';
    
    protected $guarded = [];
    
    protected $fillable = [
        'client_id',
        'company_id',
        'register_date',
        'purchase_invoice_from',
        'purchase_invoice_to',
        'purchase_invoice_lines',
        'purchase_invoice_registrations',
        'sales_invoice_from',
        'sales_invoice_to',
        'sales_invoice_lines',
        'sales_invoice_registrations',
        'payment_register_type',
        'payment_register_month_id',
        'payment_register_day',
        'payment_register_daily_records',
        'payment_register_lines',
        'petty_cash_book_type',
        'petty_cash_bank_id',
        'petty_cash_other_bank',
        'petty_cash_book_month_id',
        'petty_cash_book_lines',
        'petty_cash_book_registrations',
        'time_spent_start_time',
        'time_spent_end_time',
        'overtime_extra',
        'overtime_note',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'register_date' => 'datetime:Y-m-d 00:00:00',
    ];

    public function operator()  
    {  
        return $this->belongsTo(User::class,'operator_id');  
    }

    public function client()  
    {  
        return $this->belongsTo(User::class,'client_id');   
    }
    public function accountmanager()  
    {  
        return $this->belongsTo(User::class,'account_manager_id');   
    }

    public function company()  
    {  
        return $this->belongsTo(Company::class,'company_id');   
    }
    public function bank() 
    {  
        return $this->belongsTo(Bank::class,'bank_id','bank_id');  
    }     
    public function accountManagerExpenseMonthly():HasOne
    {
        return $this->hasOne(LineExpenseMonthly::class, 'user_id', 'account_manager_id')
                ->where(DB::raw('MONTH(pricing_date)'),"=",$this->register_date->month)
                ->where(DB::raw('YEAR(pricing_date)'),"=",$this->register_date->year);
    }      

    public function operatorExpenseMonthly():HasOne
    {
        return $this->hasOne(LineExpenseMonthly::class, 'user_id', 'operator_id')
                ->where(DB::raw('MONTH(pricing_date)'),"=",$this->register_date->month)
                ->where(DB::raw('YEAR(pricing_date)'),"=",$this->register_date->year);
    }      
    public function clientIncomeMonthly():HasOne
    {
        return $this->hasOne(LineIncomeMonthly::class, 'user_id', 'client_id')
                ->where(DB::raw('MONTH(pricing_date)'),"=",$this->register_date->month)
                ->where(DB::raw('YEAR(pricing_date)'),"=",$this->register_date->year);
    }      

    protected function paymentRegisterMonthId(): Attribute
    {
        return Attribute::make(
            get: function ($value): array {
                if(empty($value))      
                    return array();
                else
                    return explode(',',$value);
            }
        );
    }
    protected function pettyCashBookMonthId(): Attribute
    {
        return Attribute::make(
            get: function ($value): array {
                if(empty($value))      
                    return array();
                else
                    return explode(',',$value);
            }
        );
    }
    protected function paymentRegisterType(): Attribute
    {
        return Attribute::make(
            set: function ($value){
                if(!in_array($value,array("Paghe","Corrispettivi")))
                    return "Corrispettivi";
                else
                    return ucwords($value);
            }
        );
    }

    protected function timeSpent(): Attribute
    {
        return Attribute::make(
            get: function ($value, array $attributes): string {
                $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $attributes["time_spent_start_time"]);
                $end_time = Carbon::createFromFormat('Y-m-d H:i:s', $attributes["time_spent_end_time"]);
                
                return $end_time->longAbsoluteDiffForHumans($start_time,2);
            }
        );
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function timeSpentStartTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::DateFormat($value,config('constant.DATE_TIME_FORMAT')),
            set: fn ($value) => Helper::convertDateFormat($value,config('constant.DATE_TIME_FORMAT')),
        );
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function timeSpentEndTime(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::DateFormat($value,config('constant.DATE_TIME_FORMAT')),
            set: fn ($value) => Helper::convertDateFormat($value,config('constant.DATE_TIME_FORMAT')),
        );
    }
    /**
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::DateFormat($value),
        );
    }
    /**
     * Get the item's updated at.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Helper::DateFormat($value),
        );
    }

    /**
     * Return Appropriate Account Manager ID for the Client
     */
    public function getAccountManagerByClientId($client_id=0){
        $manager_client = ManagerClientAssignment::where("client_id",$client_id)->where("is_primary",1)->orderBy('created_at')->first();
        if($manager_client){
            return $manager_client->account_manager_id;
        }
        return null;
    }

    public function scopeFilterBy($query, $filters)
    {
        $namespace = 'App\Filters\LineFilters';
        $filter = new FilterBuilder($query, $filters, $namespace);

        return $filter->apply();
    }
}
?>
