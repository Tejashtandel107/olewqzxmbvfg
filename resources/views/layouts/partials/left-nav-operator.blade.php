<!--- Sidemenu -->
<ul class="side-nav">
    <li class="side-nav-title">{{__('Navigation')}}</li>
    <li class="side-nav-item">
        <a href="{{route('operator.line-expenses.index')}}" class="side-nav-link">
            <i class="ri-line-chart-fill"></i>
            <span>Production Report</span>
        </a>
    </li>
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarTasksReports" aria-expanded="false" aria-controls="sidebarTasksReports" class="side-nav-link">
            <i class="uil-clipboard-alt"></i>
            <span>Tasks</span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarTasksReports">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{route('operator.lines.index')}}">All Tasks</a>
                </li>
                <li>
                    <a href="{{route('operator.lines.create')}}">Add New Task</a>
                </li>
                <li>
                    <a href="{{route('operator.reports.lines')}}">Tasks Report</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="side-nav-item">
        <a href="{{route('operator.profile.edit')}}" class="side-nav-link">
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
