<?php

namespace App\Console\Commands;

use App\Models\LineIncomeMonthly;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateMonthlyIncomeInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly-income-invoice:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates invoice status to draft if invoice ready';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->handleOverdueLineIncome();
        // if (now()->day === 1 || now()->day === 2) {
            $this->handleDraftLineIncome();  
        // }
        return Command::SUCCESS;
    }
    /**
     * Update invoices to draft status.
     */
    private function handleDraftLineIncome()
    {
        $results = LineIncomeMonthly::where('pricing_date','<=',Carbon::now()->firstOfMonth()->subMonth())
                                    ->whereNull('invoice_status')
                                    ->where(function ($query) {
                                        $query->where('price', '>', 0)
                                              ->orWhere('total_bonus', '>', 0);
                                    })
                                    ->get();
        if(count($results)){    
            foreach ($results as $lineIncome) {
                $lineIncome->invoice_status = 'draft';
                $lineIncome->save();   
            } 
        }
    }
    
    private function handleOverdueLineIncome()
    {
        $lineIncomesMonthly = LineIncomeMonthly::whereNotNull('invoice_number')
                            ->where('pricing_date','<=',Carbon::now()->firstOfMonth()->subMonth())
                            ->whereNotNull('invoice_due_date')
                            ->where('invoice_due_date', '<=', now())
                            ->whereNotIn('invoice_status', ['paid', 'overdue'])                              
                            ->get();

        if(count($lineIncomesMonthly)){    
            foreach ($lineIncomesMonthly as $lineIncomeMonthly) {
                $lineIncomeMonthly->invoice_status = 'overdue';
                $lineIncomeMonthly->save();
                $lineIncomeMonthly->createStatusHistory('overdue', 'Lo stato della fattura è stato impostato automaticamente dal sistema', 1);
            }
        }
    }
}
