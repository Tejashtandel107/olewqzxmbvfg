<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
    <div class="card">
        <div class="card-body table-responsive">
        <div id="notify"></div>
            <div class="row mb-2 justify-content-between">
               <div class="col-auto">
                    {{ Form::model($request,array('url' =>route('admin.clients.index'),'method'=>'get','id'=>'form-filter')) }} 
                    <div class="row gy-3 gx-3 align-items-center">
                        <div class="col-sm-auto">
                            <label for="search" class="form-label">{{__("Search")}}</label>
                            {{ Form::text('search',null,['class' => 'form-control','placeholder' => __('Search')]);}}
                        </div>
                        <div class="col-sm-auto align-self-end">
                            {{ Form::button(__('Search'), array('type' => 'submit','class' => 'btn btn-dark')) }}
                        </div>
                    </div>                            
                    {{ Form::close() }} 
                </div>
                <div class="col-auto align-self-end">
                    <div class="text-xl-end mt-3">
                        <a href="{{ route('admin.clients.create') }}"><x-primary-button><i class="mdi mdi-plus me-1"></i>{{__('Add New')}}</x-primary-button></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="mb-3 mt-2"><x-show-entries :records="$clients"/></div>
                    <div class="table-responsive">
                        <table class="table table-centered table-striped">
                            <thead class="table-light"> 
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>Prezzo Fisso</th>
                                    <th>Pietra Miliare</th>
                                    <th>Prezzo Per<br> Ordinaria</th>
                                    <th>Prezzo Per<br> Semplificata</th>
                                    <th>Prezzo Per<br> Corrispettivi</th>
                                    <th>Prezzo Per<br>Paghe</th>
                                    <th></th>
                                    <th>{{__("Action")}}</th>
                                </tr>
                            </thead>
                            <tbody>
                        @if(isset($clients) && $clients->count()>0)
                            @foreach($clients as $client)
                                <tr>
                                    <td>
                                        <div class="table-user d-flex">
                                            <img src="{{ $client->photo ?? Helper::getProfileImg('') }}" alt="" class="me-2 rounded-circle">
                                            <div>
                                                <div class="fw-semibold">{{ $client->name }}</div>
                                                <span class="text-muted font-13 pt-1">{{ $client->email }}</span><br>
                                                <span class="badge badge-primary-lighten">{{Helper::getStudioPricingTypeLabel($client->pricing_type)}}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{!!Helper::formatAmount($client->price) !!}</td>
                                    <td>{{ $client->milestone }}</td>
                                    <td>{!!Helper::formatAmount($client->price_ordinaria) !!}</td>
                                    <td>{!!Helper::formatAmount($client->price_semplificata) !!}</td>
                                    <td>{!!Helper::formatAmount($client->price_corrispettivi_semplificata) !!}</td>
                                    <td>{!!Helper::formatAmount($client->price_paghe_semplificata) !!}</td>
                                    <td>
                                        <div>
                                            <span class="text-muted font-13">{{__("Status")}}: </span><x-badge :status="$client->status"/>
                                        </div>
                                        <div>
                                            <span class="text-muted font-13">{{__("Joined")}}: </span><span class="text-muted font-13 pt-1">{{ $client->created_at }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.companies.index',['client_id'=>$client->user_id]) }}" class="action-icon"> <i class="mdi mdi-office-building-outline"></i></a>                                
                                        <a href="{{ route('admin.clients.edit',$client->user_id) }}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                                        <a class="action-icon" onclick="deleteClientModal('{{route('api.clients.destroy',$client->user_id)}}','{{ $client->name }}');" href="javascript:;"><i class="mdi mdi-delete"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                                <tr>
                                    <td colspan="9"><div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records')}}</div></td>                                        
                                </tr>
                        @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-5"><x-show-entries :records="$clients"/></div>
                        <div class="col-sm-12 col-md-7"> 
                            <div class="dataTables_paginate paging_simple_numbers">
                                <ul class="pagination pagination-rounded pagination justify-content-end">
                                    {!! $clients->links() !!}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    </div> <!-- end card-body-->
    <div id="modal-client-delete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="dark-header-modalLabel" aria-hidden="true">
        <form action="" id="form-client-delete" method="post">
            @method('DELETE')
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header modal-colored-header bg-danger">
                        <h4 class="modal-title">{{__('Delete'). " " .trans_choice('Client|Clients',1)}}</h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div id="modal-notify"></div>
                        <p>{{__('Are you sure you want to delete the user')}} <b id="modal-client-name"></b>&nbsp?</p>
                        <input type="checkbox" class="form-check-input" name="delete_all">
                        <label class="form-check-label">{{__('Are you sure you want to delete associated data')}},<br>{{__('personal information, etc')}}&nbsp?<br>{{__('Once its delete you won'.'t be able to recover those data')}}.</label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="btn-delete" onclick="clientDelete(event)">{{__('Delete')}}</button>  
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>    
</div>
@section('page-scripts')
    <script src="{{ asset('/assets/js/admin/client/index.min.js') }}"></script>
@endsection
</x-app-layout>
