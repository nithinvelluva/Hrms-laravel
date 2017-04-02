@extends('layouts.admindashboard')
<head>
  <title>Add employee</title>
</head>
<body>
  @section('content')
  <div class="container">
    <div class="row" style="border-color:#000">
      <div class="login-card">
        <div class="modal-header">
          <h3 class="text-center">Add Employee</h3>
        </div>
        <div class="modal-body">
         <form id="addEmpform" method="post" action="/admin/addemployee">
           {{ csrf_field() }}
           <div class="row" id="EmpIdRow">
            <input id="IDNew" name="IDNew" type="hidden" value="" />
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
             <div class="form-group">
              <label class="labelclass"></label>

            </div>
          </div>
        </div>
        <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
           <div class="form-group">
            <label class="labelclass hrms-required-field">Employee name</label>
            <input class="form-control inputField" id="EmpName" name="EmpName" placeholder = "Employee name" required maxlength = 20></input>
            <label class="text-danger errLabel" id="lblempname">Enter Employee Name</label>
          </div>
        </div>
      </div>
      <div class="row">
        <input id="Gender" name="Gender" type="hidden" value="" />
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
         <div class="form-group">
          <label class="labelclass hrms-required-field">Gender</label>
          <div class="row">
           <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <label><input type="radio" id="Empgendermale"></input>&nbsp;Male</label>
          </div>
          <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <label><input type="radio" id="Empgenderfemale"></input>&nbsp;Female</label>
          </div>
          <label class="text-danger errLabel" id="lblgender">Select Gender</label>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
     <div class="form-group">
      <input id="Usertype" name="Usertype" type="hidden" value="" />
      <label class="labelclass hrms-required-field">User role</label>
      <select class="form-control inputField" id="roleDropDwn" name="UserRole" required>
        <option value="">Select Role</option>
        @foreach ($rolelist as $roleitem)
        <option value='{{ $roleitem -> id }}'>{{ $roleitem -> role }}</option>
        @endforeach
      </select>
      <label class="text-danger errLabel" id="lbluserrole">Select User Role</label>
    </div>
  </div>
</div>
<div class="row">
 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   <div class="form-group">
    <input id="DesigType" name="DesigType" type="hidden" value="" />
    <label class="labelclass hrms-required-field">Designation</label>
    <select class="form-control inputField" id="desigDrpDwn" name="EmpDesig" required>
      <option value="">Select Designation</option>
      @foreach ($desiglist as $desigitem)
      <option value='{{ $desigitem -> ID }}'>{{ $desigitem -> Designation }}</option>
      @endforeach
    </select>
    <label class="text-danger errLabel" id="lblempDesig">Select Employee designation</label>
  </div>
</div>
</div>
<div class="row">
 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   <div class="form-group">
    <label class="labelclass hrms-required-field">Date Of Birth</label>
    <input type="text" class="form-control inputField" id="EmpDateOfBirth" name="EmpDateOfBirth"
    placeholder="Date of birth" maxlength = 15 required></input>
    <label class="text-danger errLabel" id="ErrlblDateOfBirth">Select Date of birth</label>
  </div>
</div>
</div>
<div class="row">
 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
   <div class="form-group">
    <label class="labelclass hrms-required-field" for="Phone_number">Phone number</label>
    <input class="form-control inputField" id="EmpPhoneNumber" maxlength="10"
    name="PhoneNumber" placeholder="1234567890" type="text" value="" required />
    <label class="text-danger errLabel" id="lblphonenumber">Enter Phonenumber</label>
  </div>
</div>
</div>
<div class="row">
 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
</div>
</form>
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
<script src="{{asset('js/Helpers/Common.js')}}" type="text/javascript"></script>
<script src="{{asset('js/Dashboard/constants.js')}}" type="text/javascript"></script>
<script src="{{asset('js/jquery-ui-1.11.4.min.js')}}" type="text/javascript"></script>
<script src="{{asset('js/app/admin/addemployee.js')}}" type="text/javascript"></script>
