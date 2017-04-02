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

    <script src="{{ asset('js/Helpers/html2canvas.js') }}"></script>
    <script src="{{ asset('js/Helpers/jspdf.debug.js') }}"></script>
</head>
<body>
  @section('content')
    <div class="col-md-8">
      <div class="card">
        <div class="header">
            <h4 class="title" id="profileTitle">Reports</h4>
        </div>
        <div id="LoadPageRprt" class="waitIconDivRprt row text-center" style="display:none">
            <img alt="Progress" src='{{asset('/images/wait_icon.gif')}}' width="50" height="50" id="imgProgRprt" />
        </div>
        <div class="content">
            <form id="emprprstfrm">
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xs-12">
                        <label class = "control-label labelClass hrms-required-field">Select Year</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-xs-12">
                      <select class="form-control dropDown input-field" id="YearDropDown" name="YearDropDown">
                        <option value="">Select Year</option>
                        <option value="2012">2012</option>
                        <option value="2013">2013</option>
                        <option value="2014">2014</option>
                        <option value="2015">2015</option>
                        <option value="2016">2016</option>
                        <option value="2017">2017</option>
                      </select>
                        <label class = "text-danger errLabel" id="ErrYear">Select Year !</label>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-xs-12">
                        <label class = "control-label labelClass hrms-required-field">Select Month</label>
                    </div>
                    <div class="col-md-4 col-lg-4 col-xs-12">
                      <select class="form-control dropDown input-field" id="MonthDropDown" name="MonthDropDown">
                        <option value="">Select Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                        <label class = "text-danger errLabel" id="ErrMonth">Select Month !</label>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <label class = "text-danger errLabel" id="ErrMsgRslt">No results found for the selected year and month !!</label>
                        <input type="button" value="Generate Report" class="btn btn-info btn-fill pull-right" onclick="GenerateReport()" />
                    </div>
                </div>
            </form>
        </div>
    </div>
      <div class="modal fade col-xs-12 col-md-12 col-lg-12" id="UserReportsModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title text-center">User Report</h3>
                </div>

                <div class="modal-body" style="min-height:200px">
                    <div class="row text-right">
                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12 pull-right">
                            <div class="btn-group">
                                <a class="exportIcon" onclick="ExportReport(1)">
                                    <i class="fa fa-print fa-2x" title="Print"></i>
                                </a>
                                <a class="exportIcon" onclick="ExportReport(2)">
                                    <i class="fa fa-file-pdf-o fa-2x" title="Export As PDF"></i>
                                </a>
                                <a class="exportIcon hidden" onclick="ExportReport(3)">
                                    <i class="fa fa-file-image-o fa-2x" title="Export As PNG"></i>
                                </a>
                            </div>
                            <a id="btn-Convert-Image" href="#" style="display:none"></a>
                        </div>
                    </div>
                    <div id="UsrRprtPrntArea">
                        <div class="container" style="border:3px solid #1c94c4">
                            <div class="row text-center">
                                <label id="ReportDateLabel" />
                            </div>
                            <div class="row text-center">
                                <label id="printTitle" style="color: #e78f08;font-size: large;" />
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h5 class="disp_label">Employee Name</h5>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <h5 id="EmployeeName" class="disp_label"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h5 class="disp_label">Working Days</h5>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <h5 id="workngDays" class="disp_label"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h5 class="disp_label">No.of Holidays</h5>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <h5 id="holidays" class="disp_label"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h5 class="disp_label">Attendance</h5>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <h5 id="attdnce" class="disp_label"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h5 class="disp_label">No.of Leaves</h5>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <h5 id="leaves" class="disp_label"></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <h5 class="disp_label">Total Working Hours</h5>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <h5 id="wrkngHours" class="disp_label"></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    @endsection
</body>

<script src="{{asset('js/app/user/employeereports.js')}}"></script>
