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
</script>
<script type="text/javascript" src="{{asset('js/app/user/attendance.js')}}"></script>
