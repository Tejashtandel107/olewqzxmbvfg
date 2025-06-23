<x-app-layout :breadcrumbs="$breadcrumbs" :pagetitle="$pagetitle">
<div class="card">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            <table class="table table-centered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>{{trans_choice("User|Users",1)}}</th>
                        <th>{{__("Cost Type")}}</th>
                        <th>{{__("Fixed Cost")}}</th>
                        <th>{{__("Cost For Ordinaria")}}</th>
                        <th>{{__("Cost For Semplificata")}}</th>
                        <th>Bonus Target</th>
                        <th>{{__("Month")}}</th>
                        <th>{{__("Year")}}</th>
                        <th>{{__("Action")}}</th>
                    </tr>
                </thead>
                <tbody>
            @if(isset($pricings) && $pricings->count()>0)
                @foreach($pricings as $pricing)
                    <tr>
                        <td>{{ ($user->name) ?? "N/A" }}</td>
                        <td>{{ $pricing->pricing_type }}</td>
                        <td>&euro;{{Helper::formatAmount($pricing->price) }}</td>
                        <td>&euro;{{Helper::formatAmount($pricing->price_ordinaria)}}</td>
                        <td>&euro;{{Helper::formatAmount($pricing->price_semplificata) }}</td>
                        <td>{{ $pricing->bonus_target }}</td>
                        <td>{{ $pricing->month->name }}</td>
                        <td>{{ $pricing->year }}</td>
                        <td>
                            <a href="{{ route('admin.operators.pricings.edit',[$pricing->user_id,$pricing->pricing_id]) }}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
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
