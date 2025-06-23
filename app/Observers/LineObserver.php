<?php

namespace App\Observers;

use App\Models\Line;
use App\Services\LineIncomeMonthlyService;
use App\Services\LineExpenseMonthlyService;

class LineObserver
{	
	/**
     * Handle the Line "created" event.
     *
     * @param  \App\Models\Line  $line
     * @return void
     */
    public function created(Line $line)
    {
        $this->handleMonthlyTotal($line);
    }

	/**
     * Handle the Line "updated" event.
     *
     * @param  \App\Models\Line  $line
     * @return void
     */
    public function updated(Line $line)
    {
        $this->handleMonthlyTotal($line);
    }

	/**
     * Handle the Line "deleted" event.
     *
     * @param  \App\Models\Line  $line
     * @return void
     */
    public function deleted(line $line)
    {
        $this->handleMonthlyTotal($line);
    }

    public function saving(Line $line)
    {
        $line->purchase_invoice_lines = ($line->line_type != 'Ordinaria') ? $line->purchase_invoice_registrations :  $line->purchase_invoice_lines;
        $line->sales_invoice_lines = ($line->line_type != 'Ordinaria') ? $line->sales_invoice_registrations :  $line->sales_invoice_lines;

        if($line->line_pricing_type=='per_registrazioni'){
			$line->total_passive_lines =  empty($line->purchase_invoice_registrations) ? 0 : $line->purchase_invoice_registrations;
			$line->total_active_lines =  empty($line->sales_invoice_registrations) ? 0 : $line->sales_invoice_registrations;

			if($line->payment_register_type == 'Corrispettivi'){
				$line->total_corrispetivvi_lines =  empty($line->payment_register_daily_records) ? 0 : $line->payment_register_daily_records;
				$line->total_paghe_lines = 0;
			}
			else{
				$line->total_paghe_lines =  empty($line->payment_register_daily_records) ? 0 : $line->payment_register_daily_records;
				$line->total_corrispetivvi_lines = 0;
			}

			if($line->line_type == 'Ordinaria'){
				$line->total_prima_nota_lines =  empty($line->petty_cash_book_registrations) ? 0 : $line->petty_cash_book_registrations;
        	}
        }
        else{
			$line->total_passive_lines = empty($line->purchase_invoice_lines) ? 0 : $line->purchase_invoice_lines;
			$line->total_active_lines = empty($line->sales_invoice_lines) ? 0 : $line->sales_invoice_lines;
			if($line->payment_register_type == 'Corrispettivi'){
				$line->total_corrispetivvi_lines = empty($line->payment_register_lines) ? 0 : $line->payment_register_lines;
				$line->total_paghe_lines = 0;
        	}
			else{
				$line->total_paghe_lines = empty($line->payment_register_lines) ? 0 : $line->payment_register_lines;
				$line->total_corrispetivvi_lines = 0;
			}

			if($line->line_type == 'Ordinaria'){
				$line->total_prima_nota_lines = empty($line->petty_cash_book_lines) ? 0 : $line->petty_cash_book_lines;
			}
        }        
    }

	private function handleMonthlyTotal(Line $line){
        (new LineIncomeMonthlyService)->syncStaus($line);
        
        if($line->operator->isAccountManager() or $line->operator->isOperator()){
            (new LineExpenseMonthlyService)->syncStaus($line);
        }
	}
}
