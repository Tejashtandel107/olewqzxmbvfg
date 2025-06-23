<?php

namespace App\Filters\LineFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class AccountManagerLines extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if(empty($value))
            return;
            
        $this->query->where("lines.account_manager_id",">",0);
    }
}
