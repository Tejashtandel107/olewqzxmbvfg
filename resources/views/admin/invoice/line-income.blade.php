<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
    @section('page-css')
        <link href="{{asset('assets/css/invoice.min.css')}}" rel="stylesheet" type="text/css" id="invoice-css" />  
        <style type="text/css">
            .invoice-main{
                font-size: 1rem;
            }
        </style>
    @endsection
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
    <div class="row">
        <div class="col-xxl-9">
            <div class="card ribbon-box">
                <div class="card-body">
                @if(!empty($lineIncomeMonthly->invoice_number) && !empty($lineIncomeMonthly->invoice_status))
                    <div class="ribbon ribbon-{{$lineIncomeMonthly->statusClass }} text-uppercase float-start">{{$lineIncomeMonthly->invoice_status}}</div>
                @else
                    <div class="ribbon ribbon-secondary float-start text-uppercase">Draft</div>
                @endif 
                    <div class="text-end mb-2">
                        <a href="{{route('admin.line-incomes.show',[$lineIncomeMonthly->id,'download'=>1])}}" class="btn btn-secondary btn-sm">Scarica Fattura</a>
                    </div>
                    <x-line-income-monthly-invoice :lineIncomeMonthly="$lineIncomeMonthly" :showBankFees="1" />    
                </div>
            </div>
        </div>
        <div class="col-xxl-3">
            <div class="row">
                <div class="col-xxl-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route("admin.line-incomes.bulk.update")}}" id="bulk-status-form" onsubmit="return updateStatus()" method="post">
                                @csrf
                                <input type="hidden" name="exchange_rate" id="exchange-rate-input" value="{{ Cache::get('exchange_rate', '') }}">
                                <input type="hidden" value="{{$lineIncomeMonthly->id}}" name="ids[]">
                                <div class="form-group mb-2">
                                    <label class="mb-1">Azione</label>
                                    <select name="status" id="bulk-status" class="form-select" required>
                                        <option value="">Seleziona Azione</option>
                                        <option value="create-devpos-invoice">Crea fattura devPOS</option>
                                        <option value="paid">Contrassegna come pagato</option>
                                        <option value="cancel">Cancellare</option>
                                    </select>
                                </div>
                                <div class="form-group mb-2 total-paid d-none">
                                    <label class="mb-1">Totale pagato</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-text">
                                            <span class="ri-money-euro-circle-line"></span>
                                        </div>
                                        <input type="number" name="total_paid" class="form-control" value="{{$lineIncomeMonthly->total_outstanding}}" min=0 step="any" max="{{$lineIncomeMonthly->total_outstanding}}" required>
                                    </div>
                                </div>
                                <div class="form-group mb-2 bank-fees d-none">
                                    <label class="mb-1">Bank Fees</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-text">
                                            <span class="ri-money-euro-circle-line"></span>
                                        </div>
                                        <input type="number" name="bank_fees" class="form-control" value="0.00" min=0 step="any" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Applica azione</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-12 col-md-6">
                    <div class="card">
                        <div class="d-flex card-header justify-content-between align-items-center">
                            <h4 class="header-title">Attività di stato recente</h4>
                        </div>
                        <div class="card-body py-0 mb-3" data-simplebar style="max-height: 403px;"> 
                    @if(isset($statuses) && $statuses->count()>0)
                            <div class="timeline-alt py-0">
                        @foreach ($statuses as $key =>$status)
                            <div class="fw-bold">{{ \Carbon\Carbon::createFromFormat('Y-m', $key)->format('F Y')}}</div><br>
                            @foreach ($status as $item)
                                <?php
                                    $statusClass = $item->status_class;
                                ?>     
                                <div class="timeline-item ms-2">
                                    <i class="timeline-icon mdi mdi-adjust bg-{{$statusClass}}-lighten text-{{$statusClass}}"></i></span>
                                    <div class="timeline-item-info">
                                        <span class="text-{{$statusClass}} text-capitalize">{{$item->status}}</span><br>
                                        <small>{{$item->notes}}</small>
                                        <p class="mb-0 pb-2">
                                            <small class="text-muted">{{$item->created_at}}</small>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @endforeach
                            </div>  
                    @else
                            <div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records_report')}}</div>                
                        </div>    
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    @section('page-scripts')
    <script src="{{ asset('assets/js/admin/report/income/line-income.min.js') }}"></script>
    @endsection
</x-app-layout>