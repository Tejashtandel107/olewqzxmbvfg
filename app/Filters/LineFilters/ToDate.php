<?php

namespace App\Filters\LineFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;
use DateTime;

class ToDate extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
            
        $toDateTime = DateTime::createFromFormat(config('constant.DATE_FORMAT'), $value);

        $this->query->where('register_date','<=', $toDateTime->format('Y-m-d'));
    }
}
