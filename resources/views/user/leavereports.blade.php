
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

<script src="{{asset('js/app/user/leavereports.js')}}"></script>
