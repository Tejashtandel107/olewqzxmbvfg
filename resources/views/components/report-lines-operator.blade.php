<div class="table-responsive">
    <table class="table table-sm table-bordered">
@foreach($lineTotals as $operatorLines)    
        <tr>
            <td colspan="7" class="table-light text-center"><b>{{$operatorLines->first()->operator_name}}</b></td>
        </tr>
        <tr>
            <th class="table-light">{{trans_choice("Client|Clients",1)}}</th>
            <td class="table-light"><b>{{__('Month')}}</b></td>
            <th class="table-light">Ordinaria</th>
            <th class="table-light">Semplificata</th>
            <th class="table-light">Corrispettivi (Semplificata)</th>
            <th class="table-light">Paghe (Semplificata)</th>
            <th class="table-light">Totale Tasks</th>
        </tr>                
    @foreach($operatorLines as $line)
        <?php
            $totals = LineOperatorService::calculateLinesTotals($line,'operator');
            $pricingDate = \Carbon\Carbon::parse($line->pricing_date);
        ?>
        <tr>
            <td class="fw-semibold">{{$line->client_name}}</td>
            <td>{{$pricingDate->format("M Y")}}</td>
            <td>{{$totals->total_lines_ordinaria}}</td>
            <td>{{$totals->total_lines_semplificata}}</td>
            <td>{{$totals->total_corrispettivi_lines_semplificata}}</td>
            <td>{{$totals->total_paghe_lines_semplificata}}</td>
            <td>{{$totals->total_lines}}</td>
        </tr>
    @endforeach
    <?php
        $totals = LineOperatorService::calculateLinesTotals($operatorLines,'operator');
    ?>
        <tr>
            <td></td>
            <td class="fw-bold table-light">{{__('Total')}}</td>
            <td class="fw-bold table-light">{{$totals->total_lines_ordinaria}}</td>
            <td class="fw-bold table-light">{{$totals->total_lines_semplificata}}</td>
            <td class="fw-bold table-light">{{$totals->total_corrispettivi_lines_semplificata}}</td>
            <td class="fw-bold table-light">{{$totals->total_paghe_lines_semplificata}}</td>
            <td class="fw-bold table-light">{{$totals->total_lines}}</td>
        </tr>
    @if(!$loop->last)
        <tr><td class="border-0" colspan="7"><!-- --></td></tr>
    @endif
@endforeach
    </table>
</div>
