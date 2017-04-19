<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use App\UserModel;
use App\Helpers\Helper;
use Hash;
use Mail;
use URL;
use Carbon\Carbon;

class AccountController extends Controller
{
  public function loginGet(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
        return Redirect::to('/employees');
        break;
        case 2:
        return Redirect::to('/user/profile');
        break;
      }
    }
    else{
      return view('hrmslogin')->with('errormessage', '');
    }
  }

  public function logout(){
    Session::flush();
    return Redirect::to('/');
  }

  public function loginPost(){
    $data = ['email' => Input::get('email')];
    $user = UserModel::validateUser($data);

    if(empty($user)){
          // return Redirect::to('/')->with('errormessage', 'Invalid credentials');
          // return Redirect::back()->with('errormessage', 'Invalid credentials');
      return Redirect::back()->withErrors('errormessage', 'Invalid credentials')->withInput();
    }
    else{
      $stordPwd = $user -> password;
      if (Hash::check(Input::get('password'), $stordPwd)){
         // The passwords match...
        $userData = array(
          'username' => $user -> email ,
          'userid' => $user -> id,
          'userrole' => $user -> usertype,
          'employeeName' =>  $user -> name );

        Session::put('userdata',$userData);

        switch ($userData["userrole"]) {
          case 1:
          return Redirect::to('/employees');
          break;
          case 2:
          return Redirect::to('/user/profile');
          break;
        }
      }
      else{
        return Redirect::back()->withErrors('errormessage', 'Invalid credentials')->withInput();
                // return Redirect::to('/')->with('errormessage', 'Invalid credentials');
                // return Redirect::back()->with('errormessage', 'Invalid credentials');
      }
    }
  }

  public function getForgotPassword(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
        return Redirect::to('/employees');
        break;
        case 2:
        return Redirect::to('/user/profile');
        break;
      }
    }
    else{
      return view('/account/sentresetlink');
    }
  }

  public function postForgotPassword(Request $request){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
        return Redirect::to('/employees');
        break;
        case 2:
        return Redirect::to('/user/profile');
        break;
      }
    }
    else{
      $response = '';
      $status = 0;

      $email = $request->email;      
      $userData = UserModel::validateUser(['email' => $email]); 

      if(empty($userData)){
        $response = 'User not exists';
        $status = 404;
      }
      else{
        $toEmail = $email;
        // Mail::to($toEmail)->send(new sentEmail());
        
        $token = Carbon::now().'_'.$userData->email.'_'.$userData->id;  

        $data = ['EmpId' => $userData->id,'reset_token' => $token,'created_at' => Carbon::now()->addHours(4)];
        UserModel::createResetPasswordToken($data);

        $resetUrl = URL::to('/account/resetpassword',['token'=>$token,'empId'=>$userData->id]);

        $mailData = ['email' => $userData->email,'name' => $userData->name,
        'subject' => 'Reset password','resetToken' => $resetUrl];

        Helper::sentEmail('account.resetPasswordLink',$mailData);

        $response = 'Reset password sent successfully';
        $status = 200;
      }

      return response()->json(array('response' => $response,'status' => $status),200);
    }
  }

  public function getResetPasswordSuccess(){
    return view('/account/resetpasswordsuccess');     
  }

  public function getResetPasswordError(){
    return view('/account/resetpasswordlinkerror');
  }

  public function getResetPassword($userToken = null,$empId = null){

    if($userToken && $empId){
      $tokenData = ['EmpId' => $empId , 'reset_token' => $userToken];
      $isValid = UserModel::validateResetPasswordToken($tokenData);      

      if($isValid){        
        return view('/account/resetpassword',['token'=>$userToken,'empId'=>$empId]);
      }
      else{
        //token expired..
        return Redirect::to('/account/resetpasswordlinkerror');
      }
    }
  }

  public function postResetPassword(Request $request){
    $response = "ERROR";
    $status = 404;

    if($request){
      $updateData = ['id' => $request->empId,'password' => $request->nwPwd];
      $tokenData = ['EmpId' => $request->empId,'reset_token' => $request->token];

      UserModel::resetUserPassword($updateData);
      UserModel::updateResetPasswordToken($tokenData);

      $response = "OK";
      $status = 200;
    }
    return response()->json(array('response' => $response,'status' => $status),200);    
  }
}
