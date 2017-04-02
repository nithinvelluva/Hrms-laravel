<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Redirect;
use Session;
use App\UserModel;
use Hash;

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
          return view('hrmslogin')->with('errormessage', '');;
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
}
