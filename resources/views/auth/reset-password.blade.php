<x-app-starter-layout>
    <x-slot:title>{{__('Forgot Password')}}</x-slot>
    <x-auth>
        <div class="text-center w-75 m-auto">
            <h4 class="text-dark-50 text-center mt-0 fw-bold">{{__('Reset Password')}}</h4>
            <p class="text-muted mb-4">{{__('Enter your new password and confirm password to reset.')}}</p>
        </div>
        <div id="notify"></div>
        <form method="POST" action="{{ route('password.store') }}" id="password-reset">
            @csrf
            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="mb-3 form-group">
                <label for="emailaddress" class="form-label">{{__('Email address')}}</label>
                <input class="form-control" type="email" name="email" id="emailaddress" autofocus value="{{old('email', $request->email)}}" required="" placeholder="Enter your email">
            </div>
    
            <div class="mb-3 form-group">
                <label for="password" class="form-label">{{__('New Password')}}</label>
                <div class="input-group input-group-merge">
                    <input class="form-control" type="password" name="password" id="password" required="" placeholder="{{__('Enter your password')}}">
                    <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                    </div>
                </div>
            </div>
            <div class="mb-3 form-group">
                <label for="password_confirmation" class="form-label">{{__('Confirm New Password')}}</label>
                <div class="input-group input-group-merge">
                    <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required="" placeholder="{{__('Enter confirm password')}}">
                    <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                    </div>
                </div>
            </div>
            <div class="mb-0 text-center">
                <button class="btn btn-primary" type="submit" id="submitbtn">{{__('Reset Password')}}</button>
            </div>
        </form>
    </x-auth>
@section('page-scripts')
<script src="{{asset('assets/js/auth/reset-password.min.js')}}"></script>
@endsection
</x-app-starter-layout>