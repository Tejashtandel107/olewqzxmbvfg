<table class="table table-sm table-bordered text-nowrap overflow-scroll d-inline-block" style="max-height: 75vh">
    <thead class="table-light table-bordered position-sticky top-0">
        <tr class="text-center">
            <th rowspan="2">{{trans_choice("Operator|Operators",1)}}</th>
            <th rowspan="2">Data</th>
            <th rowspan="2">{{trans_choice('Client|Clients',1)}}</th>
            <th rowspan="2">Ragione Sociale</th>
            <th rowspan="2">O/S/P/F</th>
            <th colspan="4">Fatture Acquisti</th>
            <th colspan="4">Fatture Vendite</th>
            <th colspan="5">Corrispettivi/Paghe</th>
            <th colspan="4">Prima Nota</th>
            <th colspan="2">Overtime</th>
        </tr>
        <tr>
            <th>{{__('From Protocol Number')}}</th>
            <th>{{__('To Protocol Number')}}</th>
            <th>{{__('Purchase Invoices Number')}}</th>
            <th>{{__('Passive Lines')}}</th>
            <th>{{__('From Protocol Number')}}</th>
            <th>{{__('To Protocol Number')}}</th>
            <th>{{__('Sales Invoices Number')}}</th>
            <th>{{__('Passive Lines')}}</th>
            <th>Corrispettivi/Paghe</th>
            <th>{{__('Month')}}</th>
            <th>{{__('Days')}}</th>
            <th>{{__('Number of Daily Records')}}</th>
            <th>Righe/Paghe</th>
            <th>{{__('Bank/Cash')}}</th>
            <th>{{__('Month')}}</th>
            <th>{{__('Petty Cash Book Lines')}}</th>
            <th>{{__('Registrations')}}</th>
            <th>Ore Extra</th>
            <th>{{__('Overtime Notes')}}</th>
        </tr>
    </thead>
    @foreach($lines as $line) 
        <tr>
            <td>{{ $line->operator_name }}</td>                                    
            <td>{{ $line->register_date->format(config('constant.DATE_FORMAT')) }}</td>
            <td>{{ ($line->client_name) ?? "N/A" }}</td>
            <td>{{ ($line->company_name) ?? "N/A" }}</td>
            <td>{{ ($line->line_type) ?? "N/A"}}</td>
            <td>{{ $line->purchase_invoice_from }}</td>
            <td>{{ $line->purchase_invoice_to }}</td>
            <td>{{ $line->purchase_invoice_registrations }}</td>
            <td>{{ $line->purchase_invoice_lines }}</td> 
            <td>{{ $line->sales_invoice_from }}</td>
            <td>{{ $line->sales_invoice_to }}</td>
            <td>{{ $line->sales_invoice_registrations }}</td>
            <td>{{ $line->sales_invoice_lines }}</td>
            <td>{{ $line->payment_register_type }}</td>
            <td>
        @php
            $data=[];
            foreach($line->payment_register_month_id as $value){
                $data[]= $months[$value];
            }
            echo implode(',<br>', $data);
        @endphp
            </td>
            <td>{{ $line->payment_register_day }}</td>
            <td>{{ $line->payment_register_daily_records }}</td>
            <td>{{ $line->payment_register_lines }}</td>
            <td>{{ $line->petty_cash_book_type }}</td>
            <td>
        @php
            $data=[];
            foreach($line->petty_cash_book_month_id as $value){
                $data[]= $months[$value];
            }
            echo implode(',<br>', $data);
        @endphp
            </td>
            <td>{{ $line->petty_cash_book_lines }}</td>
            <td>{{ $line->petty_cash_book_registrations}}</td>
            <td>{{ $line->overtime_extra }}</td>
            <td>{{ $line->overtime_note }}</td>
        </tr>  
    @endforeach     
</table>