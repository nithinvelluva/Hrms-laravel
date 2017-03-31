$(document).ready(function () {        
        $('#UserName').focus();
    });
    function onBlurInput(){
        if ($('#UserName').val().toString().length > 0 && $('#Password').val().toString().length > 0) {
            $('#ErrorMsg').hide();
            $('#UserName').removeClass('input-validation-error');
            $('#Password').removeClass('input-validation-error');
        }
    }
