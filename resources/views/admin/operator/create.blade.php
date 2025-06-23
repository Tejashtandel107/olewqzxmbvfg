<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/daterangepicker/daterangepicker.css') }}
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
    {{ Html::style('/assets/vendor/formvalidation/formValidation.min.css') }}
@endsection
    <div class=card>
        @if(isset($operator))
            {{ Form::model($operator ,['method'=>'PATCH' ,'files'=>true, 'route' => ['api.operators.update', $operator->user_id],'id'=>'operator-form']) }}
        @else
            {!! Form::open(['files'=>true, 'method'=>'POST', 'route' => 'api.operators.store', 'id' =>'operator-form','autocomplete' => 'off']) !!}
        @endif
        <div class="card-body">
            <h5 class="mb-3 bg-light p-2">{{ __('Profile Information') }}</h5>
            <div class=row>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('name', __('Name'), ['class' => 'form-label']); }}
                        {{ Form::text('name',null,['class' => 'form-control','placeholder' => __('Name')]);}}
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('email', __('E-mail'), ['class' => 'form-label']); }}
                        {{ Form::text('email',null, ['class' => 'form-control','placeholder' => __('E-mail')]); }}
                    </div>
                </div>
                <div class="col-12"><!-- --></div>
                <div class="col-md-6 col-xxl-4">
                    <div class="mb-3 form-group">
                        {{ Form::label('account-manager',trans_choice('Account Manager|Account Managers',2), ['class' => 'form-label ']); }}
                        {!! Form::select('account_manager_id[]', $account_managers,$selected_managers ?? '', ['class' => 'select2 form-control select2-multiple','data-toggle'=>'select2','multiple'=>'','data-placeholder' => __("Please Select")]) !!} 
                    </div>
                </div>
                <div class="col-md-6 col-xxl-4" id="clients-list">
                    <div class="mb-3 form-group">
                        {{ Form::label('', trans_choice('Client|Clients',2), ['class' => 'form-label ']); }}
                        {!! Form::select('client_id[]', $clients, ($selected_clients) ?? [], ['class' => 'select2 form-control select2-multiple','data-toggle'=>'select2','multiple'=>'','data-placeholder' => __("Please select")]) !!}  
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3 mb-3 flex-wrap">
                <div class="mb-3 form-group">
                    <x-check-status :status="$operator->status ?? ''"/>
                </div>
                <div class="mb-3 form-group"> 
                    @if(isset($operator))
                        <x-form-file :photo="$operator->photo"/>
                    @else
                        <x-form-file :photo="Helper::getProfileImg('')"/>
                    @endif                            
                </div>
            </div>  
            <h5 class="mb-3 bg-light p-2">Impostazioni Costo Operatori</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('pricing-type','Tipo Costo Operatore', ['class' => 'form-label']); }}
                        {{ Form::select('pricing_type',$pricing_types,($operator->profile->pricing_type) ?? 'Fixed' ,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('fixed-price','Costo Stipendio', ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price',($operator->profile->price) ?? null,['class' => 'form-control','placeholder' => __('Fixed Cost')]);}}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 form-group">
                       {{ Form::label('bonus_target','Bonus Target', ['class' => 'form-label']); }}
                       {{ Form::text('bonus_target', ($operator->profile->bonus_target) ?? 6000,['class' => 'form-control','placeholder' => 'Bonus Target']);}}
                   </div>
               </div>
               <h6>Righe</h6>
               <div class="col-md-3">
                   <div class="mb-3 form-group">
                       {{ Form::label('price-ordinaria','Costo Bonus per Ordinaria', ['class' => 'form-label']); }}
                       <div class="input-group input-group-merge">
                           <div class="input-group-text">
                               <span class="ri-money-euro-circle-line"></span>
                           </div>
                           {{ Form::text('price_righe_ordinaria', ($operator->profile->price_righe_ordinaria) ?? 0.015 ,['class' => 'form-control','placeholder' => __('Cost For Ordinaria')]);}}
                       </div>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="mb-3 form-group">
                       {{ Form::label('price-semplificata','Costo Bonus per Semplificata', ['class' => 'form-label']); }}
                       <div class="input-group input-group-merge">
                           <div class="input-group-text">
                               <span class="ri-money-euro-circle-line"></span>
                           </div>
                           {{ Form::text('price_righe_semplificata', ($operator->profile->price_righe_semplificata) ?? 0.015  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                       </div>
                   </div>
               </div>
               <div class="col-md-3">
                   <div class="mb-3 form-group">
                      {{ Form::label('price_corrispettivi_semplificata','Costo Bonus per Corrispettivi(Semplificata)', ['class' => 'form-label']); }}
                      <div class="input-group input-group-merge">
                          <div class="input-group-text">
                              <span class="ri-money-euro-circle-line"></span>
                          </div>
                          {{ Form::text('price_righe_corrispettivi_semplificata', ($operator->profile->price_righe_corrispettivi_semplificata) ?? 1.5  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                      </div>
                  </div>
              </div>
              <div class="col-md-3">
                   <div class="mb-3 form-group">
                       {{ Form::label('price_paghe_semplificata','Costo Bonus per Paghe(Semplificata)', ['class' => 'form-label']); }}
                       <div class="input-group input-group-merge">
                           <div class="input-group-text">
                               <span class="ri-money-euro-circle-line"></span>
                           </div>
                           {{ Form::text('price_righe_paghe_semplificata', ($operator->profile->price_righe_paghe_semplificata) ?? 0.45  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                       </div>
                   </div>
               </div>
            </div>
            <h5 class="mb-3 bg-light p-2">Impostazioni Costo Account Manager - Compilare solo per operatori a registrazione(esterni)</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('price-ordinaria','Costo Bonus per Ordinaria', ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_righe_am_ordinaria', ($operator->profile->price_righe_am_ordinaria) ?? '0.00' ,['class' => 'form-control','placeholder' => __('Cost For Ordinaria')]);}}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('price-semplificata','Costo Bonus per Semplificata', ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_righe_am_semplificata', ($operator->profile->price_righe_am_semplificata) ?? '0.00'  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3 form-group">
                       {{ Form::label('price_corrispettivi_semplificata','Costo Bonus per Corrispettivi(Semplificata)', ['class' => 'form-label']); }}
                       <div class="input-group input-group-merge">
                           <div class="input-group-text">
                               <span class="ri-money-euro-circle-line"></span>
                           </div>
                           {{ Form::text('price_righe_am_corrispettivi_semplificata', ($operator->profile->price_righe_am_corrispettivi_semplificata) ?? '0.00'  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                       </div>
                   </div>
               </div>
               <div class="col-md-3">
                    <div class="mb-3 form-group">
                        {{ Form::label('price_paghe_semplificata','Costo Bonus per Paghe(Semplificata)', ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="ri-money-euro-circle-line"></span>
                            </div>
                            {{ Form::text('price_righe_am_paghe_semplificata', ($operator->profile->price_righe_am_paghe_semplificata) ?? '0.00'  ,['class' => 'form-control','placeholder' => __('Cost For Semplificata')]);}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(isset($operator))
                <div class="col-md-12">
                    <div class="mb-2 form-check">
                        <input class="form-check-input" type="checkbox" value="true" id="apply_price_change" name="apply_price_change">
                        <label class="form-check-label" for="apply_price_change">
                            Vuoi applicare il costo più recente alle attività/linee esistenti? In caso affermativo, definire la data di inizio e la data di fine da applicare alla modifica del costo.
                        </label>
                    </div>
                    <div class="row" id="date-range" style="display:none;">
                        <div class="col-sm-auto">
                            <div class="mb-3 form-group">
                                {{ Form::label('price-change-start-date', "Data d'inizio", ['class' => 'form-label '])}}
                                {{ Form::text('price_change_start_date',null,['class' => 'form-control','data-provide'=>'datepicker','placeholder' => __('Start Date'),'id'=>'start_date'])}}
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="mb-3 form-group">
                                {{ Form::label('price-change-end-date', "Data di fine", ['class' => 'form-label '])}}
                                {{ Form::text('price_change_end_date',null,['class' => 'form-control','data-provide'=>'datepicker','placeholder' => __('End Date'),'id'=>'end_date'])}}
                            </div>
                        </div>

                    </div>
                </div>
                @endif
            </div>
            <h4 class="mb-3 bg-light p-2">{{__('Account Management')}}</h4>
            <div class="mb-3">
                <div class="col-9">
                    <a class="btn btn-primary" id="generate-password" href="javascript:void(0);">{{ (isset($operator)) ? __('Set New Password') : __('Generate Password') }}</a>
                </div>
            </div>
            <div id="password-section" class="col-md-3" {!! (isset($operator)) ? 'style="display:none"' : "" !!}>
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
            <a href="{{route('admin.operators.index')}}" class="btn btn-outline-secondary">{{__('Cancel')}}</a>
            <div class="pt-3"><div id="notify"></div></div>
        </div>
        {!! Form::close() !!}
    </div>
@section('plugin-scripts')
    <script src="{{ asset('/assets/vendor/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/moment.min.js')}}"></script>   
    <script type="text/javascript" src="{{ asset('/assets/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/framework/bootstrap4.min.js')}}"></script>
@endsection
@section('page-scripts')
<script src="{{ asset('/assets/js/admin/operator/create.min.js?ver=1.1') }}"></script>
@endsection
</x-app-layout>



