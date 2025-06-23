<?php
    $grandTotalBonus=0;
    $grandTotalSalary=0;
    $showCostStipendio = false;
    if (Auth::user()->isSuperAdmin()):
        $detailReportURL = route('admin.reports.lines.operator');
        $showCostStipendio = true;
    elseif (Auth::user()->isAccountManager()):
        $detailReportURL = route('account-manager.reports.lines');
    endif;
?>
<div class="table-responsive">
    <table class="table table-sm table-bordered">
    @forelse ($lineExpensesMonthly as $accountManageExpenses)
        <tr>
            <td colspan="14" class="table-light text-center"><b>{{$accountManageExpenses->first()->operator_name}}</b></td>
        </tr>     
        <tr class="fw-bold">
            <td class="table-light" rowspan="2">{{trans_choice("Operator|Operators",1)}}</td>
            <td class="table-light" rowspan="2">Mese</td>
            <td class="table-light text-center" colspan="2">Ordinaria</td>
            <td class="table-light text-center" colspan="2">Semplificata</td>
            <td class="table-light text-center" colspan="2">Corrispettivi<br>(Semplificata)</td>
            <td class="table-light text-center" colspan="2">Paghe<br>(Semplificata)</td>
            <td class="table-light text-center" colspan="4">Totale</td>
        </tr>  
        <tr class="fw-bold">
            <td class="table-light">Tasks</td>
            <td class="table-light">{{__('Cost')}}</td>
            <td class="table-light">Tasks</td>
            <td class="table-light">{{__('Cost')}}</td>
            <td class="table-light">Tasks</td>
            <td class="table-light">{{__('Cost')}}</td>
            <td class="table-light">Tasks</td>
            <td class="table-light">{{__('Cost')}}</td>
            <td class="table-light">Tasks</td>
            <td class="table-light">Costo Bonus</td>
        @if($showCostStipendio)
            <td class="table-light">Costo Stipendio</td>
        @endif
            <td class="table-light">Totale Costo</td>
        </tr>
        <?php
            $totalLinesOrdinaria = $accountManageExpenses->sum('total_lines_ordinaria');
            $totalLinesSemplificata = $accountManageExpenses->sum('total_lines_semplificata');
            $totalCorrispettiviLinesSemplificata = $accountManageExpenses->sum('total_corrispettivi_lines_semplificata');
            $totalPagheLinesSemplificata = $accountManageExpenses->sum('total_paghe_lines_semplificata');
            $totalBonusOrdinaria = $accountManageExpenses->sum('total_bonus_ordinaria');
            $totalBonusSemplificata = $accountManageExpenses->sum('total_bonus_semplificata');
            $totalBonusCorrispettiviSemplificata = $accountManageExpenses->sum('total_bonus_corrispettivi_semplificata');
            $totalBonusPagheSemplificata = $accountManageExpenses->sum('total_bonus_paghe_semplificata');
            $totalLines = $accountManageExpenses->sum('total_lines');
            $totalBonus = $accountManageExpenses->sum('total_bonus');
            $totalSalary = ($showCostStipendio) ? $accountManageExpenses->sum('price') : 0;
            $grandTotalBonus += $totalBonus;
            $grandTotalSalary += $totalSalary;
        ?>
        @foreach ($accountManageExpenses as $expense)
            <?php
                $detailReportURLQueryParams = [
                    'from_date' => Helper::DateFormat($expense->pricing_date->startOfMonth(),config('constant.DATE_FORMAT')),
                    'to_date' => Helper::DateFormat($expense->pricing_date->endOfMonth(),config('constant.DATE_FORMAT')),
                    'operator_id' => ($expense->pricing_type =='Operator Bonus') ? $expense->operator_id : $expense->account_manager_id,
                ];
                $salary = ($showCostStipendio) ? $expense->price : 0;
                $pricingType = ($expense->pricing_type!='Operator Bonus') ? Helper::getOperatorPricingTypeLabel($expense->pricing_type): $expense->pricing_type;
            ?>
            <tr>
                <td>
                    <b>{{($expense->operator_name) ?? '-' }}</b><br>
                    <small class="text-muted">{{__($pricingType)}}</small>
                </td>
                <td>
                    <i class="uil uil-calender me-1"></i>
                    <a href="{{ $detailReportURL .'?'. http_build_query($detailReportURLQueryParams) }}" target="_self" title="Visualizza Dettagli">{{$expense->pricing_date->format("M Y")}}</a>
                </td>
                <td>{{$expense->total_lines_ordinaria}}</td>
                <td>{!!Helper::formatAmount($expense->total_bonus_ordinaria) !!}</td>
                <td>{{$expense->total_lines_semplificata}}</td>
                <td>{!!Helper::formatAmount($expense->total_bonus_semplificata) !!}</td>
                <td>{{$expense->total_corrispettivi_lines_semplificata}}</td>
                <td>{!!Helper::formatAmount($expense->total_bonus_corrispettivi_semplificata) !!}</td>
                <td>{{$expense->total_paghe_lines_semplificata}}</td>
                <td>{!!Helper::formatAmount($expense->total_bonus_paghe_semplificata) !!}</td>
                <td>{{$expense->total_lines}}</td>
                <td>{!! Helper::formatAmount($expense->total_bonus) !!}</td>
            @if($showCostStipendio)
                <td><?php echo ($expense->pricing_type=='Operator Bonus') ? 'N/A' : Helper::formatAmount($salary); ?></td>
            @endif
                <td>{!!Helper::formatAmount($salary + $expense->total_bonus) !!}</td>
            </tr>
        @endforeach
            <tr class="fw-bold table-light">
                <td></td>
                <td>{{__('Total')}}</td>
                <td>{{$totalLinesOrdinaria}}</td>
                <td>{!!Helper::formatAmount($totalBonusOrdinaria) !!}</td>
                <td>{{$totalLinesSemplificata}}</td>
                <td>{!!Helper::formatAmount($totalBonusSemplificata) !!}</td>
                <td>{{$totalCorrispettiviLinesSemplificata}}</td>
                <td>{!!Helper::formatAmount($totalBonusCorrispettiviSemplificata) !!}</td>
                <td>{{$totalPagheLinesSemplificata}}</td>
                <td>{!!Helper::formatAmount($totalBonusPagheSemplificata) !!}</td>
                <td>{{$totalLines}}</td>
                <td>{!!Helper::formatAmount($totalBonus) !!}</td>
            @if($showCostStipendio)
                <td>{!!Helper::formatAmount($totalSalary) !!}</td>
            @endif
                <td class="{{$className}}">{!!Helper::formatAmount($totalSalary + $totalBonus) !!}</td>
            </tr>
        @if(!$loop->last)
            <tr><td class="border-0" colspan="13"><!-- --></td></tr>
        @endif
    @empty
        <tr>
            <td class="text-center" colspan="13">{{__('messages.no_records_report')}}</td>
        </tr>
    @endforelse
    @if (Auth::user()->isSuperAdmin())
        <tr><td class="border-0" colspan="14"><!-- --></td></tr>
        <tr>
            <td class="text-end fw-bold" colspan="13">Stipendio Totale</td>
            <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalSalary) !!}</td>
        </tr>
        <tr>
            <td class="text-end fw-bold" colspan="13">Bonus Totale</td>
            <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalBonus) !!}</td>
        </tr>
        <tr>
            <td class="text-end fw-bold" colspan="13">Totale</td>
            <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalBonus + $grandTotalSalary) !!}</td>
        </tr>
    @endif

    </table>
</div>