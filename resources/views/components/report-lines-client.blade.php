
<?php
    $table_light_color="#abccf4";
?>
<div class="table-responsive">
    <table class="table table-sm table-bordered" border="0">
@foreach($lineTotals as $clientLines)
    <?php
        $companyTypes = collect([
            'ordinaria'=>$clientLines->where("line_type","Ordinaria"), 
            'semplificata'=>$clientLines->where("line_type","!=","Ordinaria")
        ]);
    ?>        <tr>
            <td colspan="8" class="table-light text-center" align="center" bgcolor="{{$table_light_color}}"><b>{{$clientLines->first()->client_name}}</b></td>
        </tr>
    @foreach($companyTypes as $companyType=>$lines)
        <tr>
            <td colspan="8" align="center" class="text-center table-light"><b>{{ucwords($companyType)}}</b></td>
        </tr>
        <tr>
            <td class="table-light"><b>{{trans_choice("Company|Companies",1)}}</b></td>
            <td class="table-light"><b>{{__('Month')}}</b></td>
            <td class="table-light"><b>Passive</b></td>
            <td class="table-light"><b>{{__('Active')}}</b></td>
            <td class="table-light"><b>Corrispettivi/Paghe</b></td>
            <td class="table-light"><b>Prima Nota</b></td>
            <td class="table-light"><b>Totale Tasks</b></td>
            <td class="table-light text-end" align="right"><b>Totale Importo</b></td>
        </tr>
        <?php
            $totalAmount=0;
            $totalPassiveLines = $lines->sum('passive_lines');
            $totalActiveLines = $lines->sum('active_lines');
            $totalCorrispettiviLines = $lines->sum('corrispettivi_lines');
            $totalPagheLines = $lines->sum('paghe_lines');
            $totalPrimaNotaLines = $lines->sum('prima_nota_lines');
        ?>
        @foreach($lines as $line)
        <?php
            $pricingDate = \Carbon\Carbon::parse($line->pricing_date);
            $totals = LineClientService::calculateTotals($line);
            $subTotal = 0;
            if($line->line_pricing_type == 'per_registrazioni' or $line->line_pricing_type == 'per_righe'){
                $totalAmount += $totals->total_bonus;
                $subTotal = $totals->total_bonus;
            }
        ?>
        <tr>
            <td class="fw-semibold">{{$line->company_name}}</td>
            <td>{{$pricingDate->format("M Y")}}</td>
            <td>{{$line->passive_lines}}</td>
            <td>{{$line->active_lines}}</td>
            <td>{{$line->corrispettivi_lines + $line->paghe_lines}}</td>
            <td>{{($companyType == "ordinaria") ? $line->prima_nota_lines : "-"}}</td>
            <td>{{$totals->total_lines}}</td>
            <td align="right" class="text-end">{!! ($subTotal>0) ? Helper::formatAmount($subTotal) : 'N/A' !!}</td>
        </tr>
        @endforeach
        <tr class="">
            <td></td>
            <td><b>{{__('Total')}}</b></td>
            <td>{{$totalPassiveLines}}</td>
            <td>{{$totalActiveLines}}</td>
            <td>{{$totalCorrispettiviLines + $totalPagheLines}}</td>
            <td>{{($companyType == "ordinaria") ? $totalPrimaNotaLines : "-"}}</td>
            <td>{{$totalPassiveLines + $totalActiveLines + $totalCorrispettiviLines + $totalPagheLines + $totalPrimaNotaLines}}</td>
            <td align="right">{!! Helper::formatAmount($totalAmount) !!}</td>
        </tr>
    @endforeach
    @if(!$loop->last)
        <tr><td class="border-0" colspan="8"><!-- --></td></tr>
    @endif
@endforeach        
    </table>
</div>