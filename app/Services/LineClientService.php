<?php

namespace App\Services;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Exports\ClientLinesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Line;
use Illuminate\Support\Facades\Storage;

class LineClientService
{
    protected $invoiceDir='lines/client/';
    protected $storage;
    public function __construct()
    {
        $this->storage = Storage::disk('local');
    }    

    public function getAllLines(array $filters = []): Collection
    {
        return Line::select('lines.*', 'users.name as client_name', 'company_name', 'line_type')
            ->selectRaw('(select name from users where user_id=lines.operator_id) as operator_name')
            ->leftJoin('users', 'users.user_id', '=', 'lines.client_id')
            ->leftJoin('companies', 'companies.company_id', '=', 'lines.company_id')
            ->filterBy($filters)
            ->orderBy('lines.created_at')
            ->get();
    }

    public function getLineTotalsByClient(array $filters = []): Collection
    {
        return Line::select('users.name as client_name', 'lines.client_id', 'line_type', 'company_name', 'line_pricing_type', 'pricing_date', 'price_ordinaria', 'price_semplificata', 'price_corrispettivi_semplificata', 'price_paghe_semplificata', 'milestone', 'total_lines')
            ->selectRaw('sum(total_passive_lines) as passive_lines')
            ->selectRaw('sum(total_active_lines) as active_lines')
            ->selectRaw('sum(total_corrispetivvi_lines) as corrispettivi_lines')
            ->selectRaw('sum(total_paghe_lines) as paghe_lines')
            ->selectRaw('sum(total_prima_nota_lines) as prima_nota_lines')
            ->leftJoin('companies', 'companies.company_id', '=', 'lines.company_id')
            ->leftJoin('users', 'users.user_id', '=', 'lines.client_id')
            ->leftJoin('line_incomes_monthly', function (JoinClause $join) {
                $join->on('line_incomes_monthly.user_id', '=', 'lines.client_id')
                    ->on(DB::raw('MONTH(pricing_date)'), '=', DB::raw('MONTH(register_date)'))
                    ->on(DB::raw('YEAR(pricing_date)'), '=', DB::raw('YEAR(register_date)'));
            })
            ->filterBy($filters)
            ->groupBy('pricing_date', 'client_id', 'lines.company_id')
            ->orderBy('pricing_date', 'desc')
            ->orderBy('client_name')
            ->orderBy('company_name')
            ->get();
    }

    public function calculateTotals($lines)
    {
        return (object) array_merge((array) $this->calculateLinesTotals($lines), (array) $this->calculateLinesBonus($lines));
    }
    public function calculateLinesTotals($lines)
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
                if ($line->line_type == 'Ordinaria') {
                    $totals->total_lines_ordinaria += $line->passive_lines + $line->active_lines + $line->corrispettivi_lines + $line->paghe_lines + $line->prima_nota_lines;
                } else {
                    $totals->total_lines_semplificata += $line->passive_lines + $line->active_lines;
                    $totals->total_corrispettivi_lines_semplificata += $line->corrispettivi_lines;
                    $totals->total_paghe_lines_semplificata += $line->paghe_lines;
                }
            }
        }
        $totals->total_lines = $totals->total_lines_ordinaria + $totals->total_lines_semplificata + $totals->total_corrispettivi_lines_semplificata + $totals->total_paghe_lines_semplificata;
        return $totals;
    }
    public function calculateLinesBonus($lines)
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
                if ($line->line_type == 'Ordinaria') {
                    $totals->total_bonus_ordinaria += ($line->passive_lines + $line->active_lines + $line->corrispettivi_lines + $line->paghe_lines + $line->prima_nota_lines) * $line->price_ordinaria;
                } else {
                    $totals->total_bonus_semplificata += ($line->passive_lines + $line->active_lines) * $line->price_semplificata;
                    $totals->total_bonus_corrispettivi_semplificata += $line->corrispettivi_lines * $line->price_corrispettivi_semplificata;
                    $totals->total_bonus_paghe_semplificata += $line->paghe_lines * $line->price_paghe_semplificata;
                }
            }
        }
        $totals->total_bonus = $totals->total_bonus_ordinaria + $totals->total_bonus_semplificata + $totals->total_bonus_corrispettivi_semplificata + $totals->total_bonus_paghe_semplificata;

        return $totals;
    }

    public function exportClientLines(array $request = [])
    {
        $clientName = null;
        $fileNameformat = "%s_Report_%s-%s.xlsx";
        if (!empty($request['client_id'])) {
            if ($client = User::find($request['client_id'])) {
                $clientName = $client->name;
            }
        }
        $fileName = sprintf($fileNameformat, ($clientName) ?? 'All', $request['from_date'], $request['to_date']);
        $fileName = str_replace('/', '', $fileName);
        $lineTotals = $this->getLineTotalsByClient($request)->groupBy('client_id');
        if (!empty($request["download"])) {
            return Excel::download(new ClientLinesExport(compact('lineTotals')), $fileName);
        }
        else{
            Excel::store(new ClientLinesExport(compact('lineTotals')), $this->invoiceDir.$fileName,'local',null, [
                'visibility' => 'private',
            ]);
            return $this->storage->path($this->invoiceDir.$fileName); 
        }
    }
}
