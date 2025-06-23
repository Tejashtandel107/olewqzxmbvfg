<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('/assets/vendor/formvalidation/formValidation.min.css') }}
@endsection
    <div class=card>
        @if(isset($pricing))
            {{ Form::model($pricing ,['method'=>'PATCH' ,'files'=>true, 'route' => ['api.pricings.update', $pricing->pricing_id],'id'=>'pricing-form']) }}
            @else
            {!! Form::open(['files'=>true, 'method'=>'POST', 'route' => 'api.pricings.store', 'id' =>'pricing-form','autocomplete' => 'off']) !!}
        @endif
        <div class="card-body">
            <h5 class="mb-3 text-uppercase bg-light p-2">{{ __('Basic Information') }}</h5>
            <div class=row>
                <div class="col-md-6 col-lg-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('user_name', trans_choice('User|Users',1), ['class' => 'form-label']); }}
                        {{ Form::text('user_name',($user->name) ?? "N/A",['class' => 'form-control','readonly']);}}
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('month_id', __("Month"), ['class' => 'form-label']); }}
                        {{ Form::select('month_id',$months,null,['class'=>'form-select','placeholder' => __("Please Select"),'disabled']); }}
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="mb-3 form-group">
                        {{ Form::label('year', __('Year'), ['class' => 'form-label']); }}
                        {{ Form::text('year',null, ['class' => 'form-control','readonly']); }}
                    </div>
                </div>
            </div>
            <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Cost Details')}}</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('pricing-type',__('Cost Type'), ['class' => 'form-label']); }}
                        {{ Form::select('pricing_type',$pricing_types,null,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                    </div>
                </div>
                <div class="col-md-auto">
                    <div class="mb-3 form-group">
                        {{ Form::label('fixed-cost',__('Fixed Cost'), ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price', null,['class' => 'form-control','placeholder' => __('Fixed Cost')]);}}
                        </div>
                    </div>
                </div>
                 <div class="col-md-auto">
                      <div class="mb-3 form-group">
                        {{ Form::label('cost-ordinaria',__('Cost For Ordinaria'), ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_ordinaria', null ,['class' => 'form-control','placeholder' => __('Cost For Ordinaria')]);}}
                        </div>
                    </div>
                </div>
                 <div class="col-md-auto">
                     <div class="mb-3 form-group">
                        {{ Form::label('cost-semplificata',__('Cost For Semplificata'), ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_semplificata', null ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                     <div class="mb-3 form-group">
                        {{ Form::label('bonus_target','Bonus Target', ['class' => 'form-label']); }}
                        {{ Form::text('bonus_target', null,['class' => 'form-control','placeholder' => 'Bonus Target']);}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer border-top border-light">
            <x-success-button class='me-2' id='submitbtn'><i class="mdi mdi-content-save me-1"></i>{{__('Save')}}</x-success-button>
            <a href="{{route('admin.operators.pricings.index',$pricing->user_id)}}" class="btn btn-outline-secondary">{{__('Cancel')}}</a>
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
    <script src="{{ asset('/assets/js/admin/pricing/create.min.js') }}"></script>
@endsection
</x-app-layout>



