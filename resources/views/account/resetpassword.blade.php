@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/User/forgotpassword.css')}}">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    <div class="waitIconDiv col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center">
                        <img alt="Progress" src="{{asset('images/wait_icon.gif')}}" width="50" height="50" id="imgProg" />
                    </div>
                    <div class="row">
                        <label for="email" class="col-md-4 control-label">New password</label>

                        <div class="col-md-6">
                            <input id="nwPwd" type="password" class="form-control" name="nwPwd" required>

                            <label class="text-danger errLabel" id = "nwPwdError"></label>
                        </div>
                    </div>
                    
                    <div class="row buttonRow">
                        <label for="email" class="col-md-4 control-label">Confirm password</label>

                        <div class="col-md-6">
                            <input id="newPwdCnfrm" type="password" class="form-control" name="newPwdCnfrm" required>

                            <label class="text-danger errLabel" id = "nwPwdCnfrmError"></label>
                        </div>
                    </div>
                    <div class="row buttonRow">                     
                        <div class="col-md-6 col-md-offset-4">
                        <input type="button" name="resetPwdSubmitBtn" class="btn btn-primary" onclick="resetPasswordPost()" value="Reset password" />
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 buttonRow hidden">
                            Know your password ? <a href="{{ url('/') }}" class="hrefLink">Login</a>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script type="text/javascript" src="{{ asset('js/Helpers/Common.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/app/account/resetPassword.js') }}"></script>