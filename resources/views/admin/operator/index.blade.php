<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
<div class="card">
    <div class="card-body table-responsive">
        <div class="row mb-2">
            <div class="col-xl-8">
                {{ Form::model($request,array('url' =>route('admin.operators.index'),'method'=>'get','id'=>'form-filter')) }} 
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
            <div class="col-xl-4 align-self-end">
                <div class="text-xl-end mt-3">
                    <a href="{{ route('admin.operators.create') }}"><x-primary-button><i class="mdi mdi-plus me-1"></i>{{__('Add New')}}</x-primary-button></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
            <div class="mb-3 mt-2"><x-show-entries :records="$operators"/></div>
                <div class="table-responsive">
                    <table class="table table-centered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>Costo Stipendio</th>
                                <th>Costo Bonus<br>per Ordinaria</th>
                                <th>Costo Bonus<br>per Semplificata</th>
                                <th>Costo Bonus<br>per Corrispettivi</th>
                                <th>Costo Bonus<br>per Paghe</th>
                                <th>Bonus Target</th>
                                <th></th>
                                <th>{{__("Action")}}</th>
                            </tr>
                        </thead>
                        <tbody>
                    @if(isset($operators) && $operators->count()>0)
                        @foreach($operators as $operator)
                            <tr>
                                <td>
                                    <div class="table-user d-flex">
                                        <img src="{{ $operator->photo}}" alt="Profile Pic" class="me-2 rounded-circle">
                                        <div>
                                            <div class="fw-semibold">{{ $operator->name }}</div>
                                            <span class="text-muted font-13 pt-1">{{ $operator->email }}</span><br/>
                                            <span class="badge badge-primary-lighten">{{Helper::getOperatorPricingTypeLabel($operator->pricing_type)}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{!!Helper::formatAmount($operator->price) !!}</td>
                                <td>{!!Helper::formatAmount($operator->price_righe_ordinaria,3) !!}</td>
                                <td>{!!Helper::formatAmount($operator->price_righe_semplificata,3) !!}</td>
                                <td>{!!Helper::formatAmount($operator->price_righe_corrispettivi_semplificata,3) !!}</td>
                                <td>{!!Helper::formatAmount($operator->price_righe_paghe_semplificata,3) !!}</td>
                                <td>{{$operator->bonus_target}}</td>
                                <td>
                                    <div>
                                        <span class="text-muted font-13">{{__("Status")}}: </span><x-badge :status="$operator->status"/>
                                    </div>
                                    <div>
                                        <span class="text-muted font-13">{{__("Joined")}}: </span><span class="text-muted font-13 pt-1">{{ $operator->created_at }}</span>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.operators.edit',$operator->user_id) }}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    <a class="action-icon" onclick="OperatorDeleteModal('{{route('api.operators.destroy',$operator->user_id)}}','{{$operator->name}}');" href="javascript:void(0);"><i class="mdi mdi-delete"></i></a>                                
                                </td>
                            </tr>
                        @endforeach
                    @else
                            <tr>
                                <td colspan="8"><div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records')}}</div></td>
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
    <div id="modal-operator-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dark-header-modalLabel" aria-hidden="true">
        <form action="" id="form-operator-delete" method="post">
            @method('DELETE')
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-danger">
                        <h4 class="modal-title">{{__('Delete'). " " .trans_choice('Operator|Operators',1)}}</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-notify"></div>
                        <p>{{__('Are you sure you want to delete the user')}} <b id="modal-operator-name"></b>&nbsp?</p>
                        <input type="checkbox" class="form-check-input" name="delete_all">
                        <label class="form-check-label">{{__('Are you sure you want to delete associated data')}},<br>{{__('personal information, etc')}}&nbsp?<br>{{__('Once its delete you won'.'t be able to recover those data')}}.</label>
                    </div>
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-danger" id="btn-delete" onclick="operatorDelete(event)">{{__('Delete')}}</button>
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>    
</div>
@section('page-scripts')
    <script src="{{ asset('/assets/js/admin/operator/index.min.js') }}"></script>
@endsection
</x-app-layout>
