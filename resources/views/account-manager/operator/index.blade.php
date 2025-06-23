<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
<div class="card">
    <div class="card-body table-responsive">
        <div class="row mb-2">
            <div class="col-xl-8">
                {{ Form::model($request,array('url' =>route('account-manager.operators.index'),'method'=>'get','id'=>'form-filter')) }} 
                <div class="row gy-3 gx-3 align-items-center">
                    <div class="col-sm-auto">
                        {{ Form::label('search', __('Search'), ['class' => 'form-label']); }}
                        {{ Form::text('search',null,['class' => 'form-control','placeholder' => __('Search')]);}}
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
            <div class="mb-3 mt-2"><x-show-entries :records="$operators"/></div>
                <div class="table-responsive text-nowrap">
                    <table class="table table-centered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__("E-mail")}}</th>
                                <th>{{__("Status")}}</th>
                                <th>{{__("Joined")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                    @if(isset($operators) && $operators->count()>0)
                        @foreach($operators as $operator)
                            <tr>
                                <td class="table-user d-flex align-items-center">
                                    <img src="{{ $operator->photo}}" alt="Profile Pic" class="me-2 rounded-circle">
                                    <div class="fw-semibold">{{ $operator->name }}</div>
                                </td>
                                <td>{{ $operator->email }}</td>
                                <td><x-badge :status="$operator->status"/></td>
                                <td>{{ $operator->created_at }}</td>
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
            </div>               
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="dataTables_info" role="status" aria-live="polite"><x-show-entries :records="$operators"/></div>
                    </div>
                    <div class="col-sm-12 col-md-7"> 
                        <div class="dataTables_paginate paging_simple_numbers">
                            <ul class="pagination pagination-rounded pagination justify-content-end">
                                {!! $operators->links() !!}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div>
</x-app-layout>
