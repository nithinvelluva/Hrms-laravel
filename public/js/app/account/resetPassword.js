$(document).ready(function(){	
	$('#nwPwd').val('');
	$('#nwPwdCnfm').val('');
});

$('#nwPwd').on('blur', function () {
	if (this.value.toString().length <= 0) {
		$('#nwPwd').css('border-color','red');
		$('#nwPwdError').text('Enter new password !');
		$('#nwPwdError').show();
	}
	else {
		$('#nwPwdError').hide();
		$('#nwPwd').css('border-color','');
	}
});
$('#CnfrmPassword').on('change keyup paste blur', function () {
	if (this.value.toString().length <= 0) {
		$('#nwPwdCnfm').css('border-color','red');
		$('#nwPwdCnfrmError').text('Enter new password again to confirm !');
		$('#nwPwdCnfrmError').show();
	}
	else {
		if (($('#nwPwd').val().length > 0 && $('#nwPwdCnfm').val().length > 0) && ($('#nwPwd').val() != $('#nwPwdCnfm').val())) {
			$('#nwPwdCnfm').css('border-color','red');
			$('#nwPwdCnfrmError').text("Confirm password doesn't match");
			$('#nwPwdCnfrmError').show();
		}
		else {
			$('#nwPwdCnfm').css('border-color','');
			$('#nwPwdCnfrmError').hide();
		}
	}
});   

function resetPasswordPost(){

	var nwPwd = $('#nwPwd').val();
	var nwPwdCnfm = $('#nwPwdCnfm').val();
	var isValid = true;

	if(stringIsNull(nwPwd)){
		$('#nwPwd').css('border-color','red');
		$('#nwPwdError').text('Enter new password !');
		$('#nwPwdError').show();
		isValid = false;
	}
	if(stringIsNull(nwPwdCnfm)){
		$('#nwPwdCnfm').css('border-color','red');
		$('#nwPwdCnfrmError').text('Enter new password again to confirm !');
		$('#nwPwdCnfrmError').show();
		isValid = false;
	}
	if((!stringIsNull(nwPwd) && !stringIsNull(nwPwdCnfm) && nwPwd != nwPwdCnfm)){
		$('#nwPwdCnfm').css('border-color','red');
		$('#nwPwdCnfrmError').text("Confirm password doesn't match !");
		$('#nwPwdCnfrmError').show();
		isValid = false;
	}

	if(isValid){
		var params = {
			empId : $('#empId').val(),
			token : $('#token').val(),
			nwPwd : nwPwd,
			nwPwdCnfm : nwPwdCnfm
		};
		$.ajax({
			url: "/account/resetpassword",
			type: "POST",
			data: JSON.stringify(params),
			datatype: "json",
			contentType: "application/json",
			success: function (data) {
				if(data && "OK" == data.response){
					$('#nwPwd').val('');
					$('#nwPwdCnfm').val('');
					$('#empId').val('');
					$('#token').val('');

					var href = $('#success-ref').attr('href');
					window.location.href = href;
					//window.location.href = "~/account/resetpasswordsuccess";		
					// window.location.href = '{{url("/account/resetpasswordsuccess")}}';					
				}				
			},
			error: function (data) {				
				showMessageBox(ERROR, "An unexpected error occured !");
			}
		});
	}
}