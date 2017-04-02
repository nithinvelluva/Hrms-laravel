@extends('layouts.user-sidebar')
<head>

    <!--   Core JS Files   -->
    <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>

    <script src="{{ asset('js/Helpers/Common.js') }}"></script>
    <script src="{{ asset('js/modernizr-2.8.3.js') }}"></script>
    <script src="{{ asset('js/Dashboard/constants.js') }}"></script>
    <script src="{{ asset('js/Dashboard/light-bootstrap-dashboard.js') }}"></script>
</head>
<body>
  @section('content')
  <div class="col-md-8">
    <div class="card">
        <div id="LoadPageChngpwd" class="row text-center waitIconDivRprt" style="display:none">
          <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="50" height="50" id="imgProgQuery" />
        </div>
        <div class="header">
          <h4 class="title" id="profileTitle">Change Password</h4>
        </div>
        <br>
        <div class="content chngPwdFormDiv">
          <div class="row">
            <div class="col-lg-6 col-md-6">
                <label class = "control-label hrms-required-field">Current Password</label>
            </div>
            <div class="col-lg-6 col-md-6">
                <input type="password" class="form-control input-field" id="currntPwd" name="currntPwd" autocomplete="off" />
                <label id="ErrPwdCurr" class="text-danger errLabel">Enter current password !!</label>
            </div>
        </div>
          <br>
          <div class="row">
            <div class="col-lg-6 col-md-6">
                <label class = "control-label hrms-required-field">New Password</label>
            </div>
            <div class="col-lg-6 col-md-6">
                <input type="password" class="form-control input-field" id="nwPwd"/>
                <label id="ErrnwPwd" class="text-danger errLabel">Enter new password !!</label>
            </div>
        </div>
          <br>
          <div class="row">
            <div class="col-lg-6 col-md-6">
                <label class = "control-label hrms-required-field">Confirm New Password</label>
            </div>
            <div class="col-lg-6 col-md-6">
                <input type="password" class="form-control input-field" id="nwPwdCnfrm"/>
                <label id="ErrnwPwdCnfm" class="text-danger errLabel">Enter Confirm password !!</label>
            </div>
        </div>
          <br>
          <div class="row text-right">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <input type="button" class="btn btn-info btn-fill" value="Update" title="Save" onclick="chngPwdSubmit()" />
                <input type="button" class="btn btn-default btn-fill" value="Cancel" title="Cancel" onclick="clearFileds(); clearErrors()" />
            </div>
        </div>
      </div>
    </div>
  </div>
  @endsection
</body>
<script src="{{asset('js/app/user/changepassword.js')}}"></script>
