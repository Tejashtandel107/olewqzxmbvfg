<?php

namespace App\Services;

use App\Models\Line;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use App\Models\LineExpenseMonthly;
use App\Models\LineIncomeMonthly;
use App\Models\LineExpenseMonthlyBonus;
use Illuminate\Support\Carbon;

class LineExpenseMonthlyService
{
    public function getOperatorExpenses(array $filters = []): Collection
    {
        return LineExpenseMonthly::select('line_expenses_monthly.*','users.name')
                                    ->leftJoin('users', 'users.user_id', '=', 'line_expenses_monthly.user_id')                    
                                    ->orderBy('pricing_date','desc')
                                    ->orderBy('users.name','asc')
                                    ->where("users.role_id",config('constant.ROLE_OPERATOR_ID'))
                                    ->filterBy($filters)
                                    ->get();
    }
    
    public function getAccountManagerExpenses(array $filters= []): Collection
    {
        $query =LineExpenseMonthly::select('line_expenses_monthly.user_id as account_manager_id', 'line_expenses_monthly.pricing_date', 'line_expenses_monthly.total_lines_ordinaria', 'line_expenses_monthly.total_lines_semplificata', 'line_expenses_monthly.total_corrispettivi_lines_semplificata', 'line_expenses_monthly.total_paghe_lines_semplificata', 'line_expenses_monthly.total_bonus_ordinaria', 'line_expenses_monthly.total_bonus_semplificata', 'line_expenses_monthly.total_bonus_corrispettivi_semplificata', 'line_expenses_monthly.total_bonus_paghe_semplificata', 'line_expenses_monthly.total_bonus', 'line_expenses_monthly.total_lines','line_expenses_monthly.price','line_expenses_monthly.pricing_type')
                                    ->selectRaw('0 as operator_id')
                                    ->selectRaw('users.name as operator_name')
                                    ->leftJoin('users', 'users.user_id', '=', 'line_expenses_monthly.user_id')
                                    ->where("users.role_id", config('constant.ROLE_ACCOUNT_MANAGER_ID'))
                                    ->filterBy($filters);

        $bonus_query = LineExpenseMonthlyBonus::select('line_expense_monthly_bonuses.user_id as account_manager_id', 'line_expense_monthly_bonuses.pricing_date', 'line_expense_monthly_bonuses.total_lines_ordinaria', 'line_expense_monthly_bonuses.total_lines_semplificata', 'line_expense_monthly_bonuses.total_corrispettivi_lines_semplificata', 'line_expense_monthly_bonuses.total_paghe_lines_semplificata', 'line_expense_monthly_bonuses.total_bonus_ordinaria', 'line_expense_monthly_bonuses.total_bonus_semplificata', 'line_expense_monthly_bonuses.total_bonus_corrispettivi_semplificata', 'line_expense_monthly_bonuses.total_bonus_paghe_semplificata', 'line_expense_monthly_bonuses.total_bonus', 'line_expense_monthly_bonuses.total_lines')
                    ->selectRaw('0 as price')
                    ->selectRaw("'Operator Bonus' as pricing_type")
                    ->selectRaw('line_expense_monthly_bonuses.source_user_id as operator_id')
                    ->selectRaw('users.name as operator_name')
                    ->leftJoin('users', 'users.user_id', '=', 'line_expense_monthly_bonuses.source_user_id')
                    ->filterBy($filters);

        return $query->union($bonus_query)->orderBy('pricing_date', 'desc')->orderBy('operator_id', 'asc')->orderBy('operator_name', 'asc')->get();
    }

    public function updateTotal(LineExpenseMonthly $lineExpenseMonthly=null)
    {
        $filters = [
            'from_date' => $lineExpenseMonthly->pricing_date->startOfMonth()->format(config('constant.DATE_FORMAT')),
            'to_date' => $lineExpenseMonthly->pricing_date->endOfMonth()->format(config('constant.DATE_FORMAT')),
            "operator_id" => $lineExpenseMonthly->user_id
        ];

        $user = $lineExpenseMonthly->user;
        $lineOperatorService = new LineOperatorService;
        $results =  $lineOperatorService->getLineTotalsByUser($filters);
        $totals = $lineOperatorService->calculateTotals($results,'operator');

        $lineExpenseMonthly->total_lines_ordinaria = $totals->total_lines_ordinaria;
        $lineExpenseMonthly->total_lines_semplificata = $totals->total_lines_semplificata;
        $lineExpenseMonthly->total_corrispettivi_lines_semplificata = $totals->total_corrispettivi_lines_semplificata;
        $lineExpenseMonthly->total_paghe_lines_semplificata = $totals->total_paghe_lines_semplificata;
        $lineExpenseMonthly->total_lines = $totals->total_lines;

        $lineExpenseMonthly->total_bonus_ordinaria = 0;
        $lineExpenseMonthly->total_bonus_semplificata = 0;
        $lineExpenseMonthly->total_bonus_corrispettivi_semplificata = 0;
        $lineExpenseMonthly->total_bonus_paghe_semplificata = 0;
        $lineExpenseMonthly->total_bonus = 0;

        if ($lineExpenseMonthly->pricing_type == 'per_righe_and_per_registrazioni' or ($lineExpenseMonthly->pricing_type == 'salary_plus_bonus' && $totals->total_lines > $lineExpenseMonthly->bonus_target)) {
            $lineExpenseMonthly->total_bonus_ordinaria = $totals->total_bonus_ordinaria;
            $lineExpenseMonthly->total_bonus_semplificata = $totals->total_bonus_semplificata;
            $lineExpenseMonthly->total_bonus_corrispettivi_semplificata = $totals->total_bonus_corrispettivi_semplificata;
            $lineExpenseMonthly->total_bonus_paghe_semplificata = $totals->total_bonus_paghe_semplificata;
            $lineExpenseMonthly->total_bonus = $totals->total_bonus;
        }
        $lineExpenseMonthly->sync_total=0;
        $lineExpenseMonthly->save();

        //Check Account Manager Bonuses
        if ($user->isOperator()) {
            $lineExpenseMonthly->sourceUserLineExpenseBonuses()->delete();
            if ($lineExpenseMonthly->pricing_type == 'per_righe_and_per_registrazioni' or ($lineExpenseMonthly->pricing_type == 'salary_plus_bonus' && $totals->total_lines > $lineExpenseMonthly->bonus_target)) {
                $grouped = $results->where("account_manager_id", ">", 0)->groupBy('account_manager_id');
                foreach ($grouped as $accountManagerId => $accountManagersLines) {
                    $totals = $lineOperatorService->calculateTotals($accountManagersLines,'account-manager');
                    $lineExpenseMonthly->sourceUserLineExpenseBonuses()->create(
                        [
                            'user_id' => $accountManagerId,
                            'pricing_date' => $lineExpenseMonthly->pricing_date,
                            'total_lines_ordinaria' => $totals->total_lines_ordinaria,
                            'total_lines_semplificata' => $totals->total_lines_semplificata,
                            'total_corrispettivi_lines_semplificata' => $totals->total_corrispettivi_lines_semplificata,
                            'total_paghe_lines_semplificata' => $totals->total_paghe_lines_semplificata,
                            'total_bonus_ordinaria' => $totals->total_bonus_ordinaria,
                            'total_bonus_semplificata' => $totals->total_bonus_semplificata,
                            'total_bonus_corrispettivi_semplificata' => $totals->total_bonus_corrispettivi_semplificata,
                            'total_bonus_paghe_semplificata' => $totals->total_bonus_paghe_semplificata,
                            'total_bonus' => $totals->total_bonus,
                            'total_lines' => $totals->total_lines
                        ]
                    );
                }
            }
        }
    }
    public function updateAvgLineCost($pricingDate=null): void
    {
        if(empty($pricingDate))
            return;
        
        $totals = LineExpenseMonthly::selectRaw('SUM(total_lines) as total_lines')
                ->selectRaw('SUM(total_bonus) as total_bonus')
                ->selectRaw('SUM(IF(pricing_type=?,	price,0)) as total_salary', ["salary_plus_bonus"])
                ->where('pricing_date', $pricingDate)
                ->first();

        $avgLineCost = 0;
        if (!empty($totals->total_lines)) {
            $totalMiscBonus = LineExpenseMonthlyBonus::where('pricing_date', $pricingDate)->sum('total_bonus');
            $totalCost = $totals->total_salary + $totals->total_bonus + $totalMiscBonus;
            $avgLineCost = floatval($totalCost / $totals->total_lines);
        }
        LineIncomeMonthly::where('pricing_date', $pricingDate)->update(['total_cost' =>  DB::raw("total_lines * $avgLineCost")]);
    }
    public function syncStaus(Line $line): bool {
        $endDate = now()->setDay(01);
        $startDate = now()->setDay(01)->subMonths(3);
       
        return LineExpenseMonthly::where("user_id",$line->operator_id)->whereBetween("pricing_date", [$startDate->format('Y-m-d'),$endDate->format('Y-m-d')])->update(['sync_total' => 1]);
    }
}
