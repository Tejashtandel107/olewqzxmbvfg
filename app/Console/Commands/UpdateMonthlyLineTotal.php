<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LineExpenseMonthlyService;
use App\Services\LineIncomeMonthlyService;
use App\Models\LineExpenseMonthly;
use App\Models\LineIncomeMonthly;

class UpdateMonthlyLineTotal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly-line-total:update {--user_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update user(s) monthly line total during the given period.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->updateIncomesMonthlyTotal();
        $this->info("Users monthly income totals updated successfully.");
        $this->updateExpensesMonthlyTotal();
        $this->info("Users monthly expense totals updated successfully.");
        return Command::SUCCESS;
    }

    private function updateIncomesMonthlyTotal(): void{
        $lineIncomeMonthlyService = new LineIncomeMonthlyService;
        $query = LineIncomeMonthly::where('sync_total', 1)->orderBy('pricing_date','desc');
        if($this->option('user_id')){
            $query->where("user_id",$this->option('user_id'));
        }
        $lineIncomesMonthly = $query->get();
        foreach($lineIncomesMonthly as $lineIncomeMonthly){
            $lineIncomeMonthlyService->updateTotal($lineIncomeMonthly);
        }
    }

    private function updateExpensesMonthlyTotal(): void{
        $lineExpenseMonthlyService = new LineExpenseMonthlyService;
        $query = LineExpenseMonthly::where('sync_total', 1)->orderBy('pricing_date','desc');
        if($this->option('user_id')){
            $query->where("user_id",$this->option('user_id'));
        }
        $lineExpensesMonthly = $query->get();
        foreach($lineExpensesMonthly as $lineExpenseMonthly){
            $lineExpenseMonthlyService->updateTotal($lineExpenseMonthly);
            $lineExpenseMonthlyService->updateAvgLineCost($lineExpenseMonthly->pricing_date->format('Y-m-d'));
        }
    }
}
