<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ReportTable extends Component
{
    public $lines;
    public $months;
    
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($lines,$months)
    {
        $this->lines = $lines;
        $this->months = $months;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.report-table');
    }
}
