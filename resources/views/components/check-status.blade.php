    @props(['status'])
    <div>
        <label class="form-label">{{__('Status')}}</label>
    </div>
    <div>
        <div class="form-check form-check-inline">
            <input {{ $attributes->merge(['type'=>'radio','class'=>'form-check-input','name'=>'status'])}} id="is_active" value='active' {{ $status=='active' ? 'checked' : "checked" }} >
            <label class="form-check-label" for="is_active">{{__('Active') }}</label>
        </div>
        <div class="form-check form-check-inline">
            <input {{ $attributes->merge(['type'=>'radio','class'=>'form-check-input','name'=>'status'])}} id="is_inactive" value='inactive' {{ $status=='inactive' ? 'checked' : "" }} >
            <label class="form-check-label" for="is_inactive">{{__('In Active')}}</label>
        </div>
    </div>

    