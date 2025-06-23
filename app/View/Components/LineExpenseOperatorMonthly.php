<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class LineExpenseOperatorMonthly extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public Collection $lineExpensesMonthly,
        public string $className,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.line-expense-operator-monthly');
    }
}
