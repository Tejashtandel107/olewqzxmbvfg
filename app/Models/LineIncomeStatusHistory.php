<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use App\Facades\Helper;

class LineIncomeStatusHistory extends Model
{
    protected $table = 'line_income_status_histories';

    protected $fillable = ['line_income_monthly_id', 'status', 'notes','user_id'];

    public function lineIncomeMonthly()
    {
        return $this->belongsTo(LineIncomeMonthly::class, 'line_income_id');
    }
    protected function statusClass(): Attribute
    {
        $class = '';
        if (!empty($this->status)) {           
            switch(strtolower($this->status)){
                case "paid":
                case "devpos invoice: success":
                    $class = "success"; 
                break;
                case "invoice sent":
                case "invoice follow up-1 sent":
                    $class = "primary"; 
                break;
                case "partially paid":
                    $class = "warning";
                break;
                default :
                    $class = "danger";
            }   
        }
        return Attribute::make(
            get: fn ($value,$attributes) => ($class),
        );
    }
}
