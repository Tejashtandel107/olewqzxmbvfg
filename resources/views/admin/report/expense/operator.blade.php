
<?php
    $selected_user_id = $request->input('user_id');
?>
<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
@endsection
    <div class="card">
        <div class="card-body">
            <div>
                {{ Form::model($request,array('url' =>route("admin.line-expenses.operators"),'method'=>'get','id'=>'form-filter')) }} 
                <div class="d-flex mb-3 gap-3 flex-wrap flex-sm-row flex-column">
                    <div class="form-control-width">
                        <label class="form-label">Periodo</label>
                        <div class="input-group">
                            {{ Form::text('from',null,['class' => 'form-control date_range','placeholder' => 'Mese da']);}}
                            <span class="input-group-text">to</span>
                            {{ Form::text('to',null,['class' => 'form-control date_range','placeholder' => 'Mese a']);}}
                        </div>                        
                    </div>
                    <div class="form-control-width">
                        <label class="form-label">{{trans_choice("Operator|Operators",1)}}</label>
                        <select name="user_id" class="form-select">
                            <option value="">{{__("Please Select")}}</option>
                        @foreach ($operators as $key=>$value)
                            <option {{($key==$selected_user_id) ? 'selected':''}} value="{{$key}}">{{$value}}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="align-self-sm-end">
                        {{ Form::button(__('Search'), array('type' => 'submit','class' => 'btn btn-primary')) }}
                    </div>
                </div> 
                {{ Form::close() }}
            </div>
            <x-line-expense-operator-monthly className="text-danger" :lineExpensesMonthly="$lineExpensesMonthly"></x-line-expense-operator-monthly>
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


