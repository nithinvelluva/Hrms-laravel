$(document).ready(function(){
  navigateTo(REPORTS);
});
function ExportReport(type) {
    var imgsrc;
    var imgName;
    html2canvas($("#UsrRprtPrntArea"), {
        onrendered: function (canvas) {
            imgsrc = canvas.toDataURL("image/png");
            console.log(imgsrc);
            imgName = "HRMS_UserReport" + "_" + $('#wlcmMsgLabel').text() + "_" + $('#printTitle').text();
            switch (type) {
                    case 1://to print
                    var printContent = document.getElementById('UsrRprtPrntArea').innerHTML;
                    var contents = $("#UsrRprtPrntArea").html();
                    var windowUrl = 'HRMS - UserReport';
                    var uniqueName = new Date();
                    var windowName = 'Print' + uniqueName.getTime();

                    var winPrint = window.open(windowUrl, windowName);
                    winPrint.document.write("<html><head><title></title>");
                    winPrint.document.write("</head><body><div>");
                    winPrint.document.write(printContent);
                    winPrint.document.write("</div></body></html>");
                    winPrint.document.close();
                    winPrint.focus();
                    winPrint.print();
                    winPrint.close();
                    break;
                    case 2://to pdf
                    var doc = new jsPDF();
                    doc.text(imgName, 10, 10);
                    doc.addImage(imgsrc, 'JPEG', 15, 40, 180, 180);
                    doc.save(imgName + '.pdf');
                    break;
                    case 3://to image
                        // var newData = imgsrc.replace(/^data:image\/png/, "data:application/octet-stream");
                        // $("#btn-Convert-Image").attr("download", imgName + ".png").attr("href", newData);
                        // window.location = $('#btn-Convert-Image').attr('href');
                        break;
                        default:
                        break;
                    }
                }
            });
}
function GenerateReport() {
    var year = $('#YearDropDown').val();
    var month = $('#MonthDropDown').val();
    clearErrors();

    var ValidateFlag = false;
    if (stringIsNull(year)) {
        $('#YearDropDown').css('border-color', 'red');
        $('#ErrYear').show();
        ValidateFlag = true;
    }
    if (stringIsNull(month)) {
        $('#MonthDropDown').css('border-color', 'red');
        $('#ErrMonth').show();
        ValidateFlag = true;
    }
    if (!ValidateFlag) {
        showLoadreport("#LoadPageRprt", "#emprprstfrm");
        var GetUserReport = {};
        GetUserReport.url = "/user/postUserReport";
        GetUserReport.type = "POST";
        GetUserReport.data = JSON.stringify({ year: year, month: month });
        GetUserReport.datatype = "json";
        GetUserReport.contentType = "application/json";
        GetUserReport.success = function (status) {
            if (!status || stringIsNull(status)) {
                HideLoadreport("#LoadPageRprt", "#emprprstfrm");
                $('#ErrMsgRslt').show();
            }
            else {
                HideLoadreport("#LoadPageRprt", "#emprprstfrm");
                $('#UserReportsModal').modal('show');
                var response = status.userReport;
                var currentDate = new Date();
                $('#ReportDateLabel').text("Report As of : " + currentDate.toDateString());
                $('#printTitle').text("Monthly Report - " + $('#MonthDropDown option:selected').text() + "," + $('#YearDropDown option:selected').text())
                    //setting values
                    $('#EmployeeName').text($('#wlcmMsgLabel').text());
                    $('#workngDays').text(response.workingDays);
                    $('#holidays').text(response.holidays);
                    $('#attdnce').text(response.activeDays + ' (Days)');
                    $('#leaves').text(response.leaveDays);
                    $('#wrkngHours').text(response.workingHours + ' (Hours)');
                    $('#LoadPage2').hide();
                }
            }; GetUserReport.error = function (data) {
            };
            $.ajax(GetUserReport);
        }
    }
    $('#YearDropDown').change(function () {
        if (stringIsNull($('#YearDropDown').val()) || $('#YearDropDown').val() < 0) {
            $('#YearDropDown').css('border-color', 'red');
            $('#ErrYear').show();
        }
        else {
            $('#YearDropDown').css('border-color', '');
            $('#ErrYear').hide();
        }
    })
    $('#MonthDropDown').change(function () {
        if (stringIsNull($('#MonthDropDown').val()) || $('#MonthDropDown').val() < 0) {
            $('#MonthDropDown').css('border-color', 'red');
            $('#ErrMonth').show();
        }
        else {
            $('#MonthDropDown').css('border-color', '');
            $('#ErrMonth').hide();
        }
    })