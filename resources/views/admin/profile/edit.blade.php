<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    @if (session('status')==='profile-updated')
                        <x-alert message="{{__('Your profile has been updated.')}}" dismissable="true" />
                    @endif
                   
                        <!-- profile content-->
                    <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <h4>{{__('Personal Information')}}</h4>
                        <div class="border-bottom border-light mb-3"></div>
                        <h5 class="mb-3 text-uppercase"><i class="mdi mdi-account-circle me-1"></i>{{__('Profile Information')}}</h5>
                        <div class="mb-3">{{ __("Update your account profile information and email address.") }}</div> 
                        <div class=row>
                            <div class="col-md-6 col-xxl-5">
                                <div class="mb-3 form-group">
                                    {{ Form::label('name', __('Name'), ['class' => 'form-label']); }}
                                    {{ Form::text('name',old('name', $user->name),['class' => 'form-control','placeholder' => __('Name')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-5">
                                <div class="mb-3 form-group">
                                    {{ Form::label('email', __('E-mail'), ['class' => 'form-label']); }}
                                    {{ Form::text('email',old('email', $user->email), ['class' => 'form-control','placeholder' => __('E-mail')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div>
                                    <p class="text-sm mt-2 text-gray-800">
                                        {{ __('Your email address is unverified.') }}

                                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                            </div>
                            <div class="col-12">
                                <div class="mb-3 form-group">
                                    {{ Form::label('photo', __('Photo'), ['class' => 'form-label']); }}
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle me-2" src="{{Auth::user()->photo}}" alt="image" width="70" height="70">
                                        {{ Form::file('photo', null ,['class' => 'form-control ms-5']);}}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="col-9">
                                    <a class="btn btn-light" onclick="ResetPasswordModal();" href="javascript:void(0);">{{__('Reset Password')}}</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-3 text-uppercase bg-light p-2"><i class="ri-notification-2-fill me-1"></i>{{__('Email notifications')}}</h5>
                            <!-- notification content-->
                            <div>{{__("Get emails to find out what's going on when you're not online. You can turn these off.")}}</div>
                      
                            <div class="form-check mb-2 form-switch mt-3 form-checkbox-success">  
                                <input {{($user->profile->notify_on_client_create) ? "checked":""}} id="notify_on_client_create" value="notify_on_client_create" name="notifications[]" type="checkbox" class="form-check-input">
                                <label class="form-check-label table-light font-bold" for="notify_on_client_create">{{__('Client create')}}</label>
                                <div>{{__("Send you a notification if the account is created by the users.")}}</div>
                            </div>
                            <div class="form-check mb-2 form-switch mt-3 form-checkbox-success">  
                                <input {{($user->profile->notify_on_client_update) ? "checked":""}} id="notify_on_client_update" value="notify_on_client_update" name="notifications[]" type="checkbox" class="form-check-input">
                                <label class="form-check-label table-light font-bold" for="notify_on_client_update">{{__('Client edit')}}</label>
                                <div>{{__("Send you a notification if the account is edited by the users.")}}</div>
                            </div>
                            <div class="form-check mb-2 form-switch mt-3 form-checkbox-success">  
                                <input {{($user->profile->notify_on_client_delete) ? "checked":""}} id="notify_on_client_delete" value="notify_on_client_delete" name="notifications[]" type="checkbox" class="form-check-input">
                                <label class="form-check-label table-light font-bold" for="notify_on_client_delete">{{__('Client delete')}}</label>
                                <div>{{__("Send you a notification if the account is deleted by the users.")}}</div>
                            </div>
                            <div class="form-check mb-2 form-switch mt-3 form-checkbox-success">  
                                <input {{($user->profile->notify_on_company_create) ? "checked":""}} id="notify_on_company_create" value="notify_on_company_create" name="notifications[]" type="checkbox" class="form-check-input">
                                <label class="form-check-label table-light font-bold" for="notify_on_company_create">{{__('Company create')}}</label>
                                <div>{{__("Send you a notification if the account is created by the users.")}}</div>
                            </div>
                            <div class="form-check mb-2 form-switch mt-3 form-checkbox-success">  
                                <input {{($user->profile->notify_on_company_update) ? "checked":""}} id="notify_on_company_update" value="notify_on_company_update" name="notifications[]" type="checkbox" class="form-check-input">
                                <label class="form-check-label table-light font-bold" for="notify_on_company_update">{{__('Company edit')}}</label>
                                <div>{{__("Send you a notification if the account is edited by the users.")}}</div>
                            </div>
                            <div class="form-check mb-2 form-switch mt-3 form-checkbox-success">  
                                <input {{($user->profile->notify_on_company_delete) ? "checked":""}} id="notify_on_company_delete" value="notify_on_company_delete" name="notifications[]" type="checkbox" class="form-check-input">
                                <label class="form-check-label table-light font-bold" for="notify_on_company_delete">{{__('Company delete')}}</label>
                                <div>{{__("Send you a notification if the account is deleted by the users.")}}</div>
                            </div>
                        </div>
                        <x-success-button class="mt-2">{{__('Save Profile')}}</x-success-button>
                    </form>
                </div> <!-- end card body -->
            </div> <!-- end card -->
        </div> <!-- end col -->
        <div class="col-xl-6">
            <div class="card">
                <form method="post" action="{{ route('admin.profile.update-settings') }}" id="form-setting-update" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="card-body">
                        @if (session('status')==='profile-updated-settings')
                            <x-alert message="{{__('Your profile has been updated.')}}" dismissable="true"/>
                        @endif
                        <h5>Settings</h5>
                        <div class="border-bottom border-light mb-3"></div>
                        <h5 class="mb-3 text-uppercase"><i class="mdi mdi-office-building me-1"></i>Informazioni sull'azienda</h5>
                        <div class="row">
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('company_name', __('Company Name'), ['class' => 'form-label']); }}
                                    {{ Form::text('company_name',($settings['company_name']) ?? null,['class' => 'form-control','placeholder' => __('Company Name')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('company_name')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('company_vat_tax',__('VAT number/Tax ID code'), ['class' => 'form-label']); }}
                                    {{ Form::text('company_vat_tax',($settings['company_vat_tax']) ?? null,['class' => 'form-control','placeholder' => __('VAT number/Tax ID code')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('company_vat_tax')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('company_address', __('Address'), ['class' => 'form-label']); }}
                                    {{ Form::text('company_address',($settings['company_address']) ?? null,['class' => 'form-control','placeholder' => __('Address')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('company_address')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="row">
                                    <div class="col-md-6 col-xxl-6">
                                        <div class="mb-3 form-group">
                                            {{ Form::label('company_city',  __('City/Town'), ['class' => 'form-label']); }}
                                            {{ Form::text('company_city',($settings['company_city']) ?? null, ['class' => 'form-control','placeholder' => __('City/Town')])}}
                                            <x-input-error class="mt-2" :messages="$errors->get('company_city')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xxl-6">
                                        <div class="mb-3 form-group">
                                            {{ Form::label('company_province', __('Province'), ['class' => 'form-label']); }}
                                            {{ Form::text('company_province',($settings['company_province']) ?? null,['class' => 'form-control','placeholder' => __('Province')])}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('company_postal_code',__('Postal Code'), ['class' => 'form-label']); }}
                                    {{ Form::text('company_postal_code',($settings['company_postal_code']) ?? null,['class' => 'form-control','placeholder' => __('Postal Code')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('company_postal_code')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('country_name', __('Country'), ['class' => 'form-label']); }}
                                    {{ Form::select('country_name',$country, ($settings) ??  null ,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                                    <x-input-error class="mt-2" :messages="$errors->get('country_name')" />
                                </div>
                            </div>
                        </div>                       
                        <h5 class="mb-3 text-uppercase bg-light p-2"><i class="mdi mdi-connection me-1"></i>DEVPOS Informazioni</h5>
                        <div class="row">
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('devpos_tenant',  'Tenant', ['class' => 'form-label']); }}
                                    {{ Form::text('devpos_tenant',($settings['devpos_tenant']) ?? null, ['class' => 'form-control','placeholder' => 'Tenant'])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('devpos_tenant')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('devpos_username','Username', ['class' => 'form-label']); }}
                                    {{ Form::text('devpos_username',($settings['devpos_username']) ?? null,['class' => 'form-control','placeholder' => 'Username'])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('devpos_username')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('devpos_password', 'Password', ['class' => 'form-label']); }}
                                    {{ Form::text('devpos_password',($settings['devpos_password']) ?? null, ['class' => 'form-control','placeholder' => 'Password'])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('devpos_password')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('devpos_business_unit_code', 'Business Unit Code', ['class' => 'form-label']); }}
                                    {{ Form::text('devpos_business_unit_code',($settings['devpos_business_unit_code']) ?? null,['class' => 'form-control','placeholder' => 'Business Unit Code'])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('devpos_business_unit_code')" />
                                </div>
                            </div>
                            <div class="col-md-6 col-xxl-6">
                                <div class="mb-3 form-group">
                                    {{ Form::label('devpos_operator_code', 'Operator Code', ['class' => 'form-label']); }}
                                    {{ Form::text('devpos_operator_code',($settings['devpos_operator_code']) ?? null,['class' => 'form-control','placeholder' => 'Operator Code'])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('devpos_operator_code')" />
                                </div>
                            </div>
                        </div> 
                        <x-success-button id="settingUpdate">{{__('Save Settings')}}</x-success-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="modal-reset-password" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dark-header-modalLabel" aria-hidden="true">
        <form method="post" action="{{ route('password.update') }}" id="form-rest-password">
            @csrf
            @method('put')
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-primary">
                        <h4 class="text-uppercase">{{__('Update Password')}}</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">{{ __('Ensure your account is using a long, random password to stay secure.') }}</div>
                        <div id="password-update-notify"></div>
                        <div class="col-12">
                            <div class="mb-3 form-group">
                                {{ Form::label('current_password', __('Current Password'), ['class' => 'form-label']); }}
                                <div class="input-group input-group-merge">
                                    {{ Form::password('current_password',['class' => 'form-control','autocomplete' => "current-password"]); }}
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>  
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 form-group">
                                {{ Form::label('password', __('New Password'), ['class' => 'form-label']); }}
                                <div class="input-group input-group-merge">
                                    {{ Form::password('password',['class' => 'form-control','autocomplete' => "new-password"]); }}
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3 form-group">
                                {{ Form::label('password_confirmation', __('Confirm Password'), ['class' => 'form-label']); }}
                                <div class="input-group input-group-merge">
                                    {{ Form::password('password_confirmation',['class' => 'form-control','autocomplete' => "new-password"]); }}
                                    <div class="input-group-text" data-password="false">
                                        <span class="password-eye"></span>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">  
                        <x-success-button id="submitbtn"><i class="mdi mdi-content-save me-1"></i>{{__('Save')}}</x-success-button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div> 
        </form>
    </div>
@section('plugin-scripts')
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/framework/bootstrap4.min.js')}}"></script>
@endsection
@section('page-scripts')
<script src="{{asset('assets/js/admin/profile/create.min.js')}}"></script>
@endsection
</x-app-layout>
