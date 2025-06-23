<div class="mb-3 mt-2"><x-show-entries :records="$lines"/></div>
<div class="table-responsive">
    <table class="table table-centered table-striped">
        <thead class="table-light">
            <tr>
                <th>{{ trans_choice('Client|Clients',1) }}</th>
                <th>{{trans_choice("Operator|Operators",1)}}</th>
                <th>{{__("Updated By")}}</th>
                <th>{{__('Date')}}</th>
                <th>{{__('Created')}}</th>
                <th>{{__('Updated')}}</th>
                <th>{{__("Action")}}</th>
            </tr>
        </thead>
        <tbody> 
        @forelse($lines as $line)
            <tr>
                <td>
                    <div class="fw-semibold">{{ ($line->client_name) ?? "N/A" }}</div>
                    <div class="text-muted">{{ ($line->company_name) ?? "N/A" }}({{$line->company_type}})</div>
                </td>
                <td>
                    <div>{{ ($line->operator_name) ?? '-' }}</div>
                    <small class="text-muted">{{Helper::getRoleName($line->operator_role_id)}}</small>
                </td>
                <td>
                    <div>{{ ($line->updator_name) ?? '-' }}</div>
                    <small class="text-muted">{{Helper::getRoleName($line->updator_role_id)}}</small>
                </td>
                <td>{{ $line->register_date->format(config('constant.DATE_FORMAT')) }}</td>
                <td>{{ $line->created_at }}</td>
                <td>{{ $line->updated_at }}</td>
                <td>
            <?php 
                $line_edit_link = "";
                $line_delete_link = route('api.lines.destroy',$line->line_id);
                if (Auth::user()->isSuperAdmin())
                    $line_edit_link = route('admin.lines.edit',$line->line_id);
                elseif (Auth::user()->isAccountManager())
                    $line_edit_link = route('account-manager.lines.edit',$line->line_id);
                elseif (Auth::user()->isOperator())
                    $line_edit_link = route('operator.lines.edit',$line->line_id);
            ?>
                    <a href="{{ $line_edit_link }}" class="action-icon"> <i class="mdi mdi-square-edit-outline"></i></a>
                    <a class="action-icon" onclick="deleteRecord('{{$line_delete_link}}');" href="javascript:;"><i class="mdi mdi-delete"></i></a>                                
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7"><div class="alert alert-danger"><i class="ri-alert-line me-2 align-middle"></i> {{__('messages.no_records')}} </div></td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="row">
    <div class="col-sm-12 col-md-5"><x-show-entries :records="$lines"/></div>
    <div class="col-sm-12 col-md-7"> 
        <div class="dataTables_paginate paging_simple_numbers">
            <ul class="pagination pagination-rounded pagination justify-content-end">
                {!! $lines->links() !!}
            </ul>
        </div>
    </div>
</div>