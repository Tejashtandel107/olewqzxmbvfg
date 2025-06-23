<x-app-starter-layout>    
        <div style="max-width:400px;margin:0px auto;">
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
</x-app-starter-layout>