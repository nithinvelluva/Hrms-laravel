$(document).ready(function () {
  ClearValues();
  $('#EmpDateOfBirth').datepicker({
    format: "yyyy-mm-dd",
    changeMonth: true,
    changeYear: true,
    beforeShow: function(input) {
      $(input).datepicker("widget").removeClass('hide-calendar');
    }
  });

  $('#desigDrpDwn').on('change', function () {
    $('#DesigType').val($('#desigDrpDwn').val());
  });
  $('#roleDropDwn').on('change', function () {
    $('#Usertype').val($('#roleDropDwn').val());
  });

  $('#Empgendermale').change(function() {
    var checked = $(this).is(':checked');
    $('#Gender').val((checked)?'M':'F');
    $('#Empgenderfemale').prop('checked', !checked);
    $('#lblgender').hide();
    console.log(checked);
  });
  $('#Empgenderfemale').change(function() {
    var checked = $(this).is(':checked');
    $('#Gender').val((checked)?'F':'M');
    $('#Empgendermale').prop('checked', !checked);
    $('#lblgender').hide();
  });

  function ClearValues() {
    $('.inputField').val('');
    $('#Empgendermale').prop('checked', false);
    $('#Empgenderfemale').prop('checked', false);
    $('#Gender').val('');
    clearValidationError();
  }
});