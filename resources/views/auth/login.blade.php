<x-app-starter-layout>
    <x-slot:title>{{__('Log In')}}</x-slot>
    <x-auth>
        <div class="text-center w-75 m-auto">
            <h4 class="text-dark-50 text-center pb-0 fw-bold">{{__('Log In')}}</h4>
            <p class="text-muted mb-4">{{__('Enter your email address and password to access the system.')}}</p>
        </div>
        <div id="notify"></div>
        <form id="login-form" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 form-group">
                <label for="emailaddress" class="form-label">{{__('Email address')}}</label>
                <input class="form-control" name="email" type="email" id="emailaddress" autofocus placeholder="{{__('Enter your email')}}">
            </div>
            <div class="mb-3 form-group">
                <a href="{{ route('password.request') }}" class="text-muted float-end" tabindex="2"><small>{{__('Forgot your password?')}}</small></a>
                <label for="password" class="form-label">{{__('Password')}}</label>
                <div class="input-group input-group-merge" tabindex='2'>
                    <input name="password" type="password" id="password" class="form-control" placeholder="{{__('Enter your password')}}">
                    <div class="input-group-text" data-password="false">
                        <span class="password-eye"></span>
                    </div>
                </div>
            </div>
            <div class="mb-3 mb-0 text-center">
                <button class="btn btn-primary" id="submitbtn"> {{__('Log In')}} </button>
            </div>
        </form>
    </x-auth>
@section('page-scripts')
<script src="{{asset('assets/js/auth/login.min.js')}}"></script>
@endsection
</x-app-starter-layout>