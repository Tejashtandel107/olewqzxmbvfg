@section('plugin-css')
<link href="{{asset('assets/vendor/formvalidation/formValidation.min.css')}}" rel="stylesheet">
@endsection
<div class="account-pages pt-2 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">
                    <!-- Logo -->
                    <div class="card-header py-4 text-center bg-dark">
                        <a href="/">
                            <x-application-logo height="45" />
                        </a>
                    </div>
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('plugin-scripts')
<script src="{{asset('assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
<script src="{{asset('assets/vendor/formvalidation/formValidation.min.js')}}"></script>
<script src="{{asset('assets/vendor/formvalidation/framework/bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/js/plugin/jquery.buttonSpinner.min.js')}}"></script>
<script src="{{asset('assets/js/plugin/jquery.notification.min.js')}}"></script>
@endsection