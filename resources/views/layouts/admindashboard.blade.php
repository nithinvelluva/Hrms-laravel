
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Hrms - AdminDashboard</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/Admin/AdminDashboard.css')}}" rel="stylesheet" />

    <!--  Light Bootstrap Table core CSS    -->
    <link href="{{asset('css/light-bootstrap-dashboard.css')}}" rel="stylesheet" />

    <!--     Fonts and icons     -->
    <link href="{{asset('css/Roboto-font.css')}}" rel="stylesheet" />
    <link href="{{asset('css/pe-icon-7-stroke.css')}}" rel="stylesheet" />
    <link href="{{asset('css/datepicker3.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/jquery-ui.css')}}" rel="stylesheet" />
</head>
<body>
    <div class="wrapper">
        <!-- <div class="sidebar" data-color="purple">
            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="javascript:void(0)" class="simple-text">
                        HRMS - DASHBOARD
                    </a>
                </div>
                <ul class="main-nav nav">
                    <li id="dashBoardRef" onclick="navigateTo(DASHBOARD)" style="display:none">
                        <a href="javascript:void(0)">
                            <i class="pe-7s-graph"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="active" id="usersRef" onclick="navigateTo(USERS)">
                        <a href="javascript:void(0)">
                            <i class="pe-7s-user"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    <li style="display:none">
                        <a href="javascript:void(0)">
                            <i class="pe-7s-note2"></i>
                            <p>Attendance</p>
                        </a>
                    </li>
                    <li style="display:none">
                        <a href="javascript:void(0)">
                            <i class="pe-7s-news-paper"></i>
                            <p>Leaves</p>
                        </a>
                    </li>
                    <li style="display:none">
                        <a href="javascript:void(0)">
                            <i class="pe-7s-science"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    <li style="display:none">
                        <a href="javascript:void(0)">
                            <i class="pe-7s-bell"></i>
                            <p>Messages</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div> -->
        <div class="main-panel dashboardContentDiv">
            <nav class="navbar navbar-default navbar-fixed fixed">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand hreflink" href="javascript:void(0)">Dashboard</a>
                        <a class="navbar-brand" href="{{ url('/employees') }}">Employees</a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-left"></ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="{{ url('account/logout') }}">
                                    Log out
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content">
                <div id="usersDiv" class="contentDiv">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>
</html>
