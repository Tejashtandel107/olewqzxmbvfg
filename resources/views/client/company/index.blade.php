<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
    <div class="card">
        <div class="card-body table-responsive">
        <div id="notify"></div>
            <div class="row mb-2 justify-content-between">
                <div class="col-auto">
                {{ Form::model($request,array('url' =>route('client.companies.index'),'method'=>'get','id'=>'form-filter')) }} 
                    <div class="row gy-3 gx-3 align-items-center">
                        <div class="col-sm-auto">
                            {{ Form::label(null, __('Search'), ['class' => 'form-label']); }}
                            {{ Form::text('search',null,['class' => 'form-control','placeholder' => __('Search')]);}}
                        </div>
                        <div class="col-sm-auto">
                            {{ Form::label(null, trans_choice('Company Type|Company Types',1), ['class' => 'form-label']); }}
                            {!! Form::select('company_type',Helper::getCompanyTypes(), null, ['class' => 'form-select','placeholder' =>__("Please Select")]) !!}   
                        </div>
                        <div class="col-sm-auto align-self-end">
                            {{ Form::button(__('Search'), array('type' => 'submit','class' => 'btn btn-dark')) }}
                        </div>
                    </div>                            
                {{ Form::close() }} 
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="mb-3 mt-2">
                        <x-show-entries :records="$companies"/>
                    </div>
                    <div class="table-responsive text-nowrap">
                        <table class="table table-centered table-striped nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>{{__("Company Name")}}</th>                                                                                
                                    <th>{{ trans_choice('Company Type|Company Types',1)}}</th>
                                    <th>{{__("VAT/Tax ID")}}</th>
                                    <th>{{__("Joined")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                        @if(isset($companies) && $companies->count()>0)
                            @foreach($companies as $comapny)
                                <tr>
                                    <td>
                                        <h5 class="my-0">{{$comapny->company_name}}</h5>
                                        <p class="mb-0 txt-muted">{{ $comapny->business_type}}</p>
                                    </td>                                        
                                    <td>{{ $comapny->company_type }}</td>
                                    <td>{{ $comapny->vat_tax}}</td>
                                    <td>{{ $comapny->created_at }}</td>
                                </tr>
                            @endforeach
                        @else
                                <tr>
                                    <td colspan="6"><div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records')}}</div></td>    
                                </tr>
                        @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                            <x-show-entries :records="$companies"/>
                        </div>
                        <div class="col-sm-12 col-md-7"> 
                            <div class="dataTables_paginate paging_simple_numbers">
                                <ul class="pagination pagination-rounded pagination justify-content-end">
                                    {!! $companies->links() !!}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end card-body-->
    <div id="modal-company-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dark-header-modalLabel" aria-hidden="true">
        <form action="" id="form-company-delete" method="post">
            @method('DELETE')
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-danger">
                        <h4 class="modal-title">{{__('Delete'). " " .trans_choice('Company|Companies',1)}}</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-notify"></div>
                        <p>{{__('Are you sure you want to delete the company')}} <b id="modal-company-name"></b>&nbsp?</p>
                        <input type="checkbox" class="form-check-input" name="delete_all">
                        <label class="form-check-label">{{__('Are you sure you want to delete associated data')}},<br>{{__('personal information, etc')}}&nbsp?<br>{{__('Once its delete you won'.'t be able to recover those data')}}.</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-delete" onclick="companyDelete(event)">{{__('Delete')}}</button>  
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>    
</div>
@section('page-scripts')
    <script src="{{ asset('assets/js/client/company/index.min.js') }}"></script>
@endsection
</x-app-layout>

