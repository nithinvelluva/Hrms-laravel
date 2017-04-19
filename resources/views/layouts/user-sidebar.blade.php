<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name', 'HRMS') }}</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}" />

    <link rel="stylesheet" href="{{asset('css/User/UserDetails.css')}}" />
    <link rel="stylesheet" href="{{asset('css/User/Imageoverlay.css')}}" />
    <link rel="stylesheet" href="{{asset('css/User/EmployeeReportsStyle.css')}}" />

    <!--  Light Bootstrap Table core CSS    -->
    <link rel="stylesheet" href="{{asset('css/light-bootstrap-dashboard.css')}}" />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" href="{{asset('css/Roboto-font.css')}}" />
    <link rel="stylesheet" href="{{asset('css/pe-icon-7-stroke.css')}}" />

    <link rel="stylesheet" href="{{asset('css/lobibox.css')}}" />
    <link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/responsive.dataTables.min.css')}}" />
    <link rel="stylesheet" href="{{asset('css/calendar.css')}}" />

    <script src="{{ asset('js/jquery-ui-1.11.4.min.js') }}"></script>
    <script src="{{ asset('js/lobibox.js') }}"></script>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar" data-color="purple">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="{{ url('/user/profile') }}" class="simple-text">
                        HRMS - PROFILE
                    </a>
                </div>
                <ul class="main-nav nav">
                    <li class="cursor" id="userProfileRef">
                        <a href="{{ url('/user/profile') }}">
                            <i class="pe-7s-user"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    <li id="attendanceRef">
                        <a href="{{ url('/user/attendance') }}">
                            <i class="pe-7s-note"></i>
                            <p>Attendance</p>
                        </a>
                    </li>

                    <li id="leavesRef" onclick="leaveRefClick()" class="cursor">
                        <a href="#leaveItemsDiv" data-toggle="collapse">
                            <i class="pe-7s-note2"></i>
                            <p>
                                Leaves
                                <b class="caret"></b>
                            </p>
                        </a>

                        <div class="active" id="leaveItemsDiv">
                            <ul class="nav nav-list">
                                <li class="cursor"><a href="{{ url('/user/leave') }}" id="applyLeaveRef">Apply Leave</a></li>
                                <li class="cursor"><a href="{{ url('/user/leavereports') }}" id="leaveReportRef">Leave Reports</a></li>
                            </ul>
                        </div>
                    </li>
                    <li id="empReportRef" class="cursor">
                        <a href="{{ url('/user/employeereports') }}">
                            <i class="pe-7s-news-paper"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    <li id="empTaskRef" class="cursor">
                        <a href="javascript:void(0)">
                            <i class="pe-7s-menu"></i>
                            <p>Tasks</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <nav class="navbar navbar-default navbar-fixed">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="javascript:void(0)">Profile</a>
                    </div>
                    <div class="collapse navbar-collapse cursor">
                        <ul class="nav navbar-nav navbar-right" style="padding-right:1em">
                            <li class="dropdown cursor">
                                <a href="javascript:void(0)" class="dropdown-toggle usr_drpdwn" data-toggle="dropdown">
                                    <label id="wlcmMsgLabel" class="wlcmMsg">{{$empName}}</label>&nbsp;<i class="fa fa-user" aria-hidden="true"></i>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="cursor"><a href="{{ url('/user/changepassword') }}"id="chngPwdRef">
                                      <i class="fa fa-cog" aria-hidden="true"></i>&nbsp;Change Password</a>
                                  </li>
                                  <li class="cursor">
                                      <a href="{{ url('account/logout') }}" id="signoutRef">
                                          <i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Sign Out</a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                      </div>
                  </div>
              </nav>

              <div class="content container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<script>
    $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
  });
</script>
<script type="text/javascript" src="{{asset('js/app/user/user-sidebar.js')}}"></script>
