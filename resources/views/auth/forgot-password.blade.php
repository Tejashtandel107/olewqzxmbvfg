<x-app-starter-layout>
    <x-slot:title>{{__('Forgot Password')}}</x-slot>
    <x-auth>
        <div class="text-center m-auto">
            <h4 class="text-dark-50 text-center mt-0 fw-bold">{{__('Reset Password')}}</h4>
            <p class="text-muted mb-4">{{__('Enter your email address and we`ll send you an email with instructions to reset your password.')}}</p>
        </div>
        <div id="notify"></div>
        <form method="POST" id="password-email" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3 form-group">
                <label for="emailaddress" class="form-label">{{__('Email address')}}</label>
                <input class="form-control" type="email" name="email" id="emailaddress" required="" autofocus placeholder="{{__('Enter your email')}}">
            </div>
            <div class="mb-0 text-center">
                <button class="btn btn-primary" type="submit" id="submitbtn">{{ __('Email Password Reset Link') }}</button>
            </div>
        </form>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <p class="text-muted">{{__('Back to')}} <a href="{{ route('login') }}" class="text-muted ms-1"><b>{{__('Log In')}}</b></a></p>
            </div><!-- end col -->
        </div><!-- end row -->
    </x-auth>
@section('page-scripts')
<script src="{{asset('assets/js/auth/forgot-password.min.js')}}"></script>
@endsection
</x-app-starter-layout>