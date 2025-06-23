<?php

namespace App\Filters\LineFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;
use DateTime;

class FromDate extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;

        $fromDateTime = DateTime::createFromFormat(config('constant.DATE_FORMAT'), $value);

        $this->query->where('register_date','>=', $fromDateTime->format('Y-m-d'));
    }
}
