  @extends('layouts.user-sidebar')
  <head>

      <!--   Core JS Files   -->
      <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
      <script src="{{ asset('js/bootstrap.min.js') }}"></script>
      <script src="{{ asset('js/moment.js') }}"></script>
      <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
      <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

      <script src="{{ asset('js/Helpers/Common.js') }}"></script>
      <script src="{{ asset('js/modernizr-2.8.3.js') }}"></script>
      <script src="{{ asset('js/Dashboard/constants.js') }}"></script>
      <script src="{{ asset('js/Dashboard/light-bootstrap-dashboard.js') }}"></script>
  </head>
  <body>
    @section('content')
    <div class="row">
      <div class="col-md-8">
          <div class="card">
          <div class="header">
              <h4 class="title" id="profileTitle">Profile</h4>
          </div>
          <div class="content">
              <form id="EmpProfileForm" class="form-horizontal" role="form" method="POST" action="/user/profile">
                  {{ csrf_field() }}
                  <div class="row">
                      <div class="col-md-5 text-center">
                        <label class="hrms-required-field">Employee ID</label>
                      </div>
                      <div class="col-md-5">
                        <input class="form-control input-field-readOnly" id = "EmpId" name="EmpId"
                              value='{{$employeeInfo->EMPID}}'></input>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-5 text-center">
                              <label class="hrms-required-field">Employee Name</label>
                      </div>
                      <div class="col-md-5">

                              <input class="form-control input-field-profile" id = "EmpFirstName"
                              placeholder = "Name" name="EmpFirstName"
                              value='{{$employeeInfo->name}}' required></input>
                              <label class="text-danger errLabel" id = "lblempFirstname">Enter name !!</label>

                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-5 text-center">
                              <label class="hrms-required-field">Email</label>
                      </div>
                      <div class="col-md-5">
                        <input class="form-control input-field-profile" id = "UserName" placeholder = "Username"
                            name = "UserName" value='{{$employeeInfo->email}}' required></input>
                        <label class="text-danger errLabel" id = "lblusername">Enter username !!</label>
                          </div>
                    </div>
                      <div class="row">
                        <div class="col-md-5 text-center">
                              <label class="hrms-required-field">Designation</label>
                          </div>
                          <div class="col-md-5">
                            <input class="form-control input-field-readOnly" id = "EmpDesig"
                            placeholder = "Designation" name="EmpDesig"
                            value='{{$employeeInfo->Designation}}'></input>
                          </div>
                      </div>
                  <div class="row">
                      <div class="col-md-5 text-center">
                              <label class="hrms-required-field">Date Of Birth</label>
                      </div>
                      <div class="col-md-5">
                        <input class="form-control input-field-profile" id = "EmpDateOfBirth" placeholder = "Date Of Birth"
                        value='{{$employeeInfo->EmpDob}}' name="EmpDateOfBirth" required></input>
                        <label class="text-danger errLabel" id = "lblempFirstname">Select Date Of Birth !!</label>
                      </div>
                      </div>
                      <div class="row">
                      <div class="col-md-5 text-center">
                              <label class="hrms-required-field">Gender</label>
                      </div>
                      <div class="col-md-5">
                        <input class="form-control input-field-profile-readOnly" id = "EmpGender"
                        placeholder = "Gender" name="EmpGender"
                        value='{{$employeeInfo->EmpGender}}'></input>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-5 text-center">
                              <label class="hrms-required-field">Phone Number</label>
                      </div>
                      <div class="col-md-5">
                        <input class="form-control input-field-profile" id = "PhoneNumber" placeholder = "Phone Number"
                          value='{{$employeeInfo->EmpPhone}}' name="PhoneNumber" required></input>
                        <label class="text-danger errLabel" id = "lblphonenumber">Enter Phone Number !!</label>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-md-6 hidden">
                          <div class="form-group">
                              <label class="hrms-required-field">Email ID</label>
                              <input class="form-control input-field-profile" id = "EmailId" placeholder = "Email Id"
                                value='{{$employeeInfo->email}}' name="EmailId" required></input>
                              <label class="text-danger errLabel" id = "lblEmailId">Enter Email Id !!</label>
                          </div>
                      </div>
                  </div>

                  <input type="button" class="btn btn-info btn-fill pull-right" value="Edit Profile" id="editBtn" onclick="editProfileClick()"/>
                  <input type="submit" class="btn btn-info btn-fill pull-right" value="Update Profile" style="display:none" id="updateBtn" />
                  <div class="clearfix"></div>
              </form>

          </div>
      </div>
    </div>
      <div class="col-md-4">
                        <input type="hidden" id="UserPhotoPath" name="UserPhotoPath"/>
                        <div class="card">
                            <div class="content">
                                <div class="row text-center">
                                    <div class="col-lg-12">
                                        <div id="effect-5" class="effects clearfix imgContainer">
                                            <div class="img avatar cursor" style="width:170px;height:150px;margin: 0 auto;">
                                                <input type="hidden" name="IsDefaultUserIcon" id="IsDefaultUserIcon"
                                                  value='{{($employeeInfo -> EmpPhotoPath)?'false':'true'}}'/>
                                                  <input type="hidden" name="userPhoto" id="userPhoto"
                                                    value='{{($employeeInfo -> EmpPhotoPath)?$employeeInfo -> EmpPhotoPath:'/images/avatar.png'}}'/>
                                                <img id="userAvatar" width="170" height="150"
                                                  src='{{($employeeInfo -> EmpPhotoPath)?$employeeInfo->EmpPhotoPath:asset('/images/avatar.png')}}' />
                                                <input id="userPhotoInput" type="file" accept="image/gif,image/jpeg,image/jpg,image/png" name="yourinputname"
                                                       style="display: none;" onchange="fileCheck(this);" />

                                                <div class="overlay" style="height:100%;width:100%;display:table">
                                                    <div class="row" style="display:table-cell;vertical-align:middle">
                                                        <div class="col-lg-12">
                                                            <span class="text-center">
                                                                <a class="button" id="usrAvatarExpndBtn" href="javascript:void(0)">
                                                                    <i class="fa fa-upload fa-lg" aria-hidden="true"></i>&nbsp;  Change Photo
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <div class="col-lg-12 hidden">
                                                            <span class="text-center">
                                                                <a class="button" id="TakePhotoBtn" onclick="takePhotoClick()" href="javascript:void(0)">
                                                                    <i class="fa fa-camera fa-lg" aria-hidden="true"></i>&nbsp;  Take Photo
                                                                </a>
                                                            </span>
                                                        </div>
                                                        <br />
                                                        <div class="col-lg-12 hidden">
                                                            <span class="text-center">
                                                                <a class="button" id="removePhotoBtn" onclick="removePhotoClick()" href="javascript:void(0)">
                                                                    <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>&nbsp;  Remove Photo
                                                                </a>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br />
                                    <h3 class="title text-center" id="empNameDisp">{{$employeeInfo->name}}</h3>
                                </div>
                                <br />
                                <div class="row text-center" id="saveDiv" style="display:none">
                                    <div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 text-center">
                                        <label class="h5">Save Changes?</label>

                                        <button class="btn btn-primary btn-sm btn-fill" type="button" height="35" width="35" title="Save"
                                                onclick="ChangeUserPhoto(false)" style="border-radius:5px;border:none">
                                            <i class="fa fa-check fa-2x" aria-hidden="true"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm btn-fill" type="button" height="35" width="35" title="Cancel"
                                                onclick="ChangeUserPhoto(true)" style="border-radius:5px;border:none">
                                            <i class="fa fa-times fa-2x" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              </div>          
      @endsection
  </body>
  </html>
  <script src="{{asset('js/app/user/profile.js')}}"></script>
