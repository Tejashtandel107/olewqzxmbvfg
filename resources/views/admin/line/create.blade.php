<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
    @section('plugin-css')
    {{ Html::style('https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css') }}
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
    @endsection
    <x-line-form :line="($line) ?? null" :clients="$clients" :companies="($companies) ?? null" :months="$months" :petty-cash-book-types="$pettyCashBookTypes" :banks="$banks"></x-line-form>
    @section('plugin-scripts')
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/formValidation.min.js')}}"></script>
    <script src="{{asset('/assets/vendor/formvalidation/framework/bootstrap4.min.js')}}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('/assets/vendor/select2/js/select2.min.js')}}"></script>
    @endsection
    @section('page-scripts')
    <script src="{{ asset('/assets/js/line/create.min.js') }}"></script>
    @endsection
</x-app-layout>
