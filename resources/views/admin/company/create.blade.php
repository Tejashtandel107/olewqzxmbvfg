<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
    @section('plugin-css')
    {{ Html::style('/assets/vendor/formvalidation/formValidation.min.css') }}
    @endsection
    <div class=card>
        <h4 class="card-header  border-bottom border-light">{{ __('Company Information') }}</h4>
        @if(isset($company))
        {{ Form::model($company ,['method'=>'PATCH' ,'files'=>true, 'route' => ['api.companies.update',$company->company_id],'id'=>'company-form']) }}
        @else
        {!! Form::open(['files'=>true, 'method'=>'POST', 'route' => 'api.companies.store', 'id' =>'company-form','autocomplete' => 'off']) !!}
        @endif
        <div class="card-body">
            <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Basic Details')}}</h5>
            <div class=row>
                <div class="col-md-4">
                    <div class="mb-3 form-group">
                        {{ Form::label(null, __('Company Name'), ['class' => 'form-label']); }}
                        {{ Form::text('company_name',null,['class' => 'form-control','placeholder' => __('Company Name')]);}}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 form-group">
                        {{ Form::label(null, trans_choice('Company Type|Company Types',1), ['class' => 'form-label ']); }}
                        {{ Form::select('company_type',$company_types,null,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 form-group">
                        {{ Form::label(null,trans_choice('Client|Clients',1), ['class' => 'form-label']); }}
                        {!! Form::select('user_id', $clients, null, ['class' => 'form-select','placeholder' =>__("Please Select")]) !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 form-group">
                        {{ Form::label(null, __('VAT number/Tax ID code'), ['class' => 'form-label']); }}
                        {{ Form::text('vat_tax',null,['class' => 'form-control','placeholder' => __('VAT number/Tax ID code')]);}}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3 form-group">
                        {{ Form::label(null, __('Business Type'), ['class' => 'form-label ']); }}
                        {{ Form::select('business_type',$business_types,null,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer border-top border-light">
            <x-success-button class='me-2' id='submitbtn'><i class="mdi mdi-content-save me-1"></i>{{__('Save')}}</x-success-button>
            <a href="{{route('admin.companies.index')}}" class="btn btn-outline-secondary">{{__('Cancel')}}</a>
            <div class="pt-3"><div id="notify"></div></div>
        </div>
        {!! Form::close() !!}
    </div>
    @section('plugin-scripts')
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/framework/bootstrap4.min.js')}}"></script>
    @endsection
    @section('page-scripts')
    <script src="{{ asset('/assets/js/admin/company/create.min.js') }}"></script>
    @endsection
</x-app-layout>