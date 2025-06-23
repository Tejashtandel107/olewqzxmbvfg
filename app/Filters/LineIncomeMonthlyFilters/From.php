<?php

namespace App\Filters\LineIncomeMonthlyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;
use Illuminate\Support\Carbon;

class From extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
        
        $dt = Carbon::createFromFormat('d/m/Y', "01/" . $value);
        $this->query->where('line_incomes_monthly.pricing_date','>=', $dt->format('Y-m-01'));
    }
}
