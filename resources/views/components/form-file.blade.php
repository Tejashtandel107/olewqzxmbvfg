<div class=" d-flex align-items-center">
    <img src="{{ $photo }}" {{ $attributes->merge(['width'=>'60px','height'=>'60px','class'=>'rounded-circle me-2']) }}  />
    {{ Form::file('photo', $attributes=[])}}
</div>