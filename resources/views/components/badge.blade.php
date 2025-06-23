@props(['status'])

@if($status=='active')
    {{-- For Active Status --}}
    <span {{ $attributes->merge(['class'=>'badge badge-success-lighten text-capitalize']) }}>{{ __("active") }}</span>
@else
    {{-- For Inactive Status --}}
    <span {{ $attributes->merge(['class'=>'badge badge-danger-lighten text-capitalize']) }}>{{ __("inactive") }}</span>
@endif