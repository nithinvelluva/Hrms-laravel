@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/User/forgotpassword.css')}}">
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">                    
                    <div class="row">
                        
                        <h3 class="modal-title text-center text-success">Your password has been changed successfully</h3>

                        <br />
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                                <a href="{{ url('/') }}" class="hrefLink">Click here to login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection