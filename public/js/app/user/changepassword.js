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