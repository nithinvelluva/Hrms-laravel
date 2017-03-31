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
                    <i class="fa fa-info-circle fa-2x cursor" aria-hidden="true" data-toggle="modal" data-target="#LeaveStatiDiv"
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
<script>
var LeaveStatiTable;
function showLoad() {
    $("#imgProg1").show();
    $("#LvStatiTable").addClass("disablediv");
}
function HideLoad() {
    $("#imgProg1").hide();
    $("#LvStatiTable").removeClass("disablediv");
}
function ResetValues(){
    $('#leaveBalLabel').text("0.0");
    $('#LeaveTypeDropDown').val('');
    $('#LvStatiImgProg').hide();
    $('#Fromdatetimepicker').val('');
    $('#Todatetimepicker').val('');
    $('#LeaveDurationDropDown').empty();
    var OptHtml = '<option value="' + 0 + '">' + "Select Leave Duration" + '</option>';
    $('#LeaveDurationDropDown').append(OptHtml);
    $('#notes').val('');
    $('.waitIconDiv').hide();
}
function GetLeaveStatistics(view,leaveType) {
    showLoad();
    var currentDate = new Date();
    var date = currentDate.toDateString();
    $('#leaveStatLabel').text("Showing leave status as of : " + date);

    var leaveStatistics = {};
    leaveStatistics.url = "/user/GetLeaveStatistics";
    leaveStatistics.type = "POST";
    leaveStatistics.datatype = "json";
    leaveStatistics.contentType = "application/json";
    leaveStatistics.complete = function (data) {
      $('#LvStatiImgProg').hide();
      HideLoad();
    };
    leaveStatistics.success = function (data) {
      if(data){
        var response = data.leaveData[0];
        if(view)
        {
            LeaveStatiTable.clear().draw();
            LeaveStatiTable.row.add({
                0: "Casual",
                1: 30,
                2: 30 - response.CasualLeave,
                3: response.CasualLeave
            }).draw();
            LeaveStatiTable.row.add({
                0: "Festive",
                1: 9,
                2: 9 - response.FestiveLeave,
                3: response.FestiveLeave
            }).draw();
            LeaveStatiTable.row.add({
                0: "Sick",
                1: 14,
                2: 14 - response.SickLeave,
                3: response.SickLeave
            }).draw();
        }
        else{
            switch (leaveType)
            {
                case "Casual":
                    $('#leaveBalLabel').text(response.CasualLeave);
                    break;
                case "Festive":
                    $('#leaveBalLabel').text(response.FestiveLeave);
                    break;
                case "Sick":
                    $('#leaveBalLabel').text(response.SickLeave);
                    break;
                default:
                    break;
            }
        }
      }
    };
    leaveStatistics.error = function () {
    };
    $.ajax(leaveStatistics);
}
function ApplyLeave(){
    clearErrors();
    var LvType = $('#LeaveTypeDropDown').val();
    var FromDate = $('#Fromdatetimepicker').val();
    var ToDate = $('#Todatetimepicker').val();
    var LvDur = $('#LeaveDurationDropDown').val();
    var Notes = $('#notes').val();
    var flag = false;
    if(LvType <= 0)
    {
        $('#LeaveTypeDropDown').css('border-color', 'red');
        $('#ErrLvType').show();
        flag = true;
    }
    if(LvDur <= 0)
    {
        $('#LeaveDurationDropDown').css('border-color', 'red');
        $('#ErrLvDur').show();
        flag = true;
    }
    if (stringIsNull(FromDate)) {
        $('#Fromdatetimepicker').css('border-color', 'red');
        $('#Errsdate').show();
        flag = true;
    }
    if (stringIsNull(ToDate)) {
        $('#Todatetimepicker').css('border-color', 'red');
        $('#Errenddate').show();
        flag = true;
    }
    if(!flag)
    {
        showLoadreport("#imgProg",".lvApplyContent");
        var StrLvTyp = $('#LeaveTypeDropDown option:selected').text();
        var LvDurStr = $('#LeaveDurationDropDown option:selected').text();
        var AddLeave = {};
        AddLeave.url = "/user/AddLeave";
        AddLeave.type = "POST";
        AddLeave.data = JSON.stringify({leaveId:null,fromDate:FromDate,toDate:ToDate,
                                        leaveType:LvType,leaveDurType:LvDur,
                                        comments:Notes,LvTypStr:StrLvTyp,lvDurTypStr:LvDurStr,isCancel:false });
        AddLeave.datatype = "json";
        AddLeave.contentType = "application/json";
        AddLeave.success = function (status) {

            HideLoadreport("#imgProg",".lvApplyContent");
            var response = status.returnData;
            if(stringIsNull(response)){}
            else if(response == "OK"){
                ResetValues();
                clearErrors();
                showMessageBox(SUCCESS, "Saved Successfully");
            }
            else if(response == "EXISTS"){
                showMessageBox(WARNING, "An Entry Exists In Selected Date Range !!");
            }
            else if(response == "ERROR"){
                showMessageBox(ERROR, "An Unexpected Error Occured!!");
            }
            // else if(response == "1"){
            //     $('#ErrLvType').show();
            //     $('#LeaveTypeDropDown').css('border-color', 'red');
            // }
            // else if(response == "2"){
            //     $('#Errsdate').show();
            //     $('#Fromdatetimepicker').css('border-color', 'red');
            // }
            // else if(response == "3"){
            //     $('#Errenddate').text("Please Select Todate !!");
            //     $('#Errenddate').show();
            //     $('#Todatetimepicker').css('border-color', 'red');
            // }
            else if(response == "4"){
                $('#Errenddate').text("Todate must be greater than equal to Fromdate !!");
                $('#Errenddate').show();
                $('#Todatetimepicker').css('border-color', 'red');
            }
            // else if(response == "5"){
            //     $('#ErrLvDur').show();
            //     $('#LeaveDurationDropDown').css('border-color', 'red');
            // }
            else if(response == "6"){
                showMessageBox(WARNING, "No Enough Leaves !!");
            }
        };
        AddLeave.error = function (data) {

            HideLoadreport("#imgProg",".lvApplyContent");
        };
        $.ajax(AddLeave);
    }
}
    $(document).ready(function () {
        navigateTo(LEAVES );

        LeaveStatiTable = $('#LvStatiTable').DataTable({ "pageLength": 5, "bFilter": false,
        "bInfo": false,"bPaginate": false, "bLengthChange": false, "ordering": false,
        "searching": false,responsive: true });
        $('#LvStatiImgProg').hide();
        var response = "";
        var LeaveDurList = [{"Value":1,"Text":"Full Day"},{"Value":2,"Text":"Half Day"}];

        /*Handling Validation Errors..*/
        $('#LeaveTypeDropDown').on('change',function(){
        var value = $('#LeaveTypeDropDown').val();
        if(value > 0){
            $('#ErrLvType').hide();
            $('#LeaveTypeDropDown').css('border-color', '');
            $('#LvStatiImgProg').show();
            $('#leaveBalLabel').text('0.0');
            var LeaveTypeStr = $('#LeaveTypeDropDown option:selected').text().trim();
            var flag = true;
            if(LeaveTypeStr == "Festive")
            {
                flag = false;
            }
            GetLeaveStatistics(false,LeaveTypeStr);

            try
            {
                $('#LeaveDurationDropDown').empty();
                var OptHtml = '<option value="' + 0 + '">' + "Select Leave Duration" + '</option>';
                $('#LeaveDurationDropDown').append(OptHtml);

                if(flag)
                {
                    $.each(LeaveDurList, function(i, option) {
                        $('#LeaveDurationDropDown').append($('<option/>').attr("value",option.Value).text(option.Text));
                    });
                }
                else{
                    $.each(LeaveDurList, function(i, option) {
                        if(option.Value == 1)
                        {
                            $('#LeaveDurationDropDown').append($('<option/>').attr("value",option.Value).text(option.Text));
                        }
                    });
                }
            }
            catch(ex)
            {
            }
        }
        else{
            $('#ErrLvType').show();
            $('#LeaveTypeDropDown').css('border-color', 'red');
            $('#leaveBalLabel').text("0.0");
            $('#LvStatiImgProg').hide();
        }
    });
        $('#LeaveDurationDropDown').on('change',function(){
        if(stringIsNull($('#LeaveDurationDropDown').val())
            || $('#LeaveDurationDropDown').val() < 0)
        {
            $('#LeaveDurationDropDown').css('border-color', 'red');
            $('#ErrLvDur').show();
        }
        else
        {
            $('#LeaveDurationDropDown').css('border-color', '');
            $('#ErrLvDur').hide();
        }
    });
        $('#Fromdatetimepicker').on('change blur keyup paste', function() {
        if(this.value.toString().length < 0)
        {
            $('#Fromdatetimepicker').css('border-color', 'red');
            $('#Errsdate').show();
        }
        else
        {
            $('#Fromdatetimepicker').css('border-color', '');
            $('#Errsdate').hide();
        }
    });
        $('#Todatetimepicker').on('change blur keyup paste', function() {
        if(this.value.toString().length < 0)
        {
            $('#Todatetimepicker').css('border-color', 'red');
            $('#Errenddate').text("Please Select Todate !!");
            $('#Errenddate').show();
        }
        else
        {
            $('#Todatetimepicker').css('border-color', '');
            $('#Errenddate').hide();
        }
    });
    });
</script>
