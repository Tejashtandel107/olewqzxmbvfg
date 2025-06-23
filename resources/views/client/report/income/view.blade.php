
<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}
@endsection
    <div class="card">
        <div class="card-body">
            <div>
                {{ Form::model($request,array('url' =>route("client.line-incomes.index"),'method'=>'get','id'=>'form-filter')) }} 
                <div class="d-flex mb-3 gap-3 flex-wrap flex-sm-row flex-column">
                    <div class="form-control-width">
                        <label class="form-label">Periodo</label>
                        <div class="input-group">
                            {{ Form::text('from',null,['class' => 'form-control date_range','placeholder' => 'Mese da']);}}
                            <span class="input-group-text">to</span>
                            {{ Form::text('to',null,['class' => 'form-control date_range','placeholder' => 'Mese a']);}}
                        </div>                        
                    </div>
                    <div class="align-self-sm-end">
                        {{ Form::button(__('Search'), array('type' => 'submit','class' => 'btn btn-primary')) }}
                    </div>
                </div> 
                {{ Form::close() }}
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Studio</th>
                            <th><i class="uil uil-calender me-1"></i> Mese</th>
                            <th>Ordinaria</th>
                            <th>Semplificata</th>
                            <th>Corrispettivi<br>(Semplificata)</th>
                            <th>Paghe<br>(Semplificata)</th>
                            <th>{{__('Total')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                @forelse ($lineIncomesMonthly as $item)
                    <?php  
                        $detailReportURL = route('client.reports.lines');
                        $totalAmount = $item->total_amount;
                        $detailReportURLQueryParams = [
                            'from_date' => Helper::DateFormat($item->pricing_date->startOfMonth(),config('constant.DATE_FORMAT')),
                            'to_date' => Helper::DateFormat($item->pricing_date->endOfMonth(),config('constant.DATE_FORMAT')),
                            'client_id' => $item->user_id
                        ];
                    ?>
                        <tr>
                            <td>
                                <div class="fw-bold">{{$item->name ?? '-'}}</div>
                                <div class="text-muted font-13 pt-1">{{Helper::getStudioPricingTypeLabel($item->pricing_type)}}</div>
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
                                <div class="text-danger fw-bold">{!!Helper::formatAmount($totalAmount) !!}</div>                 
                                <div class="mt-1">
                                    <span class="fw-bold font-13">Tasks</span>
                                    <div class="fw-bold">{{$item->total_lines}}</div>
                                </div>
                            </td>
                        </tr> 
                @empty
                        <tr>
                            <td class="text-center" colspan="10">{{__('messages.no_records_report')}}</td>
                        </tr>
                @endforelse
                        <!-- end tr -->
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end card body-->
    </div>  
@section('plugin-scripts')
    <script src="{{asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.it.min.js')}}"></script>   
@endsection
@section('page-scripts')
    <script src="{{ asset('assets/js/report/line-income.min.js') }}"></script>
@endsection
</x-app-layout>
