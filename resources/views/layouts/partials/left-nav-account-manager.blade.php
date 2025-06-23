<!--- Sidemenu -->
<ul class="side-nav">
    <li class="side-nav-title">{{__('Navigation')}}</li>
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarProductionReports" aria-expanded="false" aria-controls="sidebarProductionReports" class="side-nav-link">
            <i class="ri-line-chart-fill"></i>
            <span>Production Reports</span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarProductionReports">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{route('account-manager.line-expenses.index')}}">Account Manager</a>
                </li>
                <li>
                    <a href="{{route('account-manager.line-incomes.index')}}">Studio</a>    
                </li>
            </ul>
        </div>
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
                    <a href="{{route('account-manager.lines.index')}}">All Tasks</a>
                </li>
                <li>
                    <a href="{{route('account-manager.lines.create')}}" >Add New Task</a>
                </li>
                <li>
                    <a href="{{route('account-manager.reports.lines')}}">Operatore Report</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarClients" aria-expanded="false" aria-controls="sidebarClients" class="side-nav-link">
            <i class="ri-building-line"></i>
            <span> {{trans_choice('Client|Clients',2)}} </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarClients">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{route('account-manager.clients.index')}}">{{trans_choice('Client|Clients',2)}}</a>
                </li>
                <li>
                    <a href="{{route('account-manager.clients.create')}}">Add New {{trans_choice('Client|Clients',1)}}</a>
                </li>
                <li>
                    <a href="{{route('account-manager.companies.index')}}">{{trans_choice('Company|Companies',2)}}</a>
                </li>
                <li>
                    <a href="{{route('account-manager.companies.create')}}">Add New {{trans_choice('Company|Companies',1)}}</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="side-nav-item">
        <a href="{{route('account-manager.operators.index')}}" class="side-nav-link">
            <i class="mdi mdi-account-clock"></i>
            <span> {{trans_choice('Operator|Operators',2)}} </span>
        </a>
    </li>   
    <li class="side-nav-item">
        <a href="{{route('account-manager.profile.edit')}}" class="side-nav-link">
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
