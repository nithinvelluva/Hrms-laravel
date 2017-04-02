//  $(document).on('click','.matcard',function(){
      //     alert($(this).attr('data-id'));
      // })
      function EditEmployee(){
        var empId = $('#IdNew').val();
        clearValidationError();
        if(empId != null && empId != "" && empId != undefined){
          $('#editEmpModal').modal('show');
        }
        else{
          alert("Select an employee to edit !!");
        }
      }
      function DeleteEmployee(){
        var empId = $('#IdNew').val();
      }
      function ClearValues() {
        $('.inputField').val('');
        $('#Empgendermale').prop('checked', false);
        $('#Empgenderfemale').prop('checked', false);
        $('#Gender').val('');
        clearValidationError();
      }
      $(document).ready(function () {
        $('.empSelRadBtn').prop('checked', false);
        $('#IdNew').val('');
        $('#employeeTable').DataTable();

        $('#EmpDateOfBirth').datepicker({
          format: "yyyy-mm-dd",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });

        $("#editEmpModal").on("hidden.bs.modal", function () {
          $('.empSelRadBtn').prop('checked', false);
          $('#empEditBtn').prop("disabled", true);
          $('#empDeleteBtn').prop("disabled", true);
        });

        $(document).on('click', '.paginate_button', function () {
          var Idnew = $('#IdNew').val();
          $('.empSelRadBtn').prop('checked', false);
          $('#empEditBtn').prop("disabled", true);
          $('#empDeleteBtn').prop("disabled", true);
        });
        $(document).on('change', '.empSelRadBtn', function () {
          $('#IdNew').val($(this).attr('data-id'));
          var empId = $('#IdNew').val();
          if(empId != null && empId != "" && empId != undefined){
            $('#empEditBtn').prop("disabled", false);
            $('#empDeleteBtn').prop("disabled", false);
          }
          else{
            $('#empEditBtn').prop("disabled", true);
            $('#empDeleteBtn').prop("disabled", true);
          }

          var name = $(this).attr('data-name');
          var role = $(this).attr('data-role');
          var desig = $(this).attr('data-desig');
          var dob = $(this).attr('data-dob');
          var phone = $(this).attr('data-phone');
          var gender = $(this).data('gender');
          var email = $(this).data('email');

          $('#EMPID').val($('#IdNew').val());
          $('#EmployeeName').val(name);
          $('#roleDropDwn').val(parseInt(role));
          $('#desigDrpDwn').val(parseInt(desig));
          $('#EmpDateOfBirth').val(dob);
          $('#EmpPhoneNumber').val(phone);
          $('#EmpEmailId').val(email);
          if("M" == gender){
            $('#Empgenderfemale').prop('checked', false);
            $('#Empgendermale').prop('checked', true);
          }
          else {
            $('#Empgenderfemale').prop('checked', true);
            $('#Empgendermale').prop('checked', false);
          }
        });
        $('#Empgendermale').change(function() {
          var checked = $(this).is(':checked');
          $('#Gender').val((checked)?'M':'F');
          $('#Empgenderfemale').prop('checked', !checked);
          $('#lblgender').hide();
        });
        $('#Empgenderfemale').change(function() {
          var checked = $(this).is(':checked');
          $('#Gender').val((checked)?'F':'M');
          $('#Empgendermale').prop('checked', !checked);
          $('#lblgender').hide();
        });
        $('#desigDrpDwn').on('change', function () {
          $('#DesigType').val($('#desigDrpDwn').val());
        });
        $('#roleDropDwn').on('change', function () {
          $('#Usertype').val($('#roleDropDwn').val());
        });
      });