
<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
@section('plugin-css')
    {{ Html::style('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}
    {{ Html::style('assets/vendor/select2/css/select2.min.css')}}
@endsection
    <div class="card">
        <div class="card-body">
        @if(session()->has('message'))
            <div class="alert alert-{{ session('alert_type') }}">
                <p>{{ session()->get('message') }}</p>
            @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
            </div>
        @endif 
            <div class="status">
                <a class="{{ request('invoice_status') == '' ? 'link-dark' : '' }}" href="{{ route('admin.line-incomes.index', array_diff_key(request()->query(), array_flip((array)['invoice_status']))); }}">Tutto ({{$totalLineIncomesMonthly->count()}})</a>&nbsp;|&nbsp;
                <a class="{{ request('invoice_status') == 'unpaid' ? 'link-dark' : '' }}" href="{{ route('admin.line-incomes.index', array_merge(request()->query(), ['invoice_status' => 'unpaid'])) }}">Non pagato ({{$totalLineIncomesMonthly->where('invoice_status','=','unpaid')->count()}})</a>&nbsp;|&nbsp;
                <a class="{{ request('invoice_status') == 'overdue' ? 'link-dark' : '' }}" href="{{ route('admin.line-incomes.index', array_merge(request()->query(), ['invoice_status' => 'overdue'])) }}">In ritardo ({{$totalLineIncomesMonthly->where('invoice_status','=','overdue')->count()}})</a>
            </div>
            <div class="d-flex flex-wrap gap-4 align-items-end">
                <div>
                    <form action="{{route("admin.line-incomes.bulk.update")}}" onsubmit="return bulkUpdateStatus()" id="bulk-status-form" method="post">
                        @csrf
                        <div id="hiddenInputsContainer"></div> 
                        <div class="d-flex gap-2 flex-wrap flex-sm-row align-items-end">
                            <div>
                                <label class="mb-1">Azioni collettive</label>
                                <select name="status" id="bulk-status" class="form-select" required>
                                    <option value="">Azione collettiva</option>
                                    <option value="create-devpos-invoice">Crea fattura devPOS</option>
                                </select>
                                <input type="hidden" name="exchange_rate" id="exchange-rate-input" value="{{ Cache::get('exchange_rate', '') }}">
                            </div>
                            <div>
                                <button type="submit" class="btn btn-secondary" id="btn-bulk-update" value="bulk-status">Applica azione</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Bulk Status Update End-->
                <div class="d-flex flex-fill gap-4 flex-wrap justify-content-between align-items-end">
                    {{ Form::model($request,array('url' =>route("admin.line-incomes.index"),'method'=>'get','id'=>'form-filter')) }} 
                        <div class="d-flex gap-3 flex-wrap align-items-end">
                            <div style="width: 280px;">
                                <label class="form-label">Periodo</label>
                                <div class="input-group">
                                    {{ Form::text('from',null,['class' => 'form-control date_range','placeholder' => 'Mese da']);}}
                                    <span class="input-group-text">to</span>
                                    {{ Form::text('to',null,['class' => 'form-control date_range','placeholder' => 'Mese a']);}}
                                </div>                        
                            </div>
                            <div class="form-control-width">
                                {{ Form::label('account-manager-id', trans_choice('Account Manager',1), ['class' => 'form-label']); }}
                                {!! Form::select('account_manager_id',$account_managers, null, ['class' => 'form-select select2','data-toggle'=>'select2','placeholder' => __('Please Select'),'id'=>'account-manager-id']) !!}   
                            </div>
                            <div class="form-control-width">
                                {{ Form::label('user_id', trans_choice('Client|Clients',1), ['class' => 'form-label']); }}
                                {!! Form::select('user_id',$clients, null, ['class' => 'form-select select2','data-toggle'=>'select2','placeholder' => __('Please Select'),'id'=>'user_id']) !!}   
                            </div>
                            <div>
                                {{ Form::button(__('Search'), array('type' => 'submit','class' => 'btn btn-primary')) }}
                            </div>
                        </div>
                    {{ Form::close() }}
                    <!-- Search Filters Form End-->
                    <div>
                        <form action="{{route("admin.line-incomes.download")}}" method="post" onsubmit="return exportExcel()">
                            @csrf
                            <div id="hiddenContainer"></div>
                            <button type="submit" class="btn btn-secondary"><i class="ri-file-excel-2-fill me-1 align-middle"></i>Export Zoho Invoices</button>
                        </form>
                    </div>
                    <!-- Download Button End-->                
                </div>
                <!-- Search Filters End-->
            </div>
        </div>
        <!-- end card body-->
        <x-line-income-monthly className="text-success" :lineIncomesMonthly="$lineIncomesMonthly"></x-line-income-monthly>
    </div>
    <!-- Exchange Rate Modal -->
    <div id="exchangeRateModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header modal-colored-header bg-danger">
                    <h4 class="modal-title">Tasso di cambio</h4>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <p>Sei sicuro di aver aggiornato il tasso di cambio?</p>
                    <div class="form-group">
                        <label for="modal-exchange-rate" class="form-label">Inserisci il tasso di cambio:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupPrepend">Lek</span>
                            </div>
                            <input type="text" id="modal-exchange-rate" class="form-control" value="{{ Cache::get('exchange_rate', '') }}">
                        </div>
                        <span id="errorName" class="text-danger">
                    </div>
                </div>                
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveExchangeRate">Invia</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancellare</button>
                </div>
            </div>
        </div>
    </div>
@section('plugin-scripts')
    <script src="{{asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.it.min.js')}}"></script>   
    <script src="{{ asset('assets/vendor/select2/js/select2.min.js')}}"></script>
@endsection
@section('page-scripts')
    <script src="{{ asset('assets/js/admin/report/income/line-incomes.min.js?ver=1.1') }}"></script>
@endsection
</x-app-layout>
