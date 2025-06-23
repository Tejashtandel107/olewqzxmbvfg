<?php

namespace App\Filters\LineFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class OperatorId extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
            
        $this->query->where("lines.operator_id",$value);
    }
}
