
<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/daterangepicker/daterangepicker.css') }}
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
@endsection
<div class="card">
    <div class="card-body table-responsive">
        <div id="notify"></div>
        <div class="row justify-content-between mb-3 gap-3">
            <div class="col-sm-auto">
                {{ Form::model($request,array('url' =>route("admin.reports.lines.client"),'method'=>'get','id'=>'form-filter')) }} 
                {{ Form::hidden('from_date', null, array('id' => 'from_date')) }}
                {{ Form::hidden('to_date', null, array('id' => 'to_date')) }}
                <div class="d-flex gap-3 flex-wrap flex-sm-row flex-column">
                    <div class="form-control-width">
                        {{ Form::label('date-range', 'Periodo', ['class' => 'form-label']); }}
                        <div id="date_range" class="form-control">
                            <i class="mdi mdi-calendar"></i>&nbsp;<span id="selected_date_range">{{implode(" - ",array($request->from_date,$request->to_date))}}</span> <i class="mdi mdi-menu-down"></i>
                        </div>
                    </div>
                    <div class="form-control-width">
                        {{ Form::label('client-id', trans_choice('Client|Clients',1), ['class' => 'form-label']); }}
                        {!! Form::select('client_id',$clients, null, ['class' => 'form-select select2','data-toggle'=>'select2','placeholder' => __('Please Select'),'id'=>'client-id']) !!}   
                    </div>
                    <div class="form-control-width">
                        {{ Form::label('company-id', trans_choice('Company|Companies',1), ['class' => 'form-label']); }}
                        {!! Form::select('company_id',$companies, null, ['class' => 'form-select select2','data-toggle'=>'select2','placeholder' => __("Please Select"),'id'=>'company-id']) !!}   
                    </div>
                    <div class="align-self-sm-end">
                        {{ Form::button(__('Search'), array('type' => 'submit','class' => 'btn btn-primary')) }}
                    </div>
                </div>                              
                {{ Form::close() }} 
            </div>
            <div class="col-auto align-self-end">
                <a class="btn btn-secondary text-xl-end" href="{{route('admin.reports.lines.client',['download' => '1','client_id' => $request->client_id,'from_date' => $request->from_date,'to_date' => $request->to_date,'company_id' => $request->company_id])}}"><i class="ri-file-excel-2-fill me-1 align-middle"></i><span class="align-middle">Esportare</span></a>    
            </div>
        </div>
            <h4 class="header-title mb-3">TASKS TOTALE</h4>
        @if (isset($lineTotals) && $lineTotals->count()>0)
            <x-report-lines-client :lineTotals="$lineTotals"></x-report-lines-client>
        @else
            <div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records_report')}}</div>
        @endisset 
    </div>
</div>
        <!-- end card body-->

    <!-- end card -->
@section('plugin-scripts')
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/moment.min.js')}}"></script>   
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/daterangepicker.js')}}"></script>   
    <script src="{{ asset('/assets/vendor/select2/js/select2.min.js')}}"></script>
@endsection

@section('page-scripts')
    <script src="{{ asset('/assets/js/report/line.min.js') }}"></script>
@endsection
</x-app-layout>
