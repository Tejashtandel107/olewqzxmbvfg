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
                    <a href="{{route('admin.line-incomes.index')}}">Studio</a>
                </li>
                <li>
                    <a href="{{route('admin.line-expenses.account-managers')}}">Account Manager</a>    
                </li>
                <li>
                    <a href="{{route('admin.line-expenses.operators')}}">Operatore</a>
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
                    <a href="{{route('admin.lines.index')}}">All Tasks</a>
                </li>
                <li>
                    <a href="{{ route('admin.lines.create') }}">Add New Task</a> 
                </li>
                <li>
                    <a href="{{route('admin.reports.lines.client')}}">Studio Report</a>
                </li>
                <li>
                    <a href="{{route('admin.reports.lines.team')}}">Team Report</a>
                </li>
                <li>
                    <a href="{{route('admin.reports.lines.operator')}}">Operatore Report</a>
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
                    <a href="{{route('admin.clients.index')}}">{{trans_choice('Client|Clients',2)}}</a>
                </li>
                <li>
                    <a href="{{ route('admin.clients.create') }}">Add New {{trans_choice('Client|Clients',1)}}</a>
                </li>
                <li>
                    <a href="{{route('admin.companies.index')}}">{{trans_choice('Company|Companies',2)}}</a>
                </li>
                <li>
                    <a href="{{route('admin.companies.create')}}">Add New {{trans_choice('Company|Companies',1)}}</a>
                </li>
            <?php 
                /*
                <li>
                    <a href="{{route('admin.imports.create')}}">{{__('Company Import')}}</a>
                </li>
                */
            ?>
            </ul>
        </div>
    </li>
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarAccountManagers" aria-expanded="false" aria-controls="sidebarAccountManagers"  class="side-nav-link">
            <i class="mdi mdi-account-tie"></i>
            <span> {{trans_choice('Account Manager|Account Managers',2)}} </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarAccountManagers">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{ route('admin.account-managers.index') }}">All {{trans_choice('Account Manager|Account Managers',2)}}</a>
                </li>
                <li>
                    <a href="{{ route('admin.account-managers.create') }}">Add New</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="side-nav-item">
        <a data-bs-toggle="collapse" href="#sidebarOperator" aria-expanded="false" aria-controls="sidebarOperator"  class="side-nav-link">
            <i class="mdi mdi-account-clock"></i>
            <span> {{trans_choice('Operator|Operators',2)}} </span>
            <span class="menu-arrow"></span>
        </a>
        <div class="collapse" id="sidebarOperator">
            <ul class="side-nav-second-level">
                <li>
                    <a href="{{route('admin.operators.index')}}">All {{trans_choice('Operator|Operators',2)}}</a>
                </li>
                <li>
                    <a href="{{route('admin.operators.create')}}">Add New</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="side-nav-item">
        <a href="{{route('admin.profile.edit')}}" class="side-nav-link">
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
