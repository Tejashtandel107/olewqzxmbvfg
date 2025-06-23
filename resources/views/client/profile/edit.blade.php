<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <form method="post" action="{{ route('client.profile.update') }}" id="updateClientProfile" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <div class="card-body">
                    @if (session('status')==='profile-updated')
                        <x-alert message="{{__('Your profile has been updated.')}}" dismissable="true" />
                    @endif
                        <h4>{{__('Profile Information')}}</h4>
                        <div class="mb-3">{{ __("Update your account profile information and email address.") }}</div> 
                        <div class=row>
                            <div class="col-md-4 col-xxl-4">
                                <div class="mb-3 form-group">
                                    {{ Form::label('name', __('Name'), ['class' => 'form-label']); }}
                                    {{ Form::text('name',old('name', $user->name),['class' => 'form-control','placeholder' => __('Name')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                </div>
                            </div>
                            <div class="col-md-4 col-xxl-4">
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
                            </div> <div class="col-md-4 col-xxl-4">
                                <div class="mb-3 form-group">
                                    {{ Form::label('vat_number','Partita IVA', ['class' => 'form-label']); }}
                                    {{ Form::text('vat_number',($user->profile->vat_number) ?? null,['class' => 'form-control','placeholder' => 'Partita IVA']);}}
                                    <x-input-error class="mt-2" :messages="$errors->get('vat_number')" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-xxl-4">
                                <div class="mb-3 form-group">
                                    {{ Form::label('address', __('Address'), ['class' => 'form-label']); }}
                                    {{ Form::text('address', ($user->profile->address) ?? null,['class' => 'form-control','placeholder' => __('Address')])}}
                                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                                </div>
                            </div>
                            <div class="col-md-4 col-xxl-4">
                                <div class="row">
                                    <div class="col-md-6 col-xxl-6">
                                        <div class="mb-3 form-group">
                                            {{ Form::label('city', __('City/Town'), ['class' => 'form-label']); }}
                                            {{ Form::text('city',  ($user->profile->city) ?? null, ['class' => 'form-control','placeholder' => __('Address')])}}
                                            <x-input-error class="mt-2" :messages="$errors->get('city')" />
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-xxl-6">
                                        <div class="mb-3 form-group">
                                            {{ Form::label('province', __('Province'), ['class' => 'form-label']); }}
                                            {{ Form::text('province',($user->profile->province) ?? null,['class' => 'form-control','placeholder' => __('Province')])}}
                                            <x-input-error class="mt-2" :messages="$errors->get('province')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-xxl-4">
                                <div class="row">
                                    <div class="col-md-6 col-xxl-6">
                                        <div class="mb-3 form-group">
                                            {{ Form::label('postal_code', __('Postal Code'), ['class' => 'form-label']); }}
                                            {{ Form::text('postal_code', ($user->profile->postal_code) ?? null,['class' => 'form-control','placeholder' =>__('Postal Code')])}}
                                            <x-input-error class="mt-2" :messages="$errors->get('postal_code')" />
                                        </div>
                                    </div>  
                                    <div class="col-md-6 col-xxl-6">
                                        <div class="mb-3 form-group">
                                            {{ Form::label('country_id',  __('Country'), ['class' => 'form-label']); }}
                                            {{ Form::select('country_id', $country, ($user->profile->country_id) ??  null,['class'=>'form-select','placeholder' => __("Please Select")]); }}
                                            <x-input-error class="mt-2" :messages="$errors->get('country_id')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xxl-4">
                            <div class="mb-3 form-group">
                                {{ Form::label('additional_email',__('Additional Email'), ['class' => 'form-label']); }}
                                {{ Form::text('additional_email', ($user->profile->additional_email) ?? null,['class' => 'form-control','placeholder' => __('Inserisci ogni email separata da una virgola')])}}
                                <span class="font-13 text-muted">Invia copia email della fattura a.</span>
                            </div>
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
                    <div class="card-footer border-top border-light text-left">
                        <x-success-button id="updateProfile">{{__('Save Profile')}}</x-success-button>
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
    <script src="{{asset('/assets/js/client/profile/create.min.js')}}"></script>
@endsection
</x-app-layout>
