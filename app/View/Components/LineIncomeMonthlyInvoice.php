<?php

namespace App\View\Components;

use App\Models\LineIncomeMonthly;
use App\Models\User;
use Illuminate\View\Component;

class LineIncomeMonthlyInvoice extends Component
{
    public $client;
    public $lineIncomeMonthly;
    public $showBankFees;
    public $settings;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(LineIncomeMonthly $lineIncomeMonthly,int $showBankFees=null)
    {
        $this->lineIncomeMonthly = $lineIncomeMonthly;
        $this->showBankFees = $showBankFees;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.line-income-monthly-invoice');
    }
}
