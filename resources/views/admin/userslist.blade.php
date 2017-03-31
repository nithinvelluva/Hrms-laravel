@extends('layouts.admindashboard')
<head>
    <title>Employees</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <link href="{{asset('css/jquery.dataTables.min.css')}}" rel="stylesheet" />
    <link href="{{asset('css/responsive.dataTables.min.css')}}" rel="stylesheet" />
</head>
<body>
  @section('content')
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="row users-list-header">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
                  <a href='{{url('/admin/addemployee')}}' class="pull-left">
                    <button class="btn btn-primary btn-fill" type="button">
                        <i class="fa fa-user-plus" aria-hidden="true"></i>
                    </button>
                  </a>
                  <span class="input-group-btn" style="padding-left:10px">
                      <button class="btn btn-default btn-fill" type="button" id="empEditBtn"
                        onclick="EditEmployee()" style="border-radius: 5px;" disabled>
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                      </button>

                      <button class="btn btn-danger btn-fill" type="button" id="empDeleteBtn" onclick="event.preventDefault();
                               document.getElementById('empdelete-form').submit();"
                        onclick="DeleteEmployee()" style="border-radius: 5px;margin-left:10px" disabled>
                        <i class="fa fa-trash" aria-hidden="true"></i>
                      </button>
                      <form id="empdelete-form" action='/admin/removeemployee' method="POST" style="display: none;">
                          <input type="hidden" id="IdNew" name="IdNew"/>
                          {{ csrf_field() }}
                      </form>
                  </span>
                </div>
            </div>
            <br></br>
            <div class="row user-list">
                <div class="text-center col-md-12">
                  <table id="employeeTable" class="table-condensed table-striped table-hover display nowrap responsive"
                      cell-spacing="1" width="100%">
                    <thead class="tableHeader">
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>PhoneNumber</th>
                            <th>Designation</th>
                            <th>Role</th>
                            <th>Dob</th>
                        </tr>
                      </thead>
                      <tbody class="text-center">
                        @foreach ($users as $user)
                          <tr>
                            <td><input type="radio" class="empSelRadBtn" name="empSelRadBtn" data-id="{{ $user -> id }}"
                              data-name="{{ $user -> name }}" data-email="{{ $user -> email }}" data-phone="{{ $user -> EmpPhone }}"
                              data-desig="{{ $user -> DesigId }}" data-role="{{ $user -> RoleId }}" data-dob="{{ $user -> EmpDob}}"
                              data-gender="{{ $user -> EmpGender}}"></input>
                            </td>
                            <td>{{$user -> name}}</td>
                            <td>{{$user -> email}}</td>
                            <td>{{$user -> EmpPhone}}</td>
                            <td>{{$user -> Designation}}</td>
                            <td>{{$user -> UserRole}}</td>
                            <td>{{$user -> EmpDob}}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="modal fade" id="editEmpModal" role="dialog">
              <div class="modal-dialog">
                <div class="modal-content hrms-modal-height">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h3 class="text-center">Edit Employee</h3>
                    </div>
                    <div class="modal-body">
                      <form id="editEmpform" method="post" action="/admin/editemployee">
                           {{ csrf_field() }}
                             <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                          <label class="labelclass">Employee Id</label>
                                          <input id="EMPID" name="EMPID" type="text" class="form-control" disabled/>
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                          <label class="labelclass hrms-required-field">Employee name</label>
                                          <input class="form-control inputField" id="EmployeeName" name="EmpName"
                                            placeholder = "Employee name" required maxlength = 20></input>
                                          <label class="text-danger errLabel" id="lblempname">Enter Employee Name</label>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <input id="Gender" name="Gender" type="hidden" value="" />
                                     <div class="form-group">
                                         <label class="labelclass hrms-required-field">Gender</label>
                                         <div class="row">
                                             <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4">
                                                  <input type="radio" id="Empgendermale"></input><label>&nbsp;Male</input>
                                             </div>
                                             <div class="col-lg-6 col-md-4 col-sm-4 col-xs-4">
                                                  <input type="radio" id="Empgenderfemale"></input><label>&nbsp;Female</input>
                                             </div>
                                              <label class="text-danger errLabel" id="lblgender">Select Gender</label>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                          <input id="Usertype" name="Usertype" type="hidden" value="" />
                                          <label class="labelclass hrms-required-field">User role</label>
                                          <select class="form-control inputField" id="roleDropDwn" name="UserRole" required>
                                              <option value="">Select Role</option>
                                              <option value="1">ADMIN</option>
                                              <option value="2">USER</option>
                                          </select>
                                          <label class="text-danger errLabel" id="lbluserrole">Select User Role</label>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                          <input id="DesigType" name="DesigType" type="hidden" value="" />
                                          <label class="labelclass hrms-required-field">Designation</label>
                                          <select class="form-control inputField" id="desigDrpDwn" name="EmpDesig" required>
                                              <option value="">Select Designation</option>
                                              <option value="1">HR</option>
                                              <option value="2">SOFTWARE ENGINEER</option>
                                          </select>
                                         <label class="text-danger errLabel" id="lblempDesig">Select Employee designation</label>
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                          <label class="labelclass hrms-required-field">Date Of Birth</label>
                                          <input type="text" class="form-control inputField" id="EmpDateOfBirth" name="EmpDateOfBirth"
                                            placeholder="Date of birth" maxlength = 15 required></input>
                                          <label class="text-danger errLabel" id="ErrlblDateOfBirth">Select Date of birth</label>
                                     </div>
                                 </div>
                             </div>
                             <div class="row">
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                          <label class="labelclass hrms-required-field" for="Phone_number">Phone number</label>
                                          <input class="form-control inputField" id="EmpPhoneNumber" maxlength="10"
                                          name="PhoneNumber" placeholder="1234567890" type="text" value="" required />
                                          <label class="text-danger errLabel" id="lblphonenumber">Enter Phonenumber</label>
                                     </div>
                                 </div>
                                 <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                     <div class="form-group">
                                       <label class="labelclass hrms-required-field">Email id</label>
                                        <input class="form-control inputField" id="EmpEmailId" name="EmailId"
                                        placeholder="name@domain.com" required type="text" value="" />
                                        <label class="text-danger errLabel" id="lblEmailId">Enter Email Id</label>
                                     </div>
                                 </div>
                             </div>
                             <div class="modal-footer">
                                 <input type="submit" value="Save" class="btn btn-primary btn-fill" id="EmpDetSubmitBtn" />
                                 <button class="btn btn-danger btn-fill" type="button" id="empDeleteBtnSep"
                                          style="border-radius: 5px;height:40px;width:60px">
                                   <i class="fa fa-trash" aria-hidden="true"></i>
                                 </button>
                             </div>
                         </form>
                    </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</body>
@endsection
</html>
<!--   Core JS Files   -->
<script src="{{asset('js/jquery-3.2.0.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/dataTables.responsive.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/Helpers/Common.js')}}" type="text/javascript"></script>
<script src="{{asset('js/Dashboard/constants.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery-ui-1.11.4.min.js')}}" type="text/javascript"></script>
<script>
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
</script>
