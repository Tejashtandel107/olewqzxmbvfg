<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
<div class="card">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-centered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>Mese Anno</th>
                        <th>Tipo di Prezzo</th>
                        <th>Prezzo Fisso</th>
                        <th>Pietra Miliare</th>
                        <th>Prezzo Per Ordinaria</th>
                        <th>Prezzo Per Semplificata</th>
                        <th>Prezzo Per Corrispettivi(Semplificata)</th>
                        <th>{{__("Action")}}</th>
                    </tr>
                </thead>
                <tbody>
            @if(isset($pricings) && $pricings->count()>0)
                @foreach($pricings as $pricing)
                    <tr>
                        <td>
                            <h5>{{ $pricing->month->name .' '. $pricing->year}}</h5>
                        </td>
                        <td>{{ Helper::getStudioPricingTypeLabel($pricing->pricing_type) }}</td>
                        <td>{!!Helper::formatAmount($pricing->price) !!}</td>
                        <td>{{ $pricing->milestone }}</td>
                        <td>{!!Helper::formatAmount($pricing->price_ordinaria)!!}</td>
                        <td>{!!Helper::formatAmount($pricing->price_semplificata)!!}</td>
                        <td>{!!Helper::formatAmount($pricing->price_corrispettivi_semplificata)!!}</td>
                        <td>
                            <a href="{{ route('admin.clients.pricings.edit',[$pricing->user_id,$pricing->id]) }}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
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
            <div class="col-sm-12 col-md-5">
                <x-show-entries :records="$pricings"/>
            </div>
            <div class="col-sm-12 col-md-7"> 
                <div class="dataTables_paginate paging_simple_numbers">
                    <ul class="pagination pagination-rounded pagination justify-content-end">
                        {!! $pricings->links() !!}
                    </ul>
                </div>
            </div>
        </div>
    </div> <!-- end card-body-->
</div>
</x-app-layout>
