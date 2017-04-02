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