<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LineForm extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public $line=null,
        public $clients=[],
        public $companies=null,
        public $months,
        public $pettyCashBookTypes,
        public $banks,
    ) {}
    public function isCompanySelected($company){
        return $company->company_id === ($this->line->company_id);
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.line-form');
    }
}
