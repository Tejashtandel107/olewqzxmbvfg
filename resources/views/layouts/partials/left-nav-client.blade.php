<!--- Sidemenu -->
<ul class="side-nav">
    <li class="side-nav-title">{{__('Navigation')}}</li>
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarTasksReports" aria-expanded="false" aria-controls="sidebarTasksReports" class="side-nav-link">
            <i class="ri-line-chart-fill"></i>
            <span>Reports</span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarTasksReports">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{route('client.line-incomes.index')}}">Production Report</a>
                </li>
                <li>
                    <a href="{{route('client.reports.lines')}}">Tasks Report</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="side-nav-item">
        <a href="{{route('client.companies.index')}}" class="side-nav-link">
            <i class="ri-building-line"></i>
            <span>{{trans_choice('Company|Companies',2)}}</span>
        </a>
    </li>   
    <li class="side-nav-item">
        <a href="{{route('client.profile.edit')}}" class="side-nav-link">
            <i class="mdi mdi-account-circle me-1"></i>
            <span>{{__('My account')}}</span>
        </a> 
    </li>
    <li class="side-nav-item">
        <a href="{{route('logout')}}" class="side-nav-link" onclick="event.preventDefault();this.querySelector('form').submit();">
            <form method="POST" action="{{ route('logout') }}">                            
                @csrf
            </form>
            <i class="mdi mdi-logout me-1"></i>
            <span>{{ __('Log out') }}</span>
        </a>
    </li>
</ul>
<!--- End Sidemenu -->
