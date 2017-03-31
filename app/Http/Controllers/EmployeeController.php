<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent;
use App\users;
use Session;
use Redirect;
use App\EmployeeModel;
use Illuminate\Support\Facades\Input;

class EmployeeController extends Controller
{
   public function __construct()
      {
      }

      public function getUserData(){
        if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          switch ($sessionData["userrole"]) {
            case 1:
                  // $userDetails = array(
                  //   'users' => users::all()
                  // );
                  //  return view('/admin/userslist',$userDetails);
                  $userDetails = EmployeeModel::getEmployeeList();

                  return view('/admin/userslist',['users' => $userDetails]);
              break;
            case 2:
                $employee = EmployeeModel::getEmployeeData($sessionData["userid"]);
                return view('/user/profile',['employeeInfo' => $employee]);
              break;
          }
        }
        else{
          return Redirect::to('/');
        }
      }

      public function getaddEmployee(){
        if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          switch ($sessionData["userrole"]) {
            case 1:
                  $roleList = EmployeeModel::getRoleSelectList();
                  $desigList = EmployeeModel::getEmployeeDesignationList();
                  return view('/admin/addemployee',['rolelist'=>$roleList,'desiglist'=>$desigList]);
              break;
            case 2:
                return Redirect::to('/user/profile');
              break;
          }
        }
        else{
          return Redirect::to('/');
        }
      }

      public function postAddEmployee(){
        if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          switch ($sessionData["userrole"]) {
            case 1:
                    $userData = ['name' => Input::get('EmpName'),'email' => Input::get('EmailId'),'password' => bcrypt("hrms123"),'usertype' => Input::get('Usertype')];
                    $profileData = ['EmpPhone' => Input::get('PhoneNumber'),'EmpGender' => Input::get('Gender'),'EmpDob' => Input::get('EmpDateOfBirth'),'EmpDesignation' => Input::get('EmpDesig')];

                    EmployeeModel::addEmployee($userData,$profileData);
                    return Redirect::to('/employees');
              break;
            case 2:
                  return Redirect::to('/user/profile');
              break;
          }
        }
        else{
          return Redirect::to('/');
        }
      }

      public function removeEmployee(){
        if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          switch ($sessionData["userrole"]) {
            case 1:
                  $empId = Input::get('IdNew');
                  EmployeeModel::removeEmployee($empId);
                  return Redirect::to('/employees');
              break;
            case 2:
                  return Redirect::to('/user/profile');
              break;
          }
        }
        else{
          return Redirect::to('/');
        }
      }
  }
