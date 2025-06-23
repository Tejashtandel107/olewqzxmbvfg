@props(['pagetitle' => '','breadcrumbs' => []])
<?php
    $auth_user = (Auth::check()) ? Auth::user () : null;
	if($auth_user){
        $my_account_url = Helper::getUserAccountURL($auth_user);
	}
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <title>{{ $pagetitle }} : {{config('app.name')}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
         <!-- PLUGINS CSS-->
         @yield('plugin-css')
        <!-- App css -->
        <link href="{{asset('assets/css/app-saas.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
        <!-- Icons css -->
        <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        @yield('page-css')
    </head>
    <style type="text/css">
		.select2-container .select2-selection--multiple .select2-selection__rendered {
            padding: 0px;
		}
        .multiple-select-optgroup{
            max-width:400px;
        }
        .form-control-width{
            min-width:200px
        }
    </style>
    <body>
        <!-- Begin page -->
        <div class="wrapper">
            <!-- ========== Topbar Start ========== -->
            <div class="navbar-custom">
                <div class="topbar container-fluid">
                    <div class="d-flex align-items-center gap-lg-2 gap-1">
                        <!-- Sidebar Menu Toggle Button -->
                        <button class="button-toggle-menu">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </div>

                    <ul class="topbar-menu d-flex align-items-center gap-3">
                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle arrow-none nav-user px-2" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="account-user-avatar">
                                    <img src="{{$auth_user->photo}}" alt="user-image" width="32" height="32" class="rounded-circle">
                                </span>
                                <span class="d-lg-flex flex-column gap-1 d-none">
                                    <h5 class="my-0">{{  Str::substr(Auth::user()->name, 0, 10); }}</h5>
                                    <h6 class="my-0 fw-normal">{{Helper::getRoleName(Auth::user()->role_id)}}</h6>
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated profile-dropdown">
                                <!-- item-->
                                <div class=" dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">{{__('Welcome')}} !</h6>
                                </div>
                                <!-- item-->
                                <a href="{{($my_account_url) ?? '#'}}" class="dropdown-item">
                                    <i class="mdi mdi-account-circle me-1"></i>
                                   <span>{{__('My account')}}</span>
                                </a> 
                                <!-- item-->    
                                <a href="{{route('logout')}}" class="dropdown-item" onclick="event.preventDefault();this.querySelector('form').submit();">
                                    <form method="POST" action="{{ route('logout') }}">                            
                                        @csrf
                                    </form>
                                    <i class="mdi mdi-logout me-1"></i>
                                    <span>{{ __('Log out') }}</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>            
            <!-- ========== Topbar End ========== -->
            
            <!-- ========== Left Sidebar Start ========== -->
            <div class="leftside-menu">
                <!-- Brand Logo Light -->
                @php
                    $home_url = Helper::getUserHomeURL(Auth::user());
                @endphp
                <a href="{{$home_url}}" class="logo logo-light">
                    <span class="logo-lg"><x-application-logo/></span>
                    <span class="logo-sm"><x-application-logo-sm/></span>
                </a>
                <!-- Sidebar -left -->
                <div class="h-100" id="leftside-menu-container" data-simplebar>
                    <!--- Sidemenu -->
                    @if (Auth::user()->isSuperAdmin())
                        @include('layouts.partials.left-nav-admin')
                    @elseif (Auth::user()->isAccountManager())
                        @include('layouts.partials.left-nav-account-manager')
                    @elseif (Auth::user()->isClient())
                        @include('layouts.partials.left-nav-client')
                    @else
                        @include('layouts.partials.left-nav-operator')
                    @endif        
                    <!--- End Sidemenu -->
                    <div class="clearfix"></div>
                </div>
            </div>
            <!-- ========== Left Sidebar End ========== --> 

            <!-- ============================================================== -->
            <!-- Start Page Content here -->
            <!-- ============================================================== -->

            <div class="content-page">
                <div class="content">
                    <!-- Start Content-->
                    <div class="container-fluid">
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">
                                        <x-breadcrumbs :breadcrumbs="$breadcrumbs"/>
                                    </div>
                                    <h4 class="page-title">{{ $pagetitle}}</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        {{$slot}}
                    </div> <!-- container -->
                </div> <!-- content -->

                <!-- Footer Start -->
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                &copy; {{now()->format('Y')}} {{config('app.name')}} by {{Helper::getSetting('company_name')}}
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end footer-links d-none d-md-block">
                                    <a target="_blank" href="https://www.outsourcingcontabilita.it/"><i class="uil-external-link-alt"></i> About</a>
                                    <a target="_blank" href="https://www.outsourcingcontabilita.it/"><i class="uil-external-link-alt"></i> Contact Us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                <!-- end Footer -->
            </div>

            <!-- ============================================================== -->
            <!-- End Page content -->
            <!-- ============================================================== -->

        </div>
        <!-- END wrapper -->

        <!-- Theme Config Js -->
        <script src="{{asset('assets/js/hyper-config.min.js')}}"></script>
        <!-- Vendor js -->
        <script src="{{asset('assets/js/vendor.min.js')}}"></script>
        <!-- PLUGINS-->
         @yield('plugin-scripts') 
        <script src="{{asset('assets/js/plugin/jquery.buttonSpinner.min.js')}}"></script>
        <script src="{{asset('assets/js/plugin/jquery.notification.min.js')}}"></script>
        <!-- App js -->
        <script src="{{asset('assets/js/app.min.js')}}"></script>
        <!-- PAGE SCRIPTS-->
        @yield('page-scripts')
    </body>
</html>
