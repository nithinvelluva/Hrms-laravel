
@extends('layouts.user-sidebar')
<head>
    <!--   Core JS Files   -->
    <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.11.4.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

    <script src="{{ asset('js/Helpers/Common.js') }}"></script>
    <script src="{{ asset('js/modernizr-2.8.3.js') }}"></script>
    <script src="{{ asset('js/Dashboard/constants.js') }}"></script>
    <script src="{{ asset('js/Dashboard/light-bootstrap-dashboard.js') }}"></script>
</head>
<body>
    @section('content')
    <div class="modal fade col-xs-12 col-md-12 col-lg-12 col-sm-12" id="EditLeaveModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title text-center">Leave Details</h3>
                </div>
                <div id="LoadPageLvEdit" class="row text-center waitIconDivRprt" style="display:none">
                    <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="50" height="50" id="imgProgLvEdit" />
                </div>
                <div class="modal-body">
                    <div class="lvEditView">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="hrms-required-field">From Date</h5>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="LvstartDateLabel" placeholder="From Date" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="hrms-required-field">To Date</h5>
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control" id="LvtoDateLabel" placeholder="To Date" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="top-margin">Leave Status</h5>
                            </div>
                            <div class="col-lg-6 top-margin">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <label id="leaveStatusLabel"></label>
                                    </div>
                                    <div class="col-lg-6" id="lv_cancel_div">
                                        <input type="checkbox" id="lv_cancel_chkBx" disabled="disabled" title="Cancel Leave" /><label>Cancel</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="hrms-required-field">Leave Type</h5>
                            </div>
                            <div class="col-lg-6">
                              <select class="form-control input-field LvdropDown" id="leaveTypeLabel" name="leaveTypeLabel">
                                  <option value="">Select Leave Type</option>
                                  @foreach ($leavetypes as $leavetype)
                                    <option value='{{ $leavetype -> Id }}'>{{ $leavetype -> Type }}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="hrms-required-field">Duration Type</h5>
                            </div>
                            <div class="col-lg-6">
                              <select class="form-control input-field LvdropDown" id="LvDurTypLabel" name="LvDurTypLabel">
                                  <option value="">Select Leave Duration</option>
                                  <option value="1">Full Day</option>
                                  <option value="2">Half Day</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <h5>Comments</h5>
                            </div>
                            <div class="col-lg-6">
                                <textarea id="cmntsArea" class="form-control" placeholder="Comments"></textarea>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row modal-footer">
                        <div class="col-lg-12 text-center">
                            <input type="submit" value="Edit" class="btn btn-info btn-fill" id="LeaveEditBtn" onclick="LeaveEditClick()" />
                            <input type="button" value="Update" style="display:none" id="LeaveUpdateBtn" class="btn btn-info btn-fill" onclick="LeaveUpdateClick()" />
                            <i class="fa fa-envelope fa-2x cursor" aria-hidden="true" id="cntctAdmin" data-toggle="modal" data-target="#SentLeaveQueryModal"
                               title="Contact Admin"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade col-xs-12 col-md-12 col-lg-12 col-sm-12" id="SentLeaveQueryModal" role="dialog">
       <div class="modal-dialog">
           <!-- Modal content-->
           <div class="modal-content">
               <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                   <h3 class="modal-title text-center">Sent Query</h3>
               </div>
               <div id="LoadPageLvQuery" class="row text-center waitIconDivRprt" style="display:none">
                   <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="50" height="50" id="imgProgQuery" />
               </div>
               <div class="modal-body" id="contactAdminDiv">

      <div class="row hidden">
          <div class="col-lg-4">
              <h5>From</h5>
          </div>
          <div class="col-lg-8">
              <input type="text" id="senterEmail" class="form-control" readonly />
          </div>
      </div>
      <div class="row">
          <div class="col-lg-4">
              <h5>Subject</h5>
          </div>
          <div class="col-lg-8">
              <input type="text" class="form-control" id="Emailsubject" />
          </div>
      </div>
      <div class="row">
          <div class="col-lg-4">
              <h5>Message</h5>
          </div>
          <div class="col-lg-8">
              <textarea id="EmailBody" class="form-control txt_area"></textarea>
          </div>
      </div>

      <div class="modal-footer">
          <div class="col-lg-12 col-xs-12 col-md-12 col-sm-12 text-center">
              <i class="fa fa-paper-plane fa-2x cursor" aria-hidden="true" id="sentQuery" onclick="SentQuery()" title="Sent"></i>
          </div>
      </div>

               </div>
           </div>
       </div>
   </div>

    <div class="card">
        <div class="header">
            <h4 class="title" id="profileTitle">Leave Reports</h4>
        </div>
        <div class="content">
            <input type="hidden" id="LeaveId" name="LeaveId"/>
            <div class="row text-center">
                <div class="col-lg-6">
                    <h5 id="monthLabel"></h5>
                </div>
                <div class="col-lg-6">
                    <h5 id="dateLabel"></h5>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div id="custom-search-input" class="input-field">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Select Date" id="chngDatePicker" autocomplete="off" />
                            <span class="input-group-btn">
                                <button class="btn btn-info btn-lg" type="button" id="goBtn" onclick="ChngClick()">
                                    <i class="fa fa-search fa-lg"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <label id = "lblChnDate" class = "text-danger errLabel">Select a date !!</label>
                </div>
            </div>
            <br />
            <div class="row text-center waitIconDiv" id="LoadIconDiv">
                <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="50" height="50" id="imgProgLvRprt" />
            </div>
            <div class="row text-center">
                <label id = "lblLvNoResults" class = "text-danger">No Records Found !!</label>
            </div>
            <div class="row TableDivLvRprt text-center" style="display:none">
                <table id="LvReprtTable" class="display nowrap responsive" cellspacing="0" style="width:95%;">
                    <thead class="tableHeader">
                        <tr>
                            <th>From Date</th>
                            <th>To Date</th>
                            <th>Leave Type</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-center"></tbody>
                </table>
            </div>
        </div>
    </div>
</body>
@endsection

<script>
    var currYear;
    var currMonth;
    var IsCancel = false;
    var LeaveDetailsTable;
    var LeaveDurList = [{"Value":1,"Text":"Full Day"},{"Value":2,"Text":"Half Day"}];
    var LeaveTypList = [{"Value":1,"Text":"Casual"},{"Value":2,"Text":"Festive"},{"Value":3,"Text":"Sick"}];
    $(document).ready(function () {
      navigateTo(LEAVEREPORT);
      LeaveDetailsTable = $('#LvReprtTable').DataTable({
         "pageLength": 7, "bFilter": true,
         "bInfo": true, "bLengthChange": false,
         "ordering": true, "searching": false
      });
    });
    function LeaveEditClick() {
        $('#LeaveEditBtn').hide();
        $('#LeaveUpdateBtn').show();
        EnableLeaveFields();
    }
    function LeaveUpdateClick() {
        var leaveID = $('#LeaveId').val();
        var strtDate = $('#LvstartDateLabel').val();
        var toDate = $('#LvtoDateLabel').val();
        var comments = $('#cmntsArea').val();
        var lvTypInt = $('#leaveTypeLabel').val();
        var lvtypstr = $('#leaveTypeLabel option:selected').text();
        var lvdurtyp = $('#LvDurTypLabel').val();
        var lvdurtypstr = $('#LvDurTypLabel option:selected').text();
        IsCancel = $('#lv_cancel_chkBx').is(':checked');

        var validFlag = false;
        if (stringIsNull(strtDate)) {
            validFlag = true;
        }
        if (stringIsNull(toDate)) {
            validFlag = true;
        }
        if (!validFlag) {
            showLoadreport("#LoadPageLvEdit", ".lvEditView");
            var UpdateLeaveDetails = {};
            UpdateLeaveDetails.url = "/user/AddLeave";
            UpdateLeaveDetails.type = "POST";
            UpdateLeaveDetails.data = JSON.stringify({ leaveId: leaveID, fromDate: strtDate, toDate: toDate,
                                      leaveType: lvTypInt, leaveDurType: lvdurtyp, comments: comments, LvTypStr: lvtypstr,
                                      lvDurTypStr: lvdurtypstr, isCancel: IsCancel });
            UpdateLeaveDetails.datatype = "json";
            UpdateLeaveDetails.contentType = "application/json";

            UpdateLeaveDetails.success = function (status) {
                HideLoadreport("#LoadPageLvEdit", ".lvEditView");
                if (!stringIsNull(status)) {
                    var response = status.returnData;
                    if ("OK" == response) {
                        $('#EditLeaveModal').modal('toggle');
                        showMessageBox(SUCCESS, "Saved Successfully");
                        $('#LeaveEditBtn').show();
                        $('#LeaveUpdateBtn').hide();
                        DisableLeaveFields();
                        PopulateLeaveReportTable(currMonth, currYear, false);
                        if (IsCancel) {
                            document.getElementById("leaveStatusLabel").style.color = '#ff0000';
                            $('#leaveStatusLabel').text("Cancelled");
                            $('#lv_cancel_div').hide();
                            $('#LeaveEditBtn').hide();
                        }
                    }
                    else if ("EXISTS" == response) {
                        showMessageBox(WARNING, "An Entry Exists In Selected Date Range !!");
                    }
                    else if ("ERROR" == response) {
                        showMessageBox(ERROR, "An Unexpected Error Occured !!");
                    }
                    else if ("1" == response || "2" == response || "3" == response || "5" == response) {
                        showMessageBox(WARNING, "Select Mandatory Fields !!");
                    }
                    else if ("4" == response) {
                        showMessageBox(WARNING, "To Date Must Greater than equal to From Date !!");
                    }
                    else if ("6" == response) {
                        showMessageBox(WARNING, "Only 9 Festive leaves can be availed per year !!");
                    }
                    else if (response == "7") {
                        showMessageBox(WARNING, "No Enough Leaves !!");
                    }
                }
            };
            UpdateLeaveDetails.error = function () {
                HideLoadreport("#LoadPageLvEdit", ".lvEditView")
                showMessageBox(ERROR, "An Unexpected Error Occured !!");
            };
            $.ajax(UpdateLeaveDetails);
        }
        else {
            showMessageBox(WARNING, "Select Mandatory Fields !!");
        }
    }
    function EnableLeaveFields() {
        $('#LvstartDateLabel').removeAttr("disabled");
        $('#LvtoDateLabel').removeAttr("disabled");
        $('#cmntsArea').removeAttr("disabled");
        $('#leaveTypeLabel').removeAttr("disabled");
        $('#LvDurTypLabel').removeAttr("disabled");
        $('#lv_cancel_chkBx').removeAttr("disabled");
    }
    function DisableLeaveFields() {
        $('#LvstartDateLabel').attr('disabled', 'disabled');
        $('#LvtoDateLabel').attr('disabled', 'disabled');
        $('#cmntsArea').attr('disabled', 'disabled');
        $('#leaveTypeLabel').attr('disabled', 'disabled');
        $('#LvDurTypLabel').attr('disabled', 'disabled');
        $('#lv_cancel_chkBx').attr('disabled', 'disabled');
    }
    function ChngClick() {
        clearErrors();
        if (stringIsNull($("#chngDatePicker").val())) {
            $('#custom-search-input').css('border-color', 'red');
            $('#lblChnDate').text("Select a date !!");
            $('#lblChnDate').show();
        }
        else {
            $('#imgProgLvRprt').show();
            setLeaveReportsInfo(false);
            PopulateLeaveReportTable();
        }
    }
    $(document).on('click', '.ViewLeaveDetails', function () {
        DisableLeaveFields();
        $('#lv_cancel_chkBx').prop('checked', false);
        $('#LeaveId').val($(this).attr('data-id'));
        $('#LeaveUpdateBtn').hide();
        $('#cntctAdmin').hide();
        var isEdit = $(this).attr('data-IsEdit');
        var isRejected = $(this).attr('data-IsRejected');
        var isCancel = $(this).attr('data-IsCancel');

        if (isEdit == "true" && isRejected == "false" && isCancel == "false") //pending state.
        {
            $('#LeaveEditBtn').show();
            $('#cntctAdmin').hide();
            $('#lv_cancel_div').show();
            document.getElementById("leaveStatusLabel").style.color = '#ff6a00';//orange color
        }
        else if (isEdit == "false" && isRejected == "false" && isCancel == "false") //approved state.
        {
            $('#LeaveEditBtn').hide();
            $('#cntctAdmin').hide();
            $('#lv_cancel_div').hide();
            document.getElementById("leaveStatusLabel").style.color = '#4cff00';//green color
        }

        else if (isEdit == "false" && isRejected == "true" && isCancel == "false") //rejected state.
        {
            $('#LeaveEditBtn').hide();
            $('#cntctAdmin').show();
            $('#lv_cancel_div').hide();
            document.getElementById("leaveStatusLabel").style.color = '#ff0000';//red color
        }

        else if (isEdit == "false" && isRejected == "false" && isCancel == "true") //cancel state.
        {
            $('#LeaveEditBtn').hide();
            $('#cntctAdmin').hide();
            $('#lv_cancel_div').hide();
            document.getElementById("leaveStatusLabel").style.color = '#ff0000';//red color
        }

        $('#LvstartDateLabel').val($(this).attr('data-fromDate'));
        $('#LvtoDateLabel').val($(this).attr('data-toDate'));
        $('#cmntsArea').val($(this).attr('data-commnets'));
        $('#leaveStatusLabel').text($(this).attr('data-LeaveStatus'));

        try {
            $('#leaveTypeLabel').empty();

            var id = $(this).attr('data-leaveTypeInt');
            $.each(LeaveTypList, function (i, option) {
                try {
                    if (option.Value == id) {
                        $('#leaveTypeLabel').append($('<option/>').attr("value", option.Value).text(option.Text));
                    }
                }
                catch (ex)
                { }
            });

            $('#leaveTypeLabel').val(id);
        }
        catch (ex) {
        }

        $('#LvDurTypLabel').empty();

        var strLvType = ($(this).attr('data-LeaveType')).trim();
        var durId = $(this).attr('data-lvDurTypint');
        if (strLvType == "Casual" || strLvType == "Sick") {
            $.each(LeaveDurList, function (i, option) {
                $('#LvDurTypLabel').append($('<option/>').attr("value", option.Value).text(option.Text));
            });
        }
        else if (strLvType == "Festive") {
            $.each(LeaveDurList, function (i, option) {
                if (option.Value == durId) {
                    $('#LvDurTypLabel').append($('<option/>').attr("value", option.Value).text(option.Text));
                }
            });
        }

        $('#LvDurTypLabel').val(durId);
    })
</script>
