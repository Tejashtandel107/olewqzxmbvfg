<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
    {{ Html::style('/assets/vendor/formvalidation/formValidation.min.css') }}
@endsection
    <div class=card>
        @if(isset($client))
            {{ Form::model($client ,['method'=>'PATCH' ,'files'=>true, 'route' => ['api.clients.update', $client->user_id],'id'=>'client-form']) }}
        @else
            {!! Form::open(['files'=>true, 'method'=>'POST', 'route' => 'api.clients.store', 'id' =>'client-form','autocomplete' => 'off']) !!}
        @endif
        <div class="card-body">
        @if(isset($client))
            <div class="alert alert-info" role="alert">
                <i class="ri-information-line me-2"></i>Si prega gentilmente di non modificare direttamente il profilo dello studio, poiché ciò potrebbe causare problemi durante la fatturazione. Si prega di consultare l'amministratore per ottenere l'approvazione prima di apportare qualsiasi modifica a un profilo dello studio. Grazie per la vostra collaborazione!
            </div>
        @endif
        <h4 class="mb-3 text-uppercase bg-light p-2">{{ __('Profile Information') }}</h4>
            <div class=row>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('name', __('Name'), ['class' => 'form-label']); }}
                        {{ Form::text('name',null,['class' => 'form-control','placeholder' => __('Name')]);}}
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('email', __('E-mail'), ['class' => 'form-label']); }}
                        {{ Form::text('email',null, ['class' => 'form-control','placeholder' => __('E-mail')]); }}
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label(null,trans_choice('Operator|Operators',2), ['class' => 'form-label']); }}
                        {!! Form::select('operator_id[]', $operators, ($selected_operators) ?? null, ['class' => 'select2 form-control select2-multiple','data-toggle'=>'select2','multiple'=>'','data-placeholder' => __("Please Select")]) !!}   
                    </div>
                </div>
                <div class="col-12"><!-- --></div>

                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('address', __('Address'), ['class' => 'form-label']); }}
                        {{ Form::text('address', ($client->profile->address) ?? null,['class' => 'form-control','placeholder' => __('Address')])}}
                        <x-input-error class="mt-2" :messages="$errors->get('address')" />
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="row">
                        <div class="col-md-6 col-xxl-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('city',  __('City/Town'), ['class' => 'form-label']); }}
                                {{ Form::text('city',  ($client->profile->city) ?? null, ['class' => 'form-control','placeholder' => __('City/Town')])}}
                                <x-input-error class="mt-2" :messages="$errors->get('city')" />
                            </div>
                        </div>
                        <div class="col-md-6 col-xxl-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('province', __('Province'), ['class' => 'form-label']); }}
                                {{ Form::text('province',($client->profile->province) ?? null,['class' => 'form-control','placeholder' => __('Province')])}}
                                <x-input-error class="mt-2" :messages="$errors->get('province')" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="row">
                        <div class="col-md-6 col-xxl-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('postal_code',__('Postal Code'), ['class' => 'form-label']); }}
                                {{ Form::text('postal_code', ($client->profile->postal_code) ?? null,['class' => 'form-control','placeholder' => __('Postal Code')])}}
                                <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                            </div>
                        </div>
                        <div class="col-md-6 col-xxl-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('country_id', __('Country'), ['class' => 'form-label']); }}
                                {{ Form::select('country_id', $country, ($client->profile->country_id) ?? null,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                                <x-input-error class="mt-2" :messages="$errors->get('country_id')" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('additional_email',__('Additional Email'), ['class' => 'form-label']); }}
                        {{ Form::text('additional_email', ($client->profile->additional_email) ?? null,['class' => 'form-control','placeholder' => __('Inserisci ogni email separata da una virgola')])}}
                        <span class="font-13 text-muted">Invia copia email della fattura a.</span>
                    </div>
                </div>  
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('vat_number','Partita IVA', ['class' => 'form-label']); }}
                        {{ Form::text('vat_number',($client->profile->vat_number) ?? null,['class' => 'form-control','placeholder' => 'Partita IVA']);}}
                    </div>
                </div>
                <div class="d-flex gap-3 mb-3 flex-wrap">
                    <div class="form-group">
                        <x-check-status :status="$client->status ?? ''"/>
                    </div>
                    <div class="form-group"> 
                        @if(isset($client))
                            <x-form-file :photo="$client->photo"/>
                        @else
                            <x-form-file :photo="Helper::getProfileImg('')"/>
                        @endif                            
                    </div>
                </div>
                <h4 class="mb-3 bg-light p-2">{{__('Account Management')}}</h4>
                <div class="mb-3">
                    <div class="col-9">
                        <a class="btn btn-primary" id="generate-password" href="javascript:void(0);">{{ (isset($client)) ? __('Set New Password') : __('Generate Password') }}</a>
                    </div>
                </div>
                <div id="password-section" class="col-md-3" {!! (isset($client)) ? 'style="display:none"' : "" !!}>
                    {{ Form::label('password',__('Password'), ['class' => 'form-label']); }}
                    <div class="col-5 input-group input-group-merge">
                        {{ Form::text('password',"",['class' => 'form-control','placeholder' => __('Password')]);}}
                        <div class="input-group-text" data-password="false">
                            <span class="password-eye"></span>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
        <div class="card-footer border-top border-light">
            <x-success-button class='me-2' id='submitbtn'><i class="mdi mdi-content-save me-1"></i>{{__('Save')}}</x-success-button>
            <a href="{{route('account-manager.clients.index')}}" class="btn btn-outline-secondary">{{__('Cancel')}}</a>
            <div class="pt-3"><div id="notify"></div></div>
        </div>
        {!! Form::close() !!}
    </div>
@section('plugin-scripts')
    <script src="{{ asset('/assets/vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/framework/bootstrap4.min.js')}}"></script>
@endsection
@section('page-scripts')
<script src="{{ asset('/assets/js/account-manager/client/create.min.js') }}"></script>
@endsection
</x-app-layout>



