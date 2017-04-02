$(document).ready(function () {
  navigateTo(PROFILE);
  DisableFields();
});
function DisableFields() {
  $('#EmpId').attr('disabled', 'disabled');
  $('#EmpDesig').attr('disabled', 'disabled');
  $('#EmpGender').attr('disabled', 'disabled');
  $('.input-field-profile').attr('disabled', 'disabled');
}
function EnableFields() {
  $('.input-field-profile').removeAttr('disabled');
}
function editProfileClick(){
  $('#editBtn').hide();
  $('#updateBtn').show();
  EnableFields();
}
function removePhotoClick(){
  if('false' == $('#IsDefaultUserIcon').val())
  {
    $.ajax({
      url: "/user/removeProfilePhoto",
      type: "POST",
      processData: false,
      contentType: false,
      success: function (response) {
        if(response && "OK" == response.status){
          var path = '/images/avatar.png';
          $('#userAvatar').attr('src',path);
          $('#userPhoto').val(path);
        }
      },
      error: function (er) {
        showMessageBox(ERROR,er);
      },
      complete :function(data){
      }
    });
  }
}
$('#UserName').on('blur', function () {
  if (this.value.toString().length == 0) {
    $('#UserName').css('border-color', 'red');
    $('#lblusername').show();
  }
  else {
    $('#UserName').css('border-color', '');
    $('#lblusername').hide();
  }
});
$('#EmpFirstName').on('blur', function () {
  if (this.value.toString().length == 0) {
    $('#EmpFirstName').css('border-color', 'red');
    $('#lblempFirstname').show();
  }
  else {
    $('#EmpFirstName').css('border-color', '');
    $('#lblempFirstname').hide();
  }
});
$('#EmpDateOfBirth').on('blur', function () {
  if (this.value.toString().length == 0) {
    $('#EmpDateOfBirth').css('border-color', 'red');
    $('#ErrlblDateOfBirth').text("Select Date Of Birth");
    $('#ErrlblDateOfBirth').show();
  }
  else {
    $('#EmpDateOfBirth').css('border-color', '');
    $('#ErrlblDateOfBirth').hide();
  }
});
$('#EmailId').on('blur', function () {
  if (this.value.toString().length == 0) {
    $('#EmailId').css('border-color', 'red');
    $('#lblEmailId').text("Enter Email Id !!");
    $('#lblEmailId').show();
  }
  else {
    $('#EmailId').css('border-color', '');
    $('#lblEmailId').hide();
  }
});
$('#PhoneNumber').on('blur', function () {
  if (this.value.toString().length == 0) {
    $('#PhoneNumber').css('border-color', 'red');
    $('#lblphonenumber').text("Enter Phone Number !!");
    $('#lblphonenumber').show();
  }
  else {
    $('#PhoneNumber').css('border-color', '');
    $('#lblphonenumber').hide();
  }
});