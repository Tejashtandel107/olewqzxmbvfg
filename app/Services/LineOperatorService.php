<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;
use App\Models\Line;

class LineOperatorService
{
    public function getAllLines(array $filters = []): Collection
    {
        return Line::select('lines.*', 'users.name as client_name', 'line_type')
                    ->selectRaw('(select name from users where user_id=lines.operator_id) as operator_name')
                    ->leftJoin('users', 'users.user_id', '=', 'lines.client_id')
                    ->leftJoin('companies', 'companies.company_id', '=', 'lines.company_id')
                    ->orderBy('lines.register_date')
                    ->orderBy('lines.created_at')
                    ->filterBy($filters)
                    ->get();
    }

    public function getPaginatedLines(array $filters=[], int $perPage = 15): LengthAwarePaginator
    {
        return Line::select('lines.*','client.name as client_name','operator.role_id as operator_role_id','operator.name as operator_name','updator.name as updator_name','updator.role_id as updator_role_id','companies.company_name','companies.company_type')
                    ->leftJoin('companies', 'companies.company_id', '=', 'lines.company_id')
                    ->leftJoin('users as client', 'client.user_id', '=', 'lines.client_id')
                    ->leftJoin('users as operator', 'operator.user_id', '=', 'lines.operator_id')
                    ->leftJoin('users as updator', 'updator.user_id', '=', 'lines.updated_by')
                    ->filterBy($filters)
                    ->orderBy('lines.created_at', 'desc')
                    ->paginate($perPage)
                    ->withQueryString();
    }

    public function getLineTotalsByUser(array $filters = []): Collection
    {
        return Line::select([
                        'users.name as operator_name',
                        'lines.client_id',
                        'lines.operator_id',
                        'lines.account_manager_id',
                        'line_expenses_monthly.pricing_type',
                        'line_expenses_monthly.price',
                        'line_expenses_monthly.pricing_date',
                        'line_expenses_monthly.bonus_target',
                        'line_expenses_monthly.price_righe_ordinaria',
                        'line_expenses_monthly.price_righe_semplificata',
                        'line_expenses_monthly.price_righe_corrispettivi_semplificata',
                        'line_expenses_monthly.price_righe_paghe_semplificata',
                        'line_expenses_monthly.price_righe_am_ordinaria',
                        'line_expenses_monthly.price_righe_am_semplificata',
                        'line_expenses_monthly.price_righe_am_corrispettivi_semplificata',
                        'line_expenses_monthly.price_righe_am_paghe_semplificata'
                    ])
                    ->selectRaw('(select name from users where user_id=lines.client_id) as client_name')
                    ->selectRaw('(select name from users where user_id=lines.account_manager_id) as account_manager_name')
                    ->selectRaw('SUM(IF(line_type=?,IFNULL(purchase_invoice_lines,0) + IFNULL(sales_invoice_lines,0) + IFNULL(payment_register_lines,0) + IFNULL(petty_cash_book_lines,0),0)) as total_righe_lines_ordinaria', ["Ordinaria"])
                    ->selectRaw('SUM(IF(line_type!=?,IFNULL(purchase_invoice_lines,0) + IFNULL(sales_invoice_lines,0),0)) as total_righe_lines_semplificata', ["Ordinaria"])
                    ->selectRaw('SUM(IF(line_type!=? AND payment_register_type!=?,IFNULL(payment_register_lines,0),0)) as total_righe_corrispettivi_lines_semplificata', ["Ordinaria","Paghe"])
                    ->selectRaw('SUM(IF(line_type!=? AND payment_register_type=?,IFNULL(payment_register_lines,0),0)) as total_righe_paghe_lines_semplificata', ["Ordinaria", "Paghe"])
                    ->leftJoin('users', 'users.user_id', '=', 'lines.operator_id')
                    ->leftJoin('line_expenses_monthly', function (JoinClause $join) {
                        $join->on('line_expenses_monthly.user_id', '=', 'lines.operator_id')
                            ->on(DB::raw('MONTH(pricing_date)'), '=', DB::raw('MONTH(register_date)'))
                            ->on(DB::raw('YEAR(pricing_date)'), '=', DB::raw('YEAR(register_date)'));
                    })
                    ->filterBy($filters)
                    ->groupBy('pricing_date', 'lines.account_manager_id', 'lines.operator_id', 'lines.client_id')
                    ->orderBy('account_manager_name')
                    ->orderBy('operator_name')
                    ->orderBy('client_name')
                    ->get();
    }

    public function calculateTotals($lines,$context)
    {
        return (object) array_merge((array) $this->calculateLinesTotals($lines,$context), (array) $this->calculateLinesBonus($lines,$context));
    }

    public function calculateLinesTotals($lines,$context)
    {
        if ($lines && $lines instanceof Line) {
            $lines = new Collection([$lines]);
        }
        $totals = new \stdClass;
        $totals->total_lines_ordinaria = 0;
        $totals->total_lines_semplificata = 0;
        $totals->total_corrispettivi_lines_semplificata = 0;
        $totals->total_paghe_lines_semplificata = 0;

        if ($lines instanceof Collection) {
            foreach ($lines as $line) {
                $totals->total_lines_ordinaria += $line->total_righe_lines_ordinaria;
                $totals->total_lines_semplificata += $line->total_righe_lines_semplificata;
                $totals->total_corrispettivi_lines_semplificata += $line->total_righe_corrispettivi_lines_semplificata;
                $totals->total_paghe_lines_semplificata += $line->total_righe_paghe_lines_semplificata;
            }
        }
        $totals->total_lines = $totals->total_lines_ordinaria + $totals->total_lines_semplificata + $totals->total_corrispettivi_lines_semplificata + $totals->total_paghe_lines_semplificata;
        return $totals;
    }
    public function calculateLinesBonus($lines,$context)
    {
        if ($lines && $lines instanceof Line) {
            $lines = new Collection([$lines]);
        }
        $totals = new \stdClass;
        $totals->total_bonus_ordinaria = 0;
        $totals->total_bonus_semplificata = 0;
        $totals->total_bonus_corrispettivi_semplificata = 0;
        $totals->total_bonus_paghe_semplificata = 0;

        if ($lines instanceof Collection) {
            foreach ($lines as $line) {
                if($context=='account-manager' && $line->pricing_type=='per_righe_and_per_registrazioni'){
                    $price_righe_ordinaria = $line->price_righe_am_ordinaria;
                    $price_righe_semplificata = $line->price_righe_am_semplificata;
                    $price_righe_corrispettivi_semplificata = $line->price_righe_am_corrispettivi_semplificata;
                    $price_righe_paghe_semplificata = $line->price_righe_am_paghe_semplificata;
                }
                else{
                    $price_righe_ordinaria = $line->price_righe_ordinaria;
                    $price_righe_semplificata = $line->price_righe_semplificata;
                    $price_righe_corrispettivi_semplificata = $line->price_righe_corrispettivi_semplificata;
                    $price_righe_paghe_semplificata = $line->price_righe_paghe_semplificata;
                }
                $totals->total_bonus_ordinaria += $line->total_righe_lines_ordinaria * $price_righe_ordinaria;
                $totals->total_bonus_semplificata += $line->total_righe_lines_semplificata * $price_righe_semplificata;
                $totals->total_bonus_corrispettivi_semplificata += $line->total_righe_corrispettivi_lines_semplificata * $price_righe_corrispettivi_semplificata;
                $totals->total_bonus_paghe_semplificata += $line->total_righe_paghe_lines_semplificata * $price_righe_paghe_semplificata;
            }
        }
        $totals->total_bonus = $totals->total_bonus_ordinaria + $totals->total_bonus_semplificata + $totals->total_bonus_corrispettivi_semplificata + $totals->total_bonus_paghe_semplificata;

        return $totals;
    }
}
