<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LineIncomeMonthly extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public $lineIncomesMonthly,
        public string $className
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.line-income-monthly');
    }
}
