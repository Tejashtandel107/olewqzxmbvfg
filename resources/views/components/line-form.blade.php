<?php 
    if (Auth::user()->isSuperAdmin())
        $linesListingLink = route('admin.lines.index');
    elseif (Auth::user()->isAccountManager())
        $linesListingLink = route('account-manager.lines.index');
    elseif (Auth::user()->isOperator())
        $linesListingLink = route('operator.lines.index');

    $selectedCompany = ($line->company_id) ?? null;
?>
<div class=card>
    <div class="card-body">
    @if(isset($line))
        {{ Form::model($line ,['method'=>'PATCH' ,'files'=>true, 'route' => ['api.lines.update',$line->line_id],'id'=>'account-form']) }}
    @else
        {!! Form::open(['files'=>true, 'method'=>'POST', 'route' => 'api.lines.store', 'id' =>'account-form','autocomplete' => 'off']) !!}
    @endif
        <div class=row>
            <div class="col-lg-6">
                <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Basic Details')}}</h5>
                <div class="row">
                    <div class="col-lg-4 mb-3 form-group">
                        {{ Form::label('regiterDate', __('Date'), ['class' => 'form-label']); }}
                        <div class="input-group input-group-merge">
                            <div class="input-group-text">
                                <span class="mdi mdi-calendar"></span>
                            </div>
                        {{ Form::text('register_date',(isset($line)) ? $line->register_date->format(config('constant.DATE_FORMAT')) : null,['class' => 'form-control','placeholder' => __('Date'),'id'=>'register_date','tabindex'=>'1'])}}
                        </div>
                    </div>
                    <div class="col-lg-4 mb-3 form-group">
                        {{ Form::label('client-id', trans_choice('Client|Clients',1), ['class' => 'form-label '])}}
                        {!! Form::select('client_id', $clients , null ,['class' => 'form-select','placeholder' =>__("Please Select"),'id'=>"client-id",'tabindex'=>'2']) !!}
                    </div>
                    <div class="col-lg-4 mb-3 form-group">
                        <label for="company-id" class="form-label">{{ trans_choice("Company|Companies",1) }}</label>
                        <select id="company-id" name="company_id" tabindex="3" class="form-select">
                            <option value="" selected>{{ __("Please Select") }}</option>
                    @if(isset($companies))
                        @foreach($companies as $company)
                            <option value="{{ $company->company_id }}" {{$isCompanySelected($company) ? 'selected' : ''}}>{{ $company->company_name }}&nbsp;({{ $company->company_type }})</option>
                        @endforeach
                    @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Tasks Time')}}</h5>
                <div class="row">
                    <div Class="col-lg-4 col-lg-auto mb-3 form-group">
                        {{ Form::label('job-start-time', __('Job Start Time'), ['class' => 'form-label '])}}
                        {{ Form::text('time_spent_start_time',null,['class' => 'form-control','data-provide'=>'datepicker','placeholder' => __('Job Start Time'),'id'=>'job_start_time','data-enable-time'=>'true','data-date-format' => 'd/mm/yyyy','tabindex'=>'4'])}}
                    </div>
                    <div Class="col-lg-4 col-lg-auto mb-3 form-group">
                        {{ Form::label('job-end-time', __('Job End Time'), ['class' => 'form-label ']); }}
                        {{ Form::text('time_spent_end_time',null,['class' => 'form-control','data-provide'=>'datepicker','placeholder' => __('Job End Time'),'id'=>'job_end_time','data-enable-time'=>'true','data-date-format' => 'd/mm/yyyy','tabindex'=>'5'])}}
                    </div>
                    <div class="col-lg-4 col-lg-auto mb-3 mb-xxl-0 form-group">
                        {{ Form::label('extra-time', __('Overtime/Extra Time Spent'), ['class' => 'form-label']); }}
                        {{ Form::text('overtime_extra',null,['class' => 'form-control','placeholder' =>  __('Overtime/Extra Time Spent'),'tabindex'=>'6'])}}
                        <span class="font-13 text-muted">{{__('Please enter value in hours')}}</span>
                    </div>
                    <div class="col-lg-12 col-lg-auto mb-3 form-group">
                        {{ Form::label('over-time-note', __('Overtime Notes'), ['class' => 'form-label']); }}
                        {{ Form::textarea('overtime_note',null,['class' => 'form-control','placeholder' => __('Overtime Notes'),'rows'=>'2','tabindex'=>'7'])}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Purchase Details')}}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('purchase-invoice-from', __('From Protocol Number'), ['class' => 'form-label'])}}
                            {{ Form::text('purchase_invoice_from',null,['class' => 'form-control','placeholder' => __('From Protocol Number'),'id'=>'purchase_invoice_from','tabindex'=>'8'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('purchase-invoice-to', __('To Protocol Number'), ['class' => 'form-label'])}}
                            {{ Form::text('purchase_invoice_to',null,['class' => 'form-control','placeholder' => __('To Protocol Number'),'id'=>'purchase_invoice_to','tabindex'=>'9'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('purchase-invoice-lines',"Righe/Fatture per le semplificate", ['class' => 'form-label'])}}
                            {{ Form::text('purchase_invoice_lines',null,['class' => 'form-control','placeholder' => "Righe/Fatture per le semplificate",'tabindex'=>'10'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('purchase-invoice-registrations', __('Registrations'), ['class' => 'form-label'])}}
                            {{ Form::text('purchase_invoice_registrations',null,['class' => 'form-control','readonly' => 'true','placeholder' => __('Registrations'),'id'=>'purchase_invoice_registrations','tabindex'=>'11'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Sales Details')}}</h5>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('sales-invoice-from', __('From Number'), ['class' => 'form-label'])}}
                            {{ Form::text('sales_invoice_from',null,['class' => 'form-control','placeholder' => __('From Number'),'id'=>'sales_invoice_from','tabindex'=>'12'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('sales-invoice-to', __('To Number'), ['class' => 'form-label'])}}
                            {{ Form::text('sales_invoice_to',null,['class' => 'form-control','placeholder' => __('To Number'),'id'=>'sales_invoice_to','tabindex'=>'13'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('sales-invoice-lines',"Righe/Fatture per le semplificate", ['class' => 'form-label']) }}
                            {{ Form::text('sales_invoice_lines',null,['class' => 'form-control','placeholder' => "Righe/Fatture per le semplificate",'tabindex'=>'14'])}}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="mb-3 form-group">
                            {{ Form::label('sales-invoice-registrations', __('Registrations'), ['class' => 'form-label'])}}
                            {{ Form::text('sales_invoice_registrations',null,['class' => 'form-control','placeholder' => __('Registrations'),'id'=>'sales_invoice_registrations','tabindex'=>'15'])}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Payments Register Paghe')}}</h5>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3 form-group">
                            {{ Form::label('payment_register_type', __('Payments Register Paghe'), ['class' => 'form-label ']); }}
                            {!! Form::select('payment_register_type',["Corrispettivi" => "Corrispettivi","Paghe" => "Paghe"], null , ['class' => 'form-select','tabindex'=>'16']) !!}
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="mb-3 form-group">
                            {{ Form::label('payment-register-lines',"Righe/Paghe/Numero aliquote per le semplificate", ['class' => 'form-label']) }}
                            {{ Form::text('payment_register_lines',null,['class' => 'form-control','placeholder' => "Righe/Paghe/Numero aliquote per le semplificate",'tabindex'=>'17'])}}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3  form-group">
                            {{ Form::label('payment-register-month', __('Month'), ['class' => 'form-label']); }}
                            {!! Form::select('payment_register_month_id[]', $months, null, ['class' => 'select2 form-control select2-multiple','data-toggle'=>'select2','multiple'=>'','data-placeholder' => __("Please Select"),'tabindex'=>'18']) !!} 
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="mb-3 form-group">
                            {{ Form::label('payment-register-day', __('Days'), ['class' => 'form-label ']); }}
                            {{ Form::text('payment_register_day',null,['class' => 'form-control','placeholder' => __('Days'),'tabindex'=>'19'])}}
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="mb-3 form-group">
                            {{ Form::label('payment-register-daily-records', __('Number of Daily Records'), ['class' => 'form-label'])}}
                            {{ Form::text('payment_register_daily_records',null,['class' => 'form-control','placeholder' => __('Number of Daily Records'),'tabindex'=>'20']);}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-sm-12">
                        <h5 class="mb-3 text-uppercase bg-light p-2">{{__('Petty Cash Book')}}</h5>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3 form-group">
                            {{ Form::label('petty_cash_book_type', __('Bank/Cash'), ['class' => 'form-label ']); }}
                            {!! Form::select('petty_cash_book_type', $pettyCashBookTypes , null , ['class' => 'form-select','placeholder' =>__("Please Select"),'tabindex'=>'21']) !!}
                        </div>
                    </div>
                    <div class="col-lg-4 petty-cash-bank-id" {!! (isset($line) && $line->petty_cash_book_type === 'Banca') ? '' : 'style="display:none;"' !!}>
                        <div class="mb-3 form-group position-relative">
                            {{ Form::label('petty_cash_bank_id', __('Bank'), ['class' => 'form-label']); }}
                            {!! Form::select('petty_cash_bank_id', $banks, null, ['class' => 'form-control select2','data-toggle'=>'select2','placeholder' => __("Please Select"),'tabindex'=>'22']) !!} 
                        </div>
                    </div>
                    <div class="col-lg-4 petty-cash-other-bank" {!! (isset($line) && $line->petty_cash_bank_id === 0) ? '' : 'style="display:none;"' !!}>
                        <div class="mb-3 form-group">
                            {{ Form::label('petty_cash_other_bank', __('Bank Name'), ['class' => 'form-label']) }}
                            {{ Form::text('petty_cash_other_bank',null,['class' => 'form-control','placeholder' => __('Bank Name'),'tabindex'=>'23'])}}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3 form-group position-relative">
                            {{ Form::label('petty_cash_book_month', __('Month'), ['class' => 'form-label']); }}
                            {!! Form::select('petty_cash_book_month_id[]', $months, null, ['class' => 'select2 form-control select2-multiple','data-toggle'=>'select2','multiple'=>'','data-placeholder' => __("Please Select"),'tabindex'=>'24']) !!} 
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3 form-group">
                            {{ Form::label('petty_cash_book_lines', __('Petty Cash Book Lines'), ['class' => 'form-label']); }}
                            {{ Form::text('petty_cash_book_lines',null,['class' => 'form-control','placeholder' => __('Petty Cash Book Lines'),'tabindex'=>'25'])}}
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="mb-3 form-group">
                            {{ Form::label('petty_cash_book-registrations', __('Registrations'), ['class' => 'form-label'])}}
                            {{ Form::text('petty_cash_book_registrations',null,['class' => 'form-control','placeholder' => __('Registrations'),'tabindex'=>'26'])}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer border-top border-light">
        <x-success-button class='me-2' id='submitbtn' tabindex='21'><i class="mdi mdi-content-save me-1"></i>{{__('Save')}}</x-success-button>
        <a href="{{$linesListingLink}}" class="btn btn-outline-secondary" tabindex='22'>{{__('Cancel')}}</a>
        <div class="pt-3"><div id="notify"></div></div>
    </div>
    {!! Form::close() !!}
</div>

