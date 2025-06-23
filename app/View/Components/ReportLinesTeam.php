<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ReportLinesTeam extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public Collection $lineTotals,
    ) {}
    
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.report-lines-team');
    }
}
