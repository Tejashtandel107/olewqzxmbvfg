<?php

namespace App\Services;

use App\Models\Line;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Services\LineClientService;
use App\Models\LineIncomeMonthly;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LineIncomeMonthlyService
{
    protected $invoiceDir='invoices';
    protected $storage;
    public function __construct()
    {
        $this->storage = Storage::disk('local');
    }    
    public function getAll(array $filters = []): Collection
    {
        $latestStatus = DB::table('line_income_status_histories')
                ->select('line_income_monthly_id', DB::raw('MAX(id) as max_id'))
                ->groupBy('line_income_monthly_id');
              
        return LineIncomeMonthly::select('line_incomes_monthly.*', 'users.name','history.status')
            ->leftJoin('users', 'users.user_id', '=', 'line_incomes_monthly.user_id')
            ->leftJoinSub($latestStatus, 'latest_status', function ($join) {
                $join->on('line_incomes_monthly.id', '=', 'latest_status.line_income_monthly_id');
            })
            ->leftJoin('line_income_status_histories as history', 'history.id', '=', 'latest_status.max_id') 
            ->orderBy('pricing_date', 'desc')
            ->orderBy('users.name', 'asc')
            ->filterBy($filters)
            ->get();
    }

    public function updateTotal(LineIncomeMonthly $lineIncomeMonthly = null)
    {
        $filters = [
            'from_date' => $lineIncomeMonthly->pricing_date->startOfMonth()->format(config('constant.DATE_FORMAT')),
            'to_date' => $lineIncomeMonthly->pricing_date->endOfMonth()->format(config('constant.DATE_FORMAT')),
            "client_id" => $lineIncomeMonthly->user_id
        ];

        $lineClientService = new LineClientService;
        $results =  $lineClientService->getLineTotalsByClient($filters);
        $totals = $lineClientService->calculateTotals($results);

        $lineIncomeMonthly->total_lines_ordinaria = $totals->total_lines_ordinaria;
        $lineIncomeMonthly->total_lines_semplificata = $totals->total_lines_semplificata;
        $lineIncomeMonthly->total_corrispettivi_lines_semplificata = $totals->total_corrispettivi_lines_semplificata;
        $lineIncomeMonthly->total_paghe_lines_semplificata = $totals->total_paghe_lines_semplificata;
        $lineIncomeMonthly->total_lines = $totals->total_lines;

        $lineIncomeMonthly->total_bonus_ordinaria = 0;
        $lineIncomeMonthly->total_bonus_semplificata = 0;
        $lineIncomeMonthly->total_bonus_corrispettivi_semplificata = 0;
        $lineIncomeMonthly->total_bonus_paghe_semplificata = 0;
        $lineIncomeMonthly->total_bonus = 0;

        if (in_array($lineIncomeMonthly->pricing_type, ['per_righe', 'per_registrazioni']) or ($lineIncomeMonthly->pricing_type == 'fixed_price_with_milestone' && $totals->total_lines > $lineIncomeMonthly->milestone)) {
            $lineIncomeMonthly->total_bonus_ordinaria = $totals->total_bonus_ordinaria;
            $lineIncomeMonthly->total_bonus_semplificata = $totals->total_bonus_semplificata;
            $lineIncomeMonthly->total_bonus_corrispettivi_semplificata = $totals->total_bonus_corrispettivi_semplificata;
            $lineIncomeMonthly->total_bonus_paghe_semplificata = $totals->total_bonus_paghe_semplificata;
            $lineIncomeMonthly->total_bonus = $totals->total_bonus;
        }
        $lineIncomeMonthly->sync_total = 0;
        $lineIncomeMonthly->save();
    }

    public function syncStaus(Line $line): bool
    {
        $endDate = now()->setDay(01);
        $startDate = now()->setDay(01)->subMonths(3);
      
        return LineIncomeMonthly::where("user_id", $line->client_id)->whereBetween("pricing_date", [$startDate->format('Y-m-d'),$endDate->format('Y-m-d')])->update(['sync_total' => true]);
    }
    public function exportInvoice(LineIncomeMonthly $lineIncomeMonthly,array $request)
    {
        $fileNameformat = "Invoice-%s.pdf";
        $fileName = sprintf($fileNameformat, ($lineIncomeMonthly->invoice_number) ?: 'Draft');

        $pdf = Pdf::loadView('invoice.line-income',['lineIncomeMonthly'=>$lineIncomeMonthly]); 
        $pdf->setOption(['isRemoteEnabled' => true,'dpi' => 150]);
        if (!empty($request["download"])) {
            return $pdf->download($fileName);
        }
        else{
            $filePath = $this->invoiceDir . '/' . $fileName;
            $this->storage->createDirectory($this->invoiceDir);
            $pdf->save($this->storage->path($filePath));
            if ($this->storage->exists($filePath)) {
                return $this->storage->path($filePath);
            }            
            return false;
        }
    }
}
