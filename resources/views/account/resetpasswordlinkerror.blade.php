@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/User/forgotpassword.css')}}">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset password</div>
                <div class="panel-body">                    
                    <div class="row">                        
                        <h3 class="modal-title text-center text-danger"><i class="glyphicon glyphicon-ban-circle"></i>&nbsp;&nbsp;It's looks like reset password link has been expired !!</h3>

                        <br />
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                <a href="{{ url('/account/forgotpassword') }}" class="hrefLink">Click here to send it again</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection