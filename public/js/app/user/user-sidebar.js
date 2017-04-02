var userPhoto;
initPage();
function initPage(){
  if (Modernizr.touch) {
    $(".close-overlay").removeClass("hidden");
    $(".img").click(function(e){
      if (!$(this).hasClass("hover")) {
        $(this).addClass("hover");
      }
    });
    $(".close-overlay").click(function(e){
      e.preventDefault();
      e.stopPropagation();
      if ($(this).closest(".img").hasClass("hover")) {
        $(this).closest(".img").removeClass("hover");
      }
    });
  }
  else {
    $(".img").mouseenter(function(){
      $(this).addClass("hover");
    })
    .mouseleave(function(){
      $(this).removeClass("hover");
    });
  }
}
function setCurrentDateTime() {
  var currentTime = new Date();
  var date = currentTime.toDateString();
  var time = currentTime.toLocaleTimeString();

  $('#currentDate').text(date.toString());
  $('#currentTime').text(time.toString());

  $('#DateLabelLeave').val("As of Date : " + date.toString());

  var hours = currentTime.getHours();
  var minutes = currentTime.getMinutes();
  var seconds = currentTime.getSeconds();
}
function leaveRefClick(){
  $(".main-nav li").removeClass("active");
  $(LEAVES_LI).addClass("active");
}
$('#usrAvatarExpndBtn').click(function(){
  try{
    $('#userPhotoInput').val('');
    $('#userPhotoInput').trigger('click');
  }
  catch(ex)
  {
    showMessageBox(ERROR,"ERROR !!");
  }
})
function fileCheck(obj) {
  ExtChkFlg = false;
  var fileExtension = ['jpeg', 'jpg', 'png', 'bmp'];
  if ($.inArray($(obj).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
    showMessageBox(WARNING,"Only '.jpeg','.jpg', '.png', '.bmp' formats are allowed.");
    $('#userPhotoInput').val('');
  }
  else{
    ExtChkFlg = true;
  }
}
function ChangeUserPhoto(flag){
  var userPhotoPath = $('#UserPhotoPath').val();
  var prvUserPhotoPath = $('#userPhoto').val();

  if('true' == $('#IsDefaultUserIcon').val())
  {
    prvUserPhotoPath = null;
  }
  var UpdateUserPhoto = {};
  UpdateUserPhoto.url = "/user/UpdateUserPhoto";
  UpdateUserPhoto.type = "POST";
  UpdateUserPhoto.data = JSON.stringify({userphotopath:userPhotoPath,prvUserPhotoPath:prvUserPhotoPath,CancelFlag:flag});
  UpdateUserPhoto.datatype = "json";
  UpdateUserPhoto.contentType = "application/json";

  UpdateUserPhoto.success = function (status) {
    if("OK" == status.status)
    {
      $('#saveDiv').hide();
      $('#userPhoto').val(userPhotoPath);
      $('#userAvatar').attr('src',userPhotoPath);
      $('#IsDefaultUserIcon').val('false');
    }
    else if("CANCEL" == status.status)
    {
      $('#saveDiv').hide();
      $('#userAvatar').attr('src',$('#userPhoto').val());
      $('.cameraDiv').show();
      $('.capturePreviewDiv').hide();
    }
    else{
      showMessageBox(ERROR,"An Unexpected Error Occured!!");
    }
  };

  UpdateUserPhoto.error = function (data) {
    showMessageBox(ERROR,"An Unexpected Error Occured!!");
  };
  $.ajax(UpdateUserPhoto);
}
$("#userPhotoInput").change(function () {
  if(ExtChkFlg){
    var data = new FormData();
    var files = $("#userPhotoInput").get(0).files;
    if (files.length > 0) {
      data.append("MyImages", files[0]);
    }
    $.ajax({
      url: "/user/UploadFile",
      type: "POST",
      processData: false,
      contentType: false,
      data: data,
      success: function (response) {
        if(response && "OK" == response.status){
          var path = response.photopath;
          $('#userAvatar').attr('src',path);
          $('#UserPhotoPath').val(path);
          $('#saveDiv').show();
        }
      },
      error: function (er) {
        showMessageBox(ERROR,er);
      },
      complete :function(data){
      }
    });
  }
});
function navigateTo(page){
  var activeElement;
  var activeContentDiv;
  switch (page) {
    case PROFILE:
    console.log("profile call");
    clearErrors();
    activeElement = PROFILE_LI;
    activeContentDiv = PROFILE_DIV;
    break;
    case ATTENDANCE:
    console.log("attendance call");
    clearErrors();
    setCurrentDateTime();
    getEmpPunchDetails();
    activeElement = ATTENDANCE_LI;
    activeContentDiv = ATTENDANCE_DIV;
    break;
    case LEAVES:
    clearErrors();
    ResetValues();
    activeElement = LEAVES_LI;
    activeContentDiv = LEAVES_DIV;
    break;
    case LEAVEREPORT:
    console.log("leave reports call");
    clearErrors();
    $("#chngDatePicker").val('');
    setLeaveReportsInfo(true);
    PopulateLeaveReportTable();
    activeElement = LEAVES_LI;
    activeContentDiv = LEAVEREPORT_DIV;
    break;
    case REPORTS:
    clearErrors();
    var dateObj = new Date();
                var month = dateObj.getUTCMonth() + 1; //months from 1-12
                var year = dateObj.getUTCFullYear();
                $('#YearDropDown').val(year);
                $('#MonthDropDown').val(month);
                activeElement = REPORTS_LI;
                activeContentDiv = REPORTS_DIV;
                break;
                case CHANGEPASSWORD:
                clearFileds();
                clearErrors();
                activeContentDiv = CHANGEPASSWORD_DIV;
                break;
              }

              $('.contentDiv').hide();
              $(activeContentDiv).show();

              $(".main-nav li").removeClass("active");
              $(activeElement).addClass("active");
            }
            function clearErrors(){
              $('.errLabel').hide();
              $('.input-field').css('border-color','');
              $('.inut-field-profile').css('border-color','');
            }
            function showLoadreport(activDiv,disableDiv) {
              $(activDiv).show();
              $(disableDiv).addClass("disablediv");
              $(".waitIconDiv").css("display", "block");
            }
            function HideLoadreport(activDiv,disableDiv) {
              $(activDiv).hide();
              $(disableDiv).removeClass("disablediv");
              $(".waitIconDiv").css("display", "none");
            }
            function setLeaveReportsInfo(InitFlag){
              if(InitFlag)
              {
                var currentTime = new Date();
                var date = currentTime.toDateString();
                var dateSplit = date.split(' ');
                $('#monthLabel').text("Showing Leave Reports Of : " + dateSplit[1] + "," + dateSplit[3]);
                $('#dateLabel').text(" Details As Of : " + date);
                currYear = dateSplit[3];
                currMonth = (currentTime.getMonth()) + 1;
              }
              else
              {

                var date = $("#chngDatePicker").val();
                currMonth = months[date.split(' ')[0]];
                currYear = date.split(' ')[1];
                $('#monthLabel').text("Showing Leave Reports Of : " + monthKeyValues[currMonth] + "," + currYear);
              }
            }
            function PopulateLeaveReportTable(){
              var statusIconPath;
              var statusToolTip;
              var IsEdit = false;
              var IsRejected = false;

              $('#lblLvNoResults').hide();
              showLoadreport("#imgProgLvRprt",".TableDivLvRprt");
        // if (LeaveDetailsTable != undefined) {
        //     LeaveDetailsTable.destroy();
        // }
        // LeaveDetailsTable = $('#LvReprtTable').DataTable({
        //     "pageLength": 7, "processing": true, "serverSide": true,
        //     "paging": true, "bLengthChange": false, "searching": false,
        //     "bInfo": true,
        //     "ajax": {
        //         "url": "/User/GetLeaveDetails",
        //         "type": "POST",
        //         "dataType": "JSON",
        //         "data": function (d) {
        //             d.EmpId = empId;
        //             d.UserType = userType;
        //             d.month = currMonth;
        //             d.year = currYear;
        //         }
        //     }
        //     ,"columnDefs": [
        //         {
        //             "render": function ( data, type, row ) {
        //                 var result = leaveRprtTableCallBack(row);
        //                 statusToolTip = result["statusToolTip"];
        //                 IsEdit =result["IsEdit"];
        //                 IsRejected = result["IsRejected"];
        //                 return '<input type="button" data-toggle="modal" data-target="#EditLeaveModal" title="View Details" value="View" class="btn btn-primary btn-fill ViewLeaveDetails" data-IsCancel = "'+ row["_cancelled"] +'" data-IsRejected = "'+ IsRejected +'" data-IsEdit="' + IsEdit + '" data-empid="' + empId + '" data-empname="' + empName + '" data-fromDate="' + row["_fromdate"] + '" data-toDate="' + row["_todate"] + '" data-LeaveType="' + row["_strLvType"] + '" data-DurationType="' + row["_leavedurationtype"] + '" data-lvDurTypint="' + row["_leaveDurTypeInt"] + '" data-LeaveStatus="' + statusToolTip + '" data-leaveTypeInt="' + row["_leaveType"] + '" data-commnets="' + row["_comments"] + '"  data-id="' + row["_lvId"] + '" />'
        //             },
        //             "targets": 5
        //         }
        //         ,
        //         {
        //             "render": function ( data, type, row ) {
        //                 var result = leaveRprtTableCallBack(row);
        //                 statusIconPath = result["statusIconPath"];
        //                 statusToolTip = result["statusToolTip"];
        //                 return '<span class="cursor"><i class="'+ statusIconPath +'" aria-hidden="true" title="'+ statusToolTip +'"></i></span>'
        //             },
        //             "targets": 4
        //         }
        //     ]
        //     , "columns": [
        //         { "data": "_fromdate" },
        //         { "data": "_todate" },
        //         { "data": "_strLvType" },
        //         { "data": "_leavedurationtype" },
        //         { "data": "_leaveStatus" },
        //         { "data": "_leaveStatus" }
        //     ]
        //     ,"language":
        //         {
        //                 "processing": "<div class='row text-center waitIcon'><img alt='Progress' src='../Content/images/wait_icon.gif' width='50' height='50'/></div>"
        //         }
        // });
        //
        // HideLoadreport("#imgProgLvRprt",".TableDivLvRprt");
        // $('#lblLvNoResults').hide();
        // $('.TableDivLvRprt').show();

        var GetLeaveDetails = {};
        GetLeaveDetails.url = "/user/GetLeaveDetails";
        GetLeaveDetails.type = "POST";
        GetLeaveDetails.data = JSON.stringify({month: currMonth ,year: currYear});
        GetLeaveDetails.datatype = "json";
        GetLeaveDetails.contentType = "application/json";
        GetLeaveDetails.success = function (status){
         if(!status){
           LeaveDetailsTable.clear().draw();
           HideLoadreport("#imgProgLvRprt",".TableDivLvRprt");
           $('.TableDivLvRprt').hide();
           $('#lblLvNoResults').show();
         }
         else{
           try{
             var response = status.leaveData;
             LeaveDetailsTable.clear().draw();
             var statusIconPath;
             var statusToolTip;
             var IsEdit = false;
             var IsRejected = false;
             if(response != null && response.length > 0){
               $('#lblLvNoResults').hide();
               $('.TableDivLvRprt').show();
               for (var i = 0; i <= response.length - 1; i++) {

                           if(response[i]._status && !response[i]._rejected && !response[i]._cancelled)//approved.
                           {

                             statusIconPath = "fa fa-check fa-lg";
                             statusToolTip = "Approved";
                             IsEdit = false;
                             IsRejected = false;
                           }
                           else if(!response[i]._status && !response[i]._rejected && !response[i]._cancelled)//pending.
                           {

                             statusIconPath = "fa fa-clock-o fa-lg";
                             statusToolTip = "Pending";
                             IsEdit = true;
                             IsRejected = false;
                           }
                           else if(!response[i]._status && response[i]._rejected && !response[i]._cancelled)//rejected.
                           {

                             statusIconPath = "fa fa-times fa-lg";
                             statusToolTip = "Rejected";
                             IsEdit = false;
                             IsRejected = true;
                           }
                           else if(!response[i]._status && !response[i]._rejected && response[i]._cancelled)//cancelled.
                           {

                             statusIconPath = "fa fa-ban fa-lg";
                             statusToolTip = "Cancelled";
                             IsEdit = false;
                             IsRejected = false;
                           }
                           LeaveDetailsTable.row.add({
                             0: response[i].FromDate,
                             1:response[i].ToDate,
                             2:response[i].Type,
                             3: response[i].DurationType,
                             4:'<span class="cursor"><i class="'+ statusIconPath +'" aria-hidden="true" title="'+ statusToolTip +'"></i></span>',
                             5:'<input type="button" data-toggle="modal" data-target="#EditLeaveModal" title="View Details" value="View" class="btn btn-primary btn-fill ViewLeaveDetails" data-IsCancel = "'+ response[i]._cancelled +'" data-IsRejected = "'+ IsRejected +'" data-IsEdit="' + IsEdit + '" data-empid="' + response[i].EmpId + '" data-fromDate="' + response[i].FromDate + '" data-toDate="' + response[i].ToDate + '" data-LeaveType="' + response[i].Type + '" data-DurationType="' + response[i].DurationType + '" data-lvDurTypint="'+response[i].leaveDurTypeInt+'" data-LeaveStatus="' + statusToolTip + '" data-leaveTypeInt="'+response[i].LeaveTypeID+'" data-commnets="' + response[i].Comments + '"  data-id="' + response[i].LeaveID + '" />'
                           }).draw();
                         }
                       }
                       else{
                         $('#lblLvNoResults').show();
                         $('.TableDivLvRprt').hide();
                       }
                       HideLoadreport("#imgProgLvRprt",".TableDivLvRprt");
                     }
                     catch(ex){
                     }
                   }
                 };
                 GetLeaveDetails.error = function (data) {

                   HideLoadreport();
                   showMessageBox(ERROR,"An UnExpected Error Occured !!");
                 };
                 $.ajax(GetLeaveDetails);
               }
               function leaveRprtTableCallBack(row){
                var statusIconPath;
                var statusToolTip;
                var IsEdit = false;
                var IsRejected = false;

        if(row["_status"] && !row["_rejected"] && !row["_cancelled"])//approved.
        {
          statusIconPath = "fa fa-check fa-lg";
          statusToolTip = "Approved";
          IsEdit = false;
          IsRejected = false;
        }
        else if(!row["_status"] && !row["_rejected"] && !row["_cancelled"])//pending.
        {
          statusIconPath = "fa fa-clock-o fa-lg";
          statusToolTip = "Pending";
          IsEdit = true;
          IsRejected = false;
        }
        else if(!row["_status"] && row["_rejected"] && !row["_cancelled"])//rejected.
        {
          statusIconPath = "fa fa-times fa-lg";
          statusToolTip = "Rejected";
          IsEdit = false;
          IsRejected = true;
        }
        else if(!row["_status"] && !row["_rejected"] && row["_cancelled"])//cancelled.
        {
          statusIconPath = "fa fa-ban fa-lg";
          statusToolTip = "Cancelled";
          IsEdit = false;
          IsRejected = false;
        }

        return {statusIconPath:statusIconPath,statusToolTip:statusToolTip,IsEdit:IsEdit,IsRejected:IsRejected};
      }
      $(document).ready(function () {
        $('#EmpDateOfBirth').datepicker({
          format: "dd/mm/yyyy",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });
        $('#PunchStartDate').datepicker({
          format: "dd/mm/yyyy",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });
        $('#PunchEndDate').datepicker({
          format: "dd/mm/yyyy",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });
        $('#Fromdatetimepicker').datepicker({
          format: "dd/mm/yyyy",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });
        $('#Todatetimepicker').datepicker({
          format: "dd/mm/yyyy",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });
        $("#chngDatePicker").datepicker({
          changeMonth: true,
          changeYear: true,
          showButtonPanel: true,
          dateFormat: 'MM yy',
          beforeShow: function(input) {
            $(input).datepicker("widget").addClass('hide-calendar');
          },
          onClose: function(dateText, inst) {
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
          }
        });
        $("#LvstartDateLabel").datepicker({
          format: "dd/mm/yyyy",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });
        $("#LvtoDateLabel").datepicker({
          format: "dd/mm/yyyy",
          changeMonth: true,
          changeYear: true,
          beforeShow: function(input) {
            $(input).datepicker("widget").removeClass('hide-calendar');
          }
        });
      });