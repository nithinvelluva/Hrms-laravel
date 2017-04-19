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
    <div class="card">
        <div class="header">
            <h3 class="title">Apply Leave</h3>
        </div>
        <div id="LoadPage" class="waitIconDiv col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
            <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="50" height="50" id="imgProg" />
        </div>
        <div class="content lvApplyContent">
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <label class="control-label labelClass hrms-required-field">Leave Type</label>
                </div>
                <div class="col-md-8 col-lg-5">
                  <select class="form-control input-field" id="LeaveTypeDropDown" name="LeaveTypeDropDown">
                      <option value="">Select Leave Type</option>
                      @foreach ($leavetypes as $leavetype)
                      <option value='{{ $leavetype -> Id }}'>{{ $leavetype -> Type }}</option>
                      @endforeach
                  </select>
                  <label class="text-danger errLabel" id = "ErrLvType">Please Select Leave Type !!</label>
              </div>
          </div>
          <br />
          <div class="row">
            <div class="col-md-3 col-lg-3">
                <label class="control-label labelClass">Leave Balance</label>
            </div>
            <div class="col-md-8 col-lg-8">
                <label class="control-label  labelClass" id = "leaveBalLabel">0.0</label>
                <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' style="height:30px;width:30px;display:none"
                id="LvStatiImgProg" />
                <i class="fa fa-info-circle fa-2x cursor" aria-hidden="true" data-toggle="modal" data-target="#calendarDiv"
                title="View Leave Details"
                onclick="GetLeaveStatistics(true,null)"></i>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <label class="control-label labelClass hrms-required-field">From Date    </label>
            </div>
            <div class="col-md-8 col-lg-5">
                <input type="text" class="form-control inputbox from-date-picker input-field" placeholder="Select From Date" id="Fromdatetimepicker" required>
                <label class="text-danger errLabel" id = "Errsdate">Please Select From date !!</label>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <label class="control-label labelClass hrms-required-field">To Date    </label>
            </div>
            <div class="col-md-8 col-lg-5">
                <input type="text" class="form-control inputbox to-date-picker1 input-field" placeholder="Select To Date" id="Todatetimepicker" required>
                <label class="text-danger errLabel" id = "Errenddate">Please Select Todate !!</label>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-3 col-lg-3">
                <label class="control-label labelClass hrms-required-field">Duration</label>
            </div>
            <div class="col-md-8 col-lg-5">
              <select class="form-control input-field" id="LeaveDurationDropDown" name="LeaveDurationDropDown">
                  <option value="">Select Leave Duration</option>
                  <option value="1">Full Day</option>
                  <option value="2">Half Day</option>
              </select>
              <label class="text-danger errLabel" id = "ErrLvDur">Please Select Leave Duration !!</label>
          </div>
      </div>
      <br />
      <div class="row">
        <div class="col-md-3 col-lg-3">
            <label class="control-label labelClass">Notes  </label>
        </div>
        <div class="col-md-8 col-lg-5">
            <textarea class="form-control" id="notes" placeholder="" maxlength="3992" style="height:100px"></textarea>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-12 col-lg-12 cursor">
            <input type="button" class="btn btn-info btn-fill pull-right" value="Apply" id="applyBtn" onclick="ApplyLeave()" />
        </div>
    </div>
</div>
</div>

<div class="modal fade col-xs-12 col-md-12 col-lg-12 col-sm-12" id="LeaveStatiDiv" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="height:auto">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title text-center">Leave Details</h3>
            </div>

            <div class="modal-body" style="min-height:150px">
                <div class="row" id="statiDiv">
                    <div id="LoadPage1" class="text-center col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                        <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="40" height="40" id="imgProg1" />
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <label class="control-label" id = "leaveStatLabel"></label>
                    </div>
                    <table id="LvStatiTable" class="gridTableAtt cell-border hover display responsive" cellspacing="0" style="width:90%">
                        <thead class="tableHeader">
                            <tr>
                                <th>Leave Type</th>
                                <th>Entitled</th>
                                <th>Taken</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</body>
<script src="{{asset('js/app/user/leave.js')}}"></script>
