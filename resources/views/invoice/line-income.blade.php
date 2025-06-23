<x-canvas-layout>
    @section('page-css')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300..800&display=swap" rel="stylesheet">        
        <link href="{{asset('assets/css/invoice.min.css')}}" rel="stylesheet" type="text/css" id="invoice-css" />    
    @endsection
    <x-line-income-monthly-invoice :lineIncomeMonthly="$lineIncomeMonthly"/>
</x-canvas-layout>