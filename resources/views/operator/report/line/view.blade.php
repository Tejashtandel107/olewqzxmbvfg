
<?php
    $selected_operator_id = $request->input('operator_id');
?>
<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/daterangepicker/daterangepicker.css') }}
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
@endsection
    <div class="card">
        <div class="card-body">
            <div>
                {{ Form::model($request,array('url' =>route("operator.reports.lines"),'method'=>'get','id'=>'form-filter')) }} 
                {{ Form::hidden('from_date', null, array('id' => 'from_date')) }}
                {{ Form::hidden('to_date', null, array('id' => 'to_date')) }}
                <div class="d-flex mb-3 gap-3 flex-wrap flex-sm-row flex-column">
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
        </div>
        <!-- end card body-->
    </div>  
    <!-- end card -->
    <div class=card>
        <div class="card-body">
            <h4 class="header-title mb-3">TASKS TOTALE</h4>
        @if (isset($lineTotals) && $lineTotals->count()>0)
            <x-report-lines-operator :lineTotals="$lineTotals"></x-report-lines-operator>
        @else
            <div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records_report')}}</div>
        @endisset 
        </div>
        <!-- end card body-->
    </div>
    <!-- end card -->
    <div class=card>
        <div class="card-body">
            <h4 class="header-title mb-3">TASKS</h4>
        @if (isset($lines) && $lines->count()>0)
            <div class="table-responsive mb-3">
                <x-report-table :lines="$lines" :months="$months"></x-report-table>
            </div>
        @else
            <div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records_report')}}</div>
        @endisset
        </div>
        <!-- end card body-->
    </div>
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

