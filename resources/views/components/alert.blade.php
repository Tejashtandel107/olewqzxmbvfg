
<div {{ $attributes->merge(['class' => 'alert alert-' . ($type ?? 'success') . (isset($dismissable) ? ' alert-dismissible' : '') . ' fade show']) }}>
    {!! $message !!}
@if($dismissable)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
@endif
</div>