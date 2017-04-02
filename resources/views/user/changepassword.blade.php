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
<script>
    $(document).ready(function(){
      navigateTo(CHANGEPASSWORD);
      $('#currntPwd').on('change blur', function () {
          if (this.value.toString().length <= 0) {
              $('#currntPwd').css('border-color', 'red');
              $('#ErrPwdCurr').text("Enter current password !!");
              $('#ErrPwdCurr').show();
          }
          else {
              $('#currntPwd').css('border-color', '');
              $('#ErrPwdCurr').hide();
          }
      });
      $('#nwPwd').on('change blur', function () {
          if (this.value.toString().length <= 0) {
              $('#nwPwd').css('border-color', 'red');
              $('#ErrnwPwd').show();
          }
          else {
              $('#nwPwd').css('border-color', '');
              $('#ErrnwPwd').hide();
          }
      });
      $('#nwPwdCnfrm').on('change keyup paste blur', function () {
          if (this.value.toString().length <= 0) {
              $('#nwPwdCnfrm').css('border-color', 'red');
              $('#ErrnwPwdCnfm').text("Enter new password again to confirm !!");
              $('#ErrnwPwdCnfm').show();
          }
          else {
              if (($('#nwPwd').val().length > 0 && $('#nwPwdCnfrm').val().length > 0) && ($('#nwPwd').val() != $('#nwPwdCnfrm').val())) {
                  $('#nwPwdCnfrm').css('border-color', 'red');
                  $('#ErrnwPwdCnfm').text("Confirm password doesn't match !!");
                  $('#ErrnwPwdCnfm').show();
              }
              else {
                  $('#nwPwdCnfrm').css('border-color', '');
                  $('#ErrnwPwdCnfm').text("Ente new password again to confirm !!");
                  $('#ErrnwPwdCnfm').hide();
              }
          }
      });
    });
    function chngPwdSubmit() {
        clearErrors();
        var currntPwd = $('#currntPwd').val();
        var nwPwd = $('#nwPwd').val();
        var nwPwdCnfm = $('#nwPwdCnfrm').val();
        var validFlag = true;
        if (stringIsNull(currntPwd)) {
            $('#currntPwd').css('border-color', 'red');
            $('#ErrPwdCurr').text("Enter current password !!");
            $('#ErrPwdCurr').show();
            validFlag = false;
        }
        if (stringIsNull(nwPwd)) {
            $('#nwPwd').css('border-color', 'red');
            $('#ErrnwPwd').text("Enter new password !!");
            $('#ErrnwPwd').show();
            validFlag = false;
        }
        if (stringIsNull(nwPwdCnfm)) {
            $('#nwPwdCnfrm').css('border-color', 'red');
            $('#ErrnwPwdCnfm').text("Enter new password again to confirm !!");
            $('#ErrnwPwdCnfm').show();
            validFlag = false;
        }
        if (validFlag) {
            if (nwPwd != nwPwdCnfm) {
                $('#nwPwdCnfrm').css('border-color', 'red');
                $('#ErrnwPwdCnfm').text("Confirm Password doesn't match !!");
                $('#ErrnwPwdCnfm').show();
            }
            else {
                showLoadreport("#LoadPageChngpwd", ".chngPwdFormDiv");
                var UpdatePassword = {};
                UpdatePassword.url = "/user/changepassword";
                UpdatePassword.type = "POST";
                UpdatePassword.data = JSON.stringify({currPwd: currntPwd, nwPwd: nwPwd, nwPwdCnfm: nwPwdCnfm });
                UpdatePassword.datatype = "json";
                UpdatePassword.contentType = "application/json";
                UpdatePassword.success = function (response) {
                    
                    HideLoadreport("#LoadPageChngpwd", ".chngPwdFormDiv");
                    if (stringIsNull(response)) {
                        showMessageBox(ERROR, "An UnExpected Error Occured!!");
                    }
                    else {
                        try {
                            if(response.status && 'OK' == response.status){
                              clearFileds();
                              showMessageBox(SUCCESS, "Password updated successfully");
                            }
                            else if(response.error){
                              var errlst = response.error.split(":");
                              var errcnt = errlst.length;
                              for (var i = 0; i <= errcnt - 1; i++) {
                                  if (errlst[i] == "1") {
                                      $('#currntPwd').css('border-color', 'red');
                                      $('#ErrPwdCurr').text("Enter current password !!");
                                      $('#ErrPwdCurr').show();
                                  }
                                  else if (errlst[i] == "2") {
                                      $('#nwPwd').css('border-color', 'red');
                                      $('#ErrnwPwd').text("Enter new password !!");
                                      $('#ErrnwPwd').show();
                                  }
                                  else if (errlst[i] == "3") {
                                      $('#nwPwdCnfrm').css('border-color', 'red');
                                      $('#ErrnwPwdCnfm').text("Enter new password again to confirm !!");
                                      $('#ErrnwPwdCnfm').show();
                                  }
                                  else if (errlst[i] == "4") {
                                      $('#ErrnwPwdCnfm').text("Confirm password doesn't match !!");
                                      $('#ErrnwPwdCnfm').show();
                                  }
                                  else if (errlst[i] == "ERROR") {
                                      $('#currntPwd').css('border-color', 'red');
                                      $('#ErrPwdCurr').text("Incorrect password !!");
                                      $('#ErrPwdCurr').show();
                                  }
                            }
                          }
                        }
                        catch (ex) {
                        }
                    }
                };
                UpdatePassword.error = function (response) {
                    HideLoadreport("#LoadPageChngpwd", ".chngPwdFormDiv");
                    showMessageBox(ERROR, "An unexpected error occured !!");
                };
                $.ajax(UpdatePassword);
            }
        }
    }
    function clearFileds() {
        $('#currntPwd').val('');
        $('#nwPwd').val('');
        $('#nwPwdCnfrm').val('');
    }
</script>
