<?php 
    $detailReportURL = route('admin.reports.lines.client');
    $grandTotalRevenue = $lineIncomesMonthly->sum('total_bonus') + $lineIncomesMonthly->sum('price');
    $grandTotalCost = $lineIncomesMonthly->sum('total_cost');   
?>
<div class="table-responsive">
    <table class="table table-sm table-striped">
        <thead class="table-light">
            <tr>
                <th style="width: 30px;">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="select-all">
                    </div>                    
                </th>
                <th>Studio</th>
                <th><i class="uil uil-calender me-1"></i>Mese</th>
                <th>Ordinaria</th>
                <th>Semplificata</th>
                <th>Corrispettivi<br>(Semplificata)</th>
                <th>Paghe<br>(Semplificata)</th>
                <th>{{__('Total')}}</th>
                <th>{{__('Status')}}</th>          
                <th>Costo totale<br>approssimativo</th>
            </tr>
        </thead>
        <tbody>
    @forelse ($lineIncomesMonthly as $item)
        <?php  
            $statusClass=$invoiceStatus='';
            $totalAmount = $item->total_amount;
            $detailReportURLQueryParams = [
                'from_date' => Helper::DateFormat($item->pricing_date->startOfMonth(),config('constant.DATE_FORMAT')),
                'to_date' => Helper::DateFormat($item->pricing_date->endOfMonth(),config('constant.DATE_FORMAT')),
                'client_id' => $item->user_id
            ];
            if($item->isInvoiceReady()){
                $statusClass = $item->status_class;
                $invoiceStatus = $item->invoice_status;
            }
        ?>
            <tr>
                <td>
                    <div class="form-check">
                    @if ($item->isInvoiceReady())
                        <input type="checkbox" class="form-check-input" name="ids[]" value="{{$item->id}}">
                    @else
                        <input type="checkbox" class="form-check-input" name="ids[]" value="{{$item->id}}" disabled>
                    @endif
                    </div>                    
                </td>
                <td>
                    <div class="fw-bold">{{$item->name ?? '-'}}</div>
                    <div class="text-muted font-13 pt-1">{{Helper::getStudioPricingTypeLabel($item->pricing_type)}}</div>
            @if ($item->isInvoiceReady())
                @if (Auth::user()->isSuperAdmin())
                    <div class="d-flex flex-wrap text-muted">
                        <a href="{{ route('admin.line-incomes.show', [$item->id]) }}">{{ $item->invoice_number ? 'Vedi Fattura' : 'Vedi Draft'}}</a>
                        &nbsp;|&nbsp;
                        <a href="javascript: openPopCenter('{{ route('admin.line-incomes.statuses.index',$item->id) }}')">Cronologia dello stato</a>
                    </div>
                @endif        
            @endif
                </td>
                <td>
                    <i class="uil uil-calender me-1"></i>
                    <a href="{{ $detailReportURL . '?' . http_build_query($detailReportURLQueryParams) }}" class="text-capitalize" title="Visualizza Dettagli" target="_self">{{$item->pricing_date->translatedFormat("M Y")}}</a>
                </td>
                <td>
                    <div>{!!Helper::formatAmount($item->total_bonus_ordinaria) !!}</div>
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="font-14 fw-normal">{{$item->total_lines_ordinaria}}</div>
                    </div>
                </td>
                <td>
                    <div>{!!Helper::formatAmount($item->total_bonus_semplificata) !!}</div>
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="font-14 fw-normal">{{$item->total_lines_semplificata}}</div>
                    </div>
                </td>
                <td>
                    <div>{!!Helper::formatAmount($item->total_bonus_corrispettivi_semplificata	) !!}</div>  
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
                <td>
                    <div class="{{$className}} fw-bold">{!!Helper::formatAmount($totalAmount) !!}</div>                 
                    <div class="mt-1">
                        <span class="fw-bold font-13">Tasks</span>
                        <div class="fw-bold">{{$item->total_lines}}</div>
                    </div>
                </td>
                <td>
            @if($invoiceStatus!='')
                    <span class="text-capitalize badge badge-{{$statusClass}}-lighten">{{$invoiceStatus}}</span>
                @if(!empty($item->invoice_number))
                    <br><span>{{$item->getRawOriginal('invoice_number')}}</span>
                @endif
            @else
                <span>N/A</span>
            @endif                        
                </td>
                <td>{!!Helper::formatAmount($item->total_cost) !!}</</td>
            </tr> 
    @empty
            <tr>
                <td class="text-center" colspan="10">{{__('messages.no_records_report')}}</td>
            </tr>
    @endforelse
            <!-- end tr -->
            <tr>
                <td class="text-end fw-bold" colspan="9">Entrate totali</td>
                <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalRevenue) !!}</td>
            </tr>
            <tr>
                <td class="text-end fw-bold" colspan="9">Costo totale approssimativo</td>
                <td class="text-end fw-bold">{!!Helper::formatAmount("-" . $grandTotalCost) !!}</td>
            </tr>
            <tr>
                <td class="text-end fw-bold" colspan="9">Profitto totale</td>
                <td class="text-end fw-bold">{!!Helper::formatAmount($grandTotalRevenue - $grandTotalCost) !!}</td>
            </tr>
        </tbody>
    </table>
</div>