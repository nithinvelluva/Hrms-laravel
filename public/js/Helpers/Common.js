const SUCCESS = 1;
const WARNING = 2;
const ERROR = 3;
const CONFIRM = 4;
const monthKeyValues = JSON.parse('{ "1" : "January", "2" :"February","3":"March", "4":"April", "5":"May", "6":"June", "7":"July", "8":"August", "9":"September", "10":"October", "11":"November", "12":"December" }');
const months = JSON.parse('{ "January": 1, "February": 2, "March": 3, "April": 4, "May": 5, "June": 6, "July": 7, "August": 8, "September": 9, "October": 10, "November": 11, "December": 12 }');
const hostDomain = window.location.origin;
var EMPLOYEENAME = "";

function showMessageBox(type, message) {

    switch (type) {
        case SUCCESS:
            Lobibox.notify("success",
                    {
                        title: "HRMS", position: 'center top',
                        sound: false,size: 'mini', closeOnClick: true, msg: message
                    });
            break;
        case WARNING:
            Lobibox.notify("warning",
           {
               title: "HRMS", size: 'mini', position: 'center top',
               sound: false, closeOnClick: true, msg: message
           });
            break;
        case ERROR:
            Lobibox.notify("error",
                    {
                        title: "HRMS", size: 'mini', position: 'center top',
                        sound: false, closeOnClick: true, msg: message
                    });
            break;
        case CONFIRM:
            break;
        default:
            break;
    }
}

//function showMessageBox(type, message) {
//    switch (type) {
//        case SUCCESS:
//            Lobibox.alert("success",
//                    {
//                        title: "HRMS", closeButton: true, closeOnEsc: true, width: '350', height: 'auto',
//                        msg: message,
//                        buttons: {
//                            ok: {
//                                'class': 'btn btn-info btn-fill msgBxBtns',
//                                text: 'OK',
//                                closeOnClick: true
//                            }
//                        }
//                        ,
//                        callback: function ($this, type, ev) {
//                        }
//                    });
//            break;
//        case WARNING:
//            Lobibox.alert("warning",
//           {
//               title: "HRMS", width: '350', height: 'auto', closeOnEsc: false, closeButton: true,
//               msg: message,
//               buttons: {
//                   ok: {
//                       'class': 'btn btn-info btn-fill msgBxBtns',
//                       text: 'OK',
//                       closeOnClick: true
//                   }
//               }
//           });
//            break;
//        case ERROR:
//            Lobibox.alert("error",
//                    {
//                        title: "HRMS", width: '350', height: 'auto', closeOnEsc: false, closeButton: true,
//                        msg: message,
//                        buttons: {
//                            ok: {
//                                'class': 'btn btn-info btn-fill msgBxBtns',
//                                text: 'OK',
//                                closeOnClick: true
//                            }
//                        }
//                    });
//            break;
//        case CONFIRM:
//            break;
//        default:
//            break;
//    }
//}
function formValidate(page) {
    var validFlag = true;
    switch (page) {
        case LOGINFORM:
            clearValidationError();
            var username = $('#userName').val();
            var password = $('#userPassword').val();
            if (stringIsNull(username)) {
                $('#userName').css('border-color', 'red');
                $('#lbluserName').show();
                validFlag = false;
            }
            else if (!emailRegexValidator(username)) {
                $('#userName').css('border-color', 'red');
                $('#lbluserNameInvl').show();
                validFlag = false;
            }
            if (stringIsNull(password)) {
                $('#userPassword').css('border-color', 'red');
                $('#lblPaswd').show();
                validFlag = false;
            }
            break;
        case REGISTERFORM:
            clearValidationError();
            var name = $('#NameReg').val();
            var phone = $('#userPhone').val();
            var username = $('#userNameReg').val();
            var password = $('#userPasswordReg').val();
            var cnfmPwd = $('#userCnfrmPasswordReg').val();
            if (stringIsNull(name)) {
                $('#NameReg').css('border-color', 'red');
                $('#lblNameReg').show();
                validFlag = false;
            }
            if (stringIsNull(phone)) {
                $('#userPhone').css('border-color', 'red');
                $('#lbluserPhone').show();
                validFlag = false;
            }
            else if (!phoneNumberRegexValidate(phone)) {
                $('#userPhone').css('border-color', 'red');
                $('#lbluserPhoneInvl').show();
                validFlag = false;
            }
            if (stringIsNull(username)) {
                $('#userNameReg').css('border-color', 'red');
                $('#lbluserNameReg').show();
                validFlag = false;
            }
            else if (!emailRegexValidator(username)) {
                $('#userNameReg').css('border-color', 'red');
                $('#lbluserNameRegInvl').show();
                validFlag = false;
            }
            if (stringIsNull(password)) {
                $('#userPasswordReg').css('border-color', 'red');
                $('#lbluserPasswordReg').show();
                validFlag = false;
            }
            else if (!checkStrength(password)) {
                $('#userPasswordReg').css('border-color', 'red');
                $('#lbluserPasswordRegInvl').show();
                validFlag = false;
            }
            if (stringIsNull(cnfmPwd)) {
                $('#userCnfrmPasswordReg').css('border-color', 'red');
                $('#lblusrCnfmPwdReg').show();
                validFlag = false;
            }
            else if (!stringIsNull(password) && cnfmPwd != password) {
                $('#userCnfrmPasswordReg').css('border-color', 'red');
                $('#lblusrCnfmPwdRegMsmatch').show();
                validFlag = false;
            }
            break;
        case RESETPWD:
            clearValidationError();
            var usernameReset = $('#userNameReset').val();
            if (stringIsNull(usernameReset)) {
                $('#userNameReset').css('border-color', 'red');
                $('#lbluserNameReset').show();
                validFlag = false;
            }
            else if (!emailRegexValidator(usernameReset)) {
                $('#userNameReset').css('border-color', 'red');
                $('#lbluserNameResetInvl').show();
                validFlag = false;
            }
            break;
        default: validFlag = false;
            break;
    }
    return validFlag;
}
function clearValidationError() {
    $('.inputField').css('border-color', '');
    $('.errLabel').hide();
}
function stringIsNull(string) {
    var flag = false;
    if (string == null || string == undefined || string == "") {
        flag = true;
    }
    return flag;
}

/* Password strength indicator */
function checkStrength(password) {

    var desc = [{ 'width': '0px' }, { 'width': '20%' }, { 'width': '40%' }, { 'width': '60%' }, { 'width': '80%' }, { 'width': '100%' }];

    var descClass = ['', 'progress-bar-danger', 'progress-bar-warning', 'progress-bar-warning', 'progress-bar-success'];

    var score = 0;

    //if password bigger than 5 give 1 point
    if (password.length >= 5) score++;

    //if password has both lower or uppercase characters give 1 point
    if ((password.match(/[a-z]/)) || (password.match(/[A-Z]/))) score++;

    //if password has at least one number give 1 point
    if (password.match(/\d+/)) score++;

    //if password has at least one special caracther give 1 point
    if (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) score++;

    var isMatch = false;
    // display indicator
    if (score >= 4) {

        $("#pwd_strength").removeClass(descClass[3]).addClass(descClass[4]).css(desc[5]);
        isMatch = true;
    }
    else if (score < 4) {
        $("#pwd_strength").removeClass(descClass[score - 1]).addClass(descClass[score]).css(desc[score]);
    }
    return isMatch;
}
function emailRegexValidator(emailId) {
    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
    var isMatch = false;

    if (pattern.test(emailId)) {
        isMatch = true;
    }
    return isMatch;
}
function phoneNumberRegexValidate(phoneNumber) {
    var pattern = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
    var isMatch = false;

    if (pattern.test(phoneNumber)) {
        isMatch = true;
    }
    return isMatch;
}
