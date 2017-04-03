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
            // $('#LeaveStatiDiv').modal('show');
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