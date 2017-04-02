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
        <div class="waitIconDiv row text-center" style="display:none">
            <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="50" height="50" id="imgProgAtt" />
        </div>
        <div class="header">
            <h3 class="title">Attendance</h3>
        </div>
        <div class="content attnCntDiv">
            <input type="hidden" name="attId" id="attId"/>
            <div class="row" id="punchdInDiv">
                <div class="col-md-4">
                    <h5>Punched In Time</h5>
                </div>
                <div class="col-md-8">
                    <h5 id="PunchedInTime" class="disp_label"></h5>
                </div>
            </div>
            <div class="row" id="punchdOutDiv">
                <div class="col-md-4">
                    <h5>Punched Out Time</h5>
                </div>
                <div class="col-md-8">
                    <h5 id="PunchedOutTime" class="disp_label"></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h5>Date</h5>
                </div>
                <div class="col-md-8">
                    <h5 id="currentDate" class="disp_label"></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h5>Time</h5>
                </div>
                <div class="col-md-8">
                    <h5 id="currentTime" class="disp_label"></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h5>Notes</h5>
                </div>
                <div class="col-md-6">
                    <textarea id="notesField" class="form-control" placeholder="Enter Notes" style="height:100px"></textarea>
                </div>
            </div>
            <br />
            <div class="row text-right">
                <div class="col-md-12 col-lg-12">
                    <input type="button" onclick="punchOut()" class="btn btn-info btn-fill pull-right" value="Punch Out"
                    id="punchOutBtn" style="display:none"/>
                    <input type="button" onclick="punchIn()" class="btn btn-info btn-fill pull-right" value="Punch In"
                    id="punchInBtn" />
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="header">
            <h4 class="title">Attendance Reports</h4>
        </div>
        <div class="content">
            <input type="button" class="btn btn-info btn-fill" value="Search attendance" onclick="checkpunchInfo()"
            data-toggle="modal" data-target="#CheckPuchInfo" />
        </div>
    </div>

    <div class="modal fade col-xs-12 col-md-12 col-lg-12 col-sm-12" id="CheckPuchInfo" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content hrms-modal-height">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title text-center">Attendance Report</h3>
                </div>

                <div class="modal-body">
                    <div class="row attnRprtDiv">
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group">
                                <label class="hrms-required-field">Start Date</label>
                                <input type="text" class="form-control input-field" id="PunchStartDate" title="Start Date" placeholder="Start Date" required />
                                <label class="text-danger errLabel" id = "lblstartDate">Select start date !!</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="text" class="form-control input-field" id="PunchEndDate" title="End Date" placeholder="End Date" required />
                                <label class="text-danger errLabel" id = "lblEndDate">Select end date !!</label>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 pull-right" style="padding-top:20px">
                            <div class="form-group">
                                <input type="button" value="Search" class="btn btn-info btn-fill" onclick="SearchAttendance()" />
                            </div>
                        </div>
                    </div>
                    <div id="LoadPageAttnRprt" class="waitIconDiv row text-center" style="display:none">
                        <img alt="Progress" src='{{asset('images/wait_icon.gif')}}' width="50" height="50" id="imgProg" />
                    </div>
                    <br />
                    <label class="text-danger errLabel" id = "lblNoResults">No Records Found !!</label>
                    <div class="row" id="ResultTableDiv">
                        <table id="punchTable" class="gridTableAtt cell-border hover display nowrap responsive"
                        cellspacing="0" style="width:90%">
                        <thead class="pretty col-header">
                            <tr class="tableHeader">
                                <th>Date</th>
                                <th>PunchedIn Time</th>
                                <th>PunchedOut Time</th>
                                <th class="durCol">Duration(hh:mm)</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
</body>
<script>
    $.ajaxSetup({
      headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') }
  });
    var PunchDetailsTable;
    $(document).ready(function () {
      navigateTo(ATTENDANCE);
      PunchDetailsTable = $('#punchTable').DataTable({
       "pageLength": 7, "bFilter": true, "bInfo": true,
       "bLengthChange": false, "ordering": true,
       "searching": false, responsive: true
   });
  });
    $('#lblNoResults').show();
    $('#ResultTableDiv').hide();
    $("#LoadPage").hide();
    function checkpunchInfo() {
        clearValues();
        clearErrors();
    }
    function clearValues() {
        $('#PunchStartDate').val("");
        $('#PunchEndDate').val("");
        $('#ResultTableDiv').hide();
        $('#notesField').val('');
    }
    function convertTimeLocal(serverTime){
        var date = new Date(serverTime + " UTC");
        return date.toLocaleString();
    }
    function getEmpPunchDetails(){
        showLoadreport("#imgProgAtt",".attnCntDiv");
        $('#notesField').val('');
        $.ajax({
            url: "/user/GetEmpPunchDetails",
            type:"POST",
            success:function(data){
                if(data){
                  var response = data.punchInfo;
                  if (stringIsNull(response)) {
                      $('#punchInBtn').show();
                      $('#punchdInDiv').hide();
                      $('#punchOutBtn').hide();
                      $('#punchdOutDiv').hide();
                  }
                  else if (!stringIsNull(response[0]["PunchinTime"]) && !stringIsNull(response[0]["PunchoutTime"])) {
                      $('#punchInBtn').show();
                      $('#punchOutBtn').hide();
                      $('#punchdInDiv').hide();
                      $('#punchdOutDiv').hide();
                  }
                  else if (!stringIsNull(response[0]["PunchinTime"]) && stringIsNull(response[0]["PunchoutTime"])) {
                      $('#attId').val(response[0]["ID"]);
                      $('#punchInBtn').hide();
                      $('#punchOutBtn').show();
                          //var date = new Date(response[0]["PunchinTime"] +" UTC");
                          $('#PunchedInTime').text(convertTimeLocal(response[0]["PunchinTime"]));
                          $('#punchdInDiv').show();
                          $('#punchdOutDiv').hide();
                      }
                  }
                  HideLoadreport("#imgProgAtt",".attnCntDiv");
              },
              error:function(data){
                console.log(data.responseText);
            },
            complete: function (data) {
              HideLoadreport("#imgProgAtt",".attnCntDiv");
          }
      });
    }
    function punchIn() {
        showLoadreport("#imgProgAtt", ".attnCntDiv");
        var AddAttendance = {};
        AddAttendance.url = "/user/AddAttendance";
        AddAttendance.type = "POST";
        AddAttendance.data = JSON.stringify({ punchInTime: null, punchOutTime: null, type: 1,notes:$('#notesField').val() });
        AddAttendance.datatype = "json";
        AddAttendance.contentType = "application/json";
        AddAttendance.success = function (data) {
            setCurrentDateTime();
            $('#notesField').val('');
            HideLoadreport("#imgProgAtt", ".attnCntDiv");
            if (stringIsNull(data)) {
                showMessageBox(ERROR, "An Unexpected Error Occured!!");
            }
            else if (data.status == "OK") {
                $('#attId').val(data.attId);
                $('#punchInBtn').hide();
                $('#punchOutBtn').show();
                // var date = new Date(data.punchInTime +" UTC");
                $('#PunchedInTime').text(convertTimeLocal(data.punchInTime));
                $('#punchdInDiv').show();
                $('#punchdOutDiv').hide();
            }
            else{
                showMessageBox(ERROR, "An Unexpected Error Occured!!");
            }
        };
        AddAttendance.error = function () {
            HideLoadreport("#imgProgAtt", ".attnCntDiv");
            showMessageBox(ERROR, "An Unexpected Error Occured!!");
        };
        $.ajax(AddAttendance);
    }
    function punchOut() {
        setCurrentDateTime();
        showLoadreport("#imgProgAtt", ".attnCntDiv");
        var pin = $('#PunchedInTime').text();
        var AddAttendance = {};
        AddAttendance.url = "/user/AddAttendance";
        AddAttendance.type = "POST";
        AddAttendance.data = JSON.stringify({ attId:$('#attId').val(), punchInTime: pin, punchOutTime: null, type: 2
            ,notes:$('#notesField').val()});
        AddAttendance.datatype = "json";
        AddAttendance.contentType = "application/json";
        AddAttendance.success = function (data) {
            setCurrentDateTime();
            $('#notesField').val('');
            HideLoadreport("#imgProgAtt", ".attnCntDiv");
            if (stringIsNull(data)) {
                showMessageBox(ERROR, "An Unexpected Error Occured!!");
            }
            else if (data.status == "OK") {
                $('#punchInBtn').show();
                $('#punchOutBtn').hide();
                $('#punchdInDiv').hide();
                $('#punchdOutDiv').hide();
            }
            else {
                showMessageBox(ERROR, "An Unexpected Error Occured!!");
            }
        };
        AddAttendance.error = function () {
            HideLoadreport("#imgProgAtt", ".attnCntDiv");
            showMessageBox(ERROR, "An Unexpected Error Occured!!");
        };
        $.ajax(AddAttendance);
    }
    $('#PunchStartDate').on('change keyup paste', function () {
        if (this.value.toString().length == 0) {
            $('#PunchStartDate').css('border-color', 'red');
            $('#lblstartDate').text('Select Start Date !')
            $('#lblstartDate').show();
        }
        else {
            $('#PunchStartDate').css('border-color', '');
            $('#lblstartDate').hide();
        }
    });
    $('#PunchEndDate').on('change keyup paste', function () {
        if (this.value.toString().length == 0) {
        }
        else {
            $('#PunchEndDate').css('border-color', '');
            $('#lblstartDate').hide();
        }
    });
    function getAttendanceData() {
        $('#lblNoResults').hide();
        // $('#ResultTableDiv').show();
        // HideLoadreport("#LoadPageAttnRprt", "#attnRprtDiv");

        // PunchDetailsTable = $('#punchTable').DataTable({
        //     "pageLength": 7, "processing": true, "serverSide": true,
        //     "paging": true, "bLengthChange": false, "searching": false,
        //     "bInfo": true,
        //     "ajax": {
        //         "url": "/User/SearchPunchDetails",
        //         "type": "POST",
        //         "dataType": "JSON",
        //         "data": function (d) {
        //             d.EmpId = empId;
        //             d.StartDate = $('#PunchStartDate').val();
        //             d.EndDate = $('#PunchEndDate').val();
        //             d.timeoffset = offset;
        //         }
        //     }
        //     ,"columnDefs": [
        //         {
        //             "render": function ( data, type, row ) {
        //                 return row["PunchinTime"].split(' ')[0]
        //             },
        //             "targets": 0
        //         }
        //     ]
        //     , "columns": [
        //         { "data": "PunchinTime" },
        //         { "data": "PunchinTime" },
        //         { "data": "PunchoutTime" },
        //         { "data": "Duration" }
        //     ]
        //      , "language":
        //         {
        //             "processing": "<div class='row text-center waitIconDiv'><img alt='Progress' src='../Content/images/wait_icon.gif' width='50' height='50'/></div>"
        //         }
        // });
        $.ajax({
            url: "/user/SearchPunchDetails",
            type:"POST",
            datatype:"json",
            contentType :"application/json",
            data:JSON.stringify({ StartDate: $('#PunchStartDate').val(), EndDate: $('#PunchEndDate').val() }),
            success:function(data){
             HideLoadreport("#LoadPageAttnRprt", "#attnRprtDiv");
             try {
                 if (data) {
                     var response = data.searchData;
                     if(response.length > 0){
                        $('#ResultTableDiv').show();
                        PunchDetailsTable.clear().draw();
                        for (var i = 0; i <= response.length - 1 ; i++) {
                            PunchDetailsTable.row.add({
                              0: (response[i].PunchinTime).split(' ')[0],
                              1: (response[i].PunchinTime)?convertTimeLocal(response[i].PunchinTime):'',
                              2: (response[i].PunchoutTime)?convertTimeLocal(response[i].PunchoutTime):'',
                              3: response[i].Duration?response[i].Duration:""
                          }).draw();
                        }
                    }
                    else {
                      $('#ResultTableDiv').hide();
                      $('#lblNoResults').show();
                  }
              }
              else {
                  $('#ResultTableDiv').hide();
                  $('#lblNoResults').show();
              }
          }
          catch (ex) {
            showMessageBox(ERROR, "An Unexpected Error Occured!!");
        }
    },
    error:function(data){
        HideLoadreport("#LoadPageAttnRprt", "#attnRprtDiv");
        showMessageBox(ERROR, "An Unexpected Error Occured!!");
    },
    complete: function (data) {
      HideLoadreport("#LoadPageAttnRprt", "#attnRprtDiv");
  }
});
    }
    function SearchAttendance() {
        clearErrors();
        var startDate = $('#PunchStartDate').val();
        var endDate = $('#PunchEndDate').val();
        var sDate = new Date(startDate);
        var eDate = new Date(endDate);
        var currentDate = new Date();
        var flag = false;
        if ((startDate.length == 0 && endDate.length == 0) || (startDate.length == 0 && endDate.length != 0)) {
            $('#PunchStartDate').css('border-color', 'red');
            $('#lblstartDate').text('Select Start date !!');
            $('#lblstartDate').show();
            flag = true;
        }
        else if (sDate > eDate) {
            $('#PunchStartDate').css('border-color', 'red');
            $('#lblstartDate').text('Start date must be less than or equal to End date !!');
            $('#lblstartDate').show();
            flag = true;
        }
        else if (eDate > currentDate) {
            $('#PunchEndDate').css('border-color', 'red');
            $('#lblEndDate').text('End date must be less than or equal to Current Date !!');
            $('#lblEndDate').show();
            flag = true;
        }
        if (!flag) {
            showLoadreport("#LoadPageAttnRprt", "#attnRprtDiv");
            //blockNumber = 1;
            getAttendanceData();
        }
    }
</script>
