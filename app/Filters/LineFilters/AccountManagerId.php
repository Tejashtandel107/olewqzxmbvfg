<?php

namespace App\Filters\LineFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class AccountManagerId extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
            
        $this->query->where(function($query) use ($value){
            $query->where("lines.account_manager_id",$value)->orWhere("lines.operator_id",$value);
        });        
    }
}
