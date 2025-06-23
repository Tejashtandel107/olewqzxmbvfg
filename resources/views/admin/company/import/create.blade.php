<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('/assets/vendor/formvalidation/formValidation.min.css') }}
@endsection
<div class=card>
        <h4 class="card-header  border-bottom border-light">{{ __('Upload files') }}</h4>
        <div class="card-body">
            <div id="notify"></div>
                <div class=row>
                {!! Form::open(array('files'=>true, 'method' => 'POST','route' => ['admin.imports.store'], 'id' => 'import-company-form','autocomplete' => 'off')) !!}
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                        {!! Form::label('import-file', 'Select File') !!}
                        {!! Form::file('import_file',null, array('class' => 'form-control text-center')) !!}                        
                        </div>   
                        <a href="{{ Storage::url('samples/sample-companies-import.xlsx') }}">{{__('Download sample ms-excel file')}}</a>
                    </div>
                </div>
        </div>
            <div class="card-body">
            <x-success-button class='me-2' id='submitbtn'><i class="mdi mdi-content-save me-1"></i>{{__('Save')}}</x-success-button>    
            <a href="{{route('admin.imports.create')}}" class="btn btn-outline-secondary" data-dismiss="modal">{{__('Cancel')}}</a>
            </div>
        {!! Form::close() !!}
</div>
@section('plugin-scripts')
    <script src="{{asset('/assets/vendor/jquery-form/jquery.form.min.js')}}"></script>
@endsection
@section('page-scripts')
<script src="{{ asset('/assets/js/admin/company/import/create.min.js') }}"></script>
@endsection
</x-app-layout>