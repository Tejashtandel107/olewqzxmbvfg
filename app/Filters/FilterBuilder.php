<?php

namespace App\Filters;
use Illuminate\Support\Str;

class FilterBuilder
{
    protected $query;
    protected $filters;
    protected $namespace;

    public function __construct($query, $filters, $namespace)
    {
        $this->query = $query;
        $this->filters = $filters;
        $this->namespace = $namespace;
    }

    public function apply()
    {
        foreach ($this->filters as $name => $value) {
            $normailizedName = ucfirst(Str::camel($name));
            $class = $this->namespace . "\\{$normailizedName}";
            if (! class_exists($class)) {   
                continue;
            }

            (new $class($this->query))->handle($value);
        }

        return $this->query;
    }
}
