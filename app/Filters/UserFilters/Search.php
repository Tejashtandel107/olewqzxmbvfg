<?php

namespace App\Filters\UserFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class Search extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
            
        $this->query->where(function ($q) use($value) { //  use function to pass data inside
            $q->orWhere('name', 'LIKE','%' . $value . '%')->orWhere('email', 'LIKE','%' . $value . '%');
        });
    }
}
