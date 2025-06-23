<?php 
    $showCostStipendio=false;
    if (Auth::user()->isSuperAdmin()):
        $detailReportURL = route('admin.reports.lines.operator');
        $showCostStipendio = true;
    elseif (Auth::user()->isOperator()):
        $detailReportURL = route('operator.reports.lines');
    endif;        

    $grandTotalBonus = $lineExpensesMonthly->sum('total_bonus');
    $grandTotalSalary = ($showCostStipendio) ? $lineExpensesMonthly->sum('price') : 0;
?>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead class="table-light">
            <tr>
                <th>{{trans_choice("Operator|Operators",1)}}</th>
                <th>Mese</th>
                <th>Ordinaria </th>
                <th>Semplificata </th>
                <th>Corrispettivi<br>(Semplificata)</th>
                <th>Paghe<br>(Semplificata)</th>
            @if($showCostStipendio)
                <th>Costo Stipendio</th>
                <th>Costo Bonus</th>
            @endif
                <th>Totale </th>
            </tr>
        </thead>
        <tbody>
    @forelse ($lineExpensesMonthly as $item)
        <?php
            $salary = ($showCostStipendio) ? $item->price : 0; 
            $detailReportURLQueryParams = [
                'from_date' => Helper::DateFormat($item->pricing_date->startOfMonth(),config('constant.DATE_FORMAT')),
                'to_date' => Helper::DateFormat($item->pricing_date->endOfMonth(),config('constant.DATE_FORMAT')),
                'operator_id' => $item->user_id
            ];
        ?>
            <tr>
                <td>
                    <div class="fw-bold">{{$item->name ?? '-'}}</div>
                    <div class="text-muted font-13 pt-1">{{Helper::getOperatorPricingTypeLabel($item->pricing_type)}}</div>
                </td>
                <td>
                    <i class="uil uil-calender me-1"></i>
                    <a href="{{ $detailReportURL . '?' . http_build_query($detailReportURLQueryParams) }}" target="_self" title="Visualizza Dettagli">{{$item->pricing_date->format("M Y")}}</a>
                </td>
                <td>
                    <div>{!!Helper::formatAmount($item->total_bonus_ordinaria) !!}</div>
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="font-14 fw-normal">{{$item->total_lines_ordinaria}}</div>
                    </div>
                </td>
                <td>
                    <div>{!!Helper::formatAmount($item->total_bonus_semplificata ) !!}</div>
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="font-14 fw-normal">{{$item->total_lines_semplificata}}</div>
                    </div>
                </td>
                <td>
                    <div>{!!Helper::formatAmount( $item->total_bonus_corrispettivi_semplificata) !!}</div>
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="font-14 fw-normal">{{$item->total_corrispettivi_lines_semplificata}}</div>
                    </div>
                </td>
                <td>
                    <div>{!!Helper::formatAmount($item->total_bonus_paghe_semplificata) !!}</div>
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="font-14 fw-normal">{{$item->total_paghe_lines_semplificata}}</div>
                    </div>
                </td>
            @if($showCostStipendio)
                <td>{!!Helper::formatAmount($salary) !!}</td>
                <td>{!!Helper::formatAmount($item->total_bonus) !!}</td>
            @endif
                <td>
                    <div class="{{$className}} fw-bold">{!!Helper::formatAmount($salary + $item->total_bonus) !!}</div>
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="font-14 fw-normal">{{$item->total_lines}}</div>
                    </div>
                </td>
            </tr> 
    @empty
            <tr>
                <td class="text-center" colspan="9">{{__('messages.no_records_report')}}</td>
            </tr>
    @endforelse
        @if (Auth::user()->isSuperAdmin())
            <tr>
                <td class="text-end fw-bold" colspan="8">Stipendio Totale</td>
                <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalSalary) !!}</td>
            </tr>
            <tr>
                <td class="text-end fw-bold" colspan="8">Bonus Totale</td>
                <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalBonus) !!}</td>
            </tr>
            <tr>
                <td class="text-end fw-bold" colspan="8">Totale</td>
                <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalBonus + $grandTotalSalary) !!}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>