
<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/daterangepicker/daterangepicker.css') }}
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
@endsection
    <div class="card">
        <div class="card-body table-responsive">
            <div id="notify"></div>
            <div class="d-flex flex-wrap mb-2 justify-content-between">
                <div>
                    {{ Form::model($request,array('url' =>route('account-manager.lines.index'),'method'=>'get','id'=>'form-filter')) }} 
                    {{ Form::hidden('from_date', null, array('id' => 'from_date')) }}
                    {{ Form::hidden('to_date', null, array('id' => 'to_date')) }}
                    <div class="row gy-3 gx-3 align-items-center">
                        <div class="col-sm-auto">
                            {{ Form::label('date-range', 'Periodo', ['class' => 'form-label']); }}
                            <div id="date_range" class="form-control">
                                <i class="mdi mdi-calendar"></i>&nbsp;<span id="selected_date_range">{{implode(" - ",array($request->from_date,$request->to_date))}}</span> <i class="mdi mdi-menu-down"></i>
                            </div>
                        </div>
                         <div class="col-sm-auto">
                            {{ Form::label('client-id', trans_choice('Client|Clients',1), ['class' => 'form-label']); }}
                            {!! Form::select('client_id',$clients, null, ['class' => 'form-select select2','data-toggle'=>'select2','placeholder' => __("Please Select"),'id'=>"client-id"]) !!}   
                        </div>
                        <div class="col-sm-auto">
                            {{ Form::label('company-id', trans_choice('Company|Companies',1), ['class' => 'form-label']); }}
                            {!! Form::select('company_id',$companies, null, ['class' => 'form-select select2','data-toggle'=>'select2','placeholder' => __("Please Select"),'id'=>'company-id']) !!}   
                        </div>
                        <div class="col-sm-auto align-self-end">
                            {{ Form::button(__('Search'), array('type' => 'submit','class' => 'btn btn-dark')) }}
                        </div>
                    </div>                              
                    {{ Form::close() }} 
                </div>
                <div class="align-self-end">
                    <div class="text-xl-end mt-3">
                        <a href="{{ route('account-manager.lines.create') }}"><x-primary-button><i class="mdi mdi-plus me-1"></i>{{__('Add New')}}</x-primary-button></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <x-lines :lines="$lines"/>
                </div>
            </div>
        </div> <!-- end card-body-->
    </div>
@section('plugin-scripts')
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/moment.min.js')}}"></script>   
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ asset('/assets/vendor/select2/js/select2.min.js')}}"></script>
@endsection
@section('page-scripts')
    <script src="{{ asset('/assets/js/account-manager/line/index.min.js') }}"></script>
@endsection
</x-app-layout>
