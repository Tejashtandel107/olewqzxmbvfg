<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ClientLinesExport implements FromView,ShouldAutoSize
{
    protected $lineTotals;

    public function __construct($lineTotals)
    {
        $this->lineTotals = $lineTotals;
    }
   
    /**
    * @return \Illuminate\Support\Collection
    */ public function view(): View
    {
        return view('export.client-line-excel',$this->lineTotals);
    }
   
}
