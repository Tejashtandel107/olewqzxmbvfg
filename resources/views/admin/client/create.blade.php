<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}
    {{ Html::style('assets/vendor/daterangepicker/daterangepicker.css') }}
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
        <h4 class="mb-3 bg-light p-2">{{ __('Profile Information') }}</h4>
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
                        {{ Form::label('vat_number','Partita IVA', ['class' => 'form-label']); }}
                        {{ Form::text('vat_number',($client->profile->vat_number) ?? null,['class' => 'form-control','placeholder' => 'Partita IVA']);}}
                    </div>
                </div>
                <div class="col-12"><!-- --></div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('address',  __('Address'), ['class' => 'form-label']); }}
                        {{ Form::text('address', ($client->profile->address) ?? null,['class' => 'form-control','placeholder' =>  __('Address')])}}
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
                                {{ Form::select('country_id', $country, ($client->profile->country_id) ??  null,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                                <x-input-error class="mt-2" :messages="$errors->get('country_id')" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12"><!-- --></div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('account-manager',trans_choice('Primary Account Manager|Primary Account Managers',1), ['class' => 'form-label']); }}
                        {{ Form::select('primary_account_manager_id',$account_managers, ($primary_selected_manager) ?? null ,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('account-manager', trans_choice('Secondary Account Manager|Secondary Account Managers',2), ['class' => 'form-label ']); }}
                        {!! Form::select('secondary_account_manager_id[]', $account_managers, ($secondary_selected_managers) ?? null, ['class' => 'select2 form-control select2-multiple','data-toggle'=>'select2','multiple'=>'','data-placeholder' => __("Please Select")]) !!} 
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label(null,trans_choice('Operator|Operators',2), ['class' => 'form-label']); }}
                        {!! Form::select('operator_id[]', $operators, ($selected_operators) ?? null, ['class' => 'select2 form-control select2-multiple','data-toggle'=>'select2','multiple'=>'','data-placeholder' => __("Please Select")]) !!}   
                    </div>
                </div>
                <div class="col-md-4 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('additional_emails',__('Additional Emails'), ['class' => 'form-label']); }}
                        {{ Form::text('additional_emails', (isset($client->profile)) ? $client->profile->getRawOriginal('additional_emails') : null,['class' => 'form-control','placeholder' => __('Inserisci ogni email separata da una virgola')])}}
                        <span class="font-13 text-muted">Invia copia email della fattura a.</span>
                    </div>
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
            <h5 class="mb-3 bg-light p-2">Impostazioni Prezzo Studio</h5>
            <div class="row align-items-end">
                <div class="col-lg-3 col-md-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('pricing-type','Tipo di Prezzo', ['class' => 'form-label']); }}
                        {{ Form::select('pricing_type',$pricing_types,($client->profile->pricing_type) ?? 'Per Line' ,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                   <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('fixed-price','Prezzo Fisso', ['class' => 'form-label']); }}
                                <div class="input-group input-group-merge">
                                    <div class="input-group-text">
                                        <span class="ri-money-euro-circle-line"></span>
                                    </div>
                                    {{ Form::text('price', ($client->profile->price) ?? 0.00,['class' => 'form-control','placeholder' => __('Fixed Cost')]);}}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('milestone','Pietra Miliare', ['class' => 'form-label']); }}
                                {{ Form::text('milestone', ($client->profile->milestone) ?? 0 ,['class' => 'form-control','placeholder' => 'Pietra Miliare']);}}
                            </div>
                        </div>
                   </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="mb-3 form-group">
                        <label class="form-label">Data di attivazione</label>
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="mdi mdi-calendar"></span>
                            </div>
                            {{ Form::text('activation_date',isset($client->profile->activation_date) ? ($client->profile->activation_date)->format('m/Y') : null,['class' => 'form-control date_range','placeholder' => 'Activation Date']);}}
                        </div>                        
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                   <div class="row align-items-end">
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('price_level', 'Livello dei prezzi', ['class' => 'form-label ']); }}
                                {!! Form::select('price_level_id', $priceLevels , $client->profile->price_level_id ?? null, ['class' => 'form-select','placeholder' =>__("Please Select")]) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 form-group">
                                {{ Form::label('original_price_level', 'Livello del prezzo base', ['class' => 'form-label ']); }}
                                {!! Form::select('base_price_level_id', $priceLevels , $client->profile->base_price_level_id ?? null, ['class' => 'form-select','placeholder' =>__("Please Select")]) !!}
                            </div>
                        </div>
                   </div>
                </div>
                <div class="col-lg-3 col-md-4">
                      <div class="mb-3 form-group">
                        {{ Form::label('price-ordinaria','Prezzo Per Ordinaria', ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_ordinaria', ($client->profile->price_ordinaria) ?? '0.40' ,['class' => 'form-control','placeholder' => __('Cost For Ordinaria')]);}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                     <div class="mb-3 form-group">
                        {{ Form::label('price-semplificata','Prezzo Per Semplificata', ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_semplificata', ($client->profile->price_semplificata) ?? '0.50'  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                     <div class="mb-3 form-group">
                        {{ Form::label('price-corrispettivi','Prezzo Per Corrispettivi(Semplificata)', ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_corrispettivi_semplificata', ($client->profile->price_corrispettivi_semplificata) ?? 10  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <div class="mb-3 form-group">
                       {{ Form::label('price_paghe_semplificata','Prezzo Per Paghe(Semplificata)', ['class' => 'form-label']); }}
                       <div class="input-group input-group-merge">
                           <div class="input-group-text">
                               <span class="ri-money-euro-circle-line"></span>
                           </div>
                           {{ Form::text('price_paghe_semplificata', ($client->profile->price_paghe_semplificata) ?? 3 ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                       </div>
                   </div>
                </div>
            @if(isset($client))
                <div class="col-md-12">
                    <div class="mb-2 form-check">
                        <input class="form-check-input" type="checkbox" value="true" id="apply_price_change" name="apply_price_change">
                        <label class="form-check-label" for="apply_price_change">
                            Vuoi applicare il prezzo più recente alle attività/linee esistenti? In caso affermativo, definire la data di inizio e la data di fine da applicare alla modifica del prezzo.
                        </label>
                    </div>
                    <div class="row" id="date-range" style="display:none;">
                        <div class="col-sm-auto">
                            <div class="mb-3 form-group">
                                {{ Form::label('price-change-start-date', "Data d'inizio", ['class' => 'form-label '])}}
                                {{ Form::text('price_change_start_date',null,['class' => 'form-control','placeholder' => __('Start Date'),'id'=>'start_date'])}}
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-3 form-group">
                                {{ Form::label('price-change-end-date', "Data di fine", ['class' => 'form-label '])}}
                                {{ Form::text('price_change_end_date',null,['class' => 'form-control','placeholder' => __('End Date'),'id'=>'end_date'])}}
                            </div>
                        </div>

                    </div>
                </div>
            @endif
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
        <div class="card-footer border-top border-light">
            <x-success-button class='me-2' id='submitbtn'><i class="mdi mdi-content-save me-1"></i>{{__('Save')}}</x-success-button>
            <a href="{{route('admin.clients.index')}}" class="btn btn-outline-secondary">{{__('Cancel')}}</a>
            <div class="pt-3"><div id="notify"></div></div>
        </div>
        {!! Form::close() !!}
    </div>
@section('plugin-scripts')
    <script src="{{asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.it.min.js')}}"></script>   
    <script src="{{ asset('/assets/vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/moment.min.js')}}"></script>   
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/framework/bootstrap4.min.js')}}"></script>
@endsection
@section('page-scripts')
<script src="{{ asset('/assets/js/admin/client/create.min.js') }}"></script>
@endsection
</x-app-layout>

