<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Session;
use App\EmployeeModel;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Input;
use App\UserModel;
use App\AttendanceModel;
use App\LeaveModel;
use App\Http\Requests;
use Carbon\Carbon;
use Validator;
use File;
use FileInfo;
use DB;

class UserController extends Controller
{
  public function getUserProfile(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
            $employee = EmployeeModel::getEmployeeData($sessionData["userid"]);
            if(!empty($employee)  && $employee -> EmpPhotoPath){
              $employee->EmpPhotoPath = '/images/uploads/'.$employee->EmpPhotoPath;
            }
            return view('/user/profile',['employeeInfo'=> $employee,'empName' => $sessionData["employeeName"]]);
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function userProfilePost(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
              $userData = ['id' => $sessionData["userid"],'email' => Input::get('EmailId'),'name' => Input::get('EmpFirstName')];
              $profileData = ['EmpPhone' => Input::get('PhoneNumber'),'EmpDob' => Input::get('EmpDateOfBirth')];
              $updateUser = UserModel::updateUser($userData,$profileData);
              $newSessionData = array(
                'username' => $userData['email'],
                'userid' => $userData['id'],
                'userrole' => $sessionData['userrole'],
                'employeeName' =>  $userData['name']);
              Session::put('userdata',$newSessionData);
              $employee = EmployeeModel::getEmployeeData($sessionData['userid']);
              if($employee && $employee -> EmpPhotoPath){
                  $employee->EmpPhotoPath = '/images/uploads/'.$employee->EmpPhotoPath;
              }
              return view('/user/profile',['employeeInfo'=> $employee,'empName' => $sessionData["employeeName"]]);
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function UploadFile(Request $request){
    if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          $empId = $sessionData["userid"];

          if( $request->hasFile('MyImages') ) {
              $file = $request->file('MyImages');
                /*
                * Validate the request
                */
                // $validator = Validator::make($request,
                //   [ 'MyImages' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',]
                // );
                //
                // if ($validator->passes()) {
                //   echo "valid";
                // }
                /*
                * Get the image name and store the image in users
                * folder on the server
                */
                $filename = 'HRMS'.'_'.$empId.'_'.time().'.'.$request->MyImages->getClientOriginalExtension();
                $imgFolderPath = '/images/uploads/';
                $destinationPath = public_path() . $imgFolderPath;
                if(!File::exists($destinationPath)) {
                    // path does not exist
                    $result = File::makeDirectory($destinationPath, 0775);
                }
                $file->move($destinationPath,$filename);
                $profileData = ['EmpPhotoPath' => $filename,'EmpId' => $empId];
                UserModel::UpdateUserPhoto($profileData);
                return response()->json(['photopath'=> $imgFolderPath.$filename,'status' => 'OK'],200);
      }
      //return response()->json(['error'=>$validator->errors()->all()]);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function UpdateUserPhoto(Request $request){
    $rtrnMsg = '';
    if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          $empId = $sessionData["userid"];
          $photoDir = '/images/uploads/';

          if($request && $request -> userphotopath){
            if(!($request -> CancelFlag)){
              if($request -> prvUserPhotoPath){
                if(File::exists(public_path().$request -> prvUserPhotoPath)){
                  File::delete(public_path().$request -> prvUserPhotoPath);
                }
              }
              $rtrnMsg = 'OK';
            }
            else{
              $filename = '';
              if(File::exists(public_path().$request -> userphotopath)){
                File::delete(public_path().$request -> userphotopath);
              }
              if($request -> prvUserPhotoPath){
                $filename = str_replace($photoDir,'',$request -> prvUserPhotoPath);
              }
              $profileData = ['EmpId' => $empId,'EmpPhotoPath' => $filename];
              UserModel::UpdateUserPhoto($profileData);
              $rtrnMsg = 'CANCEL';
            }
            return response()->json(array('status'=> $rtrnMsg), 200);
          }
          return response()->json(array('status'=> 'ERROR'), 400);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function removeUserPhoto(Request $request){
      if(Session::get('userdata')){
        $sessionData = Session::get('userdata');
        $empId = $sessionData["userid"];
        $photoDir = '/images/uploads/';
        $userPhoto = DB::table('employeeinfo')->where('EmpId',$empId)->get(['EmpPhotoPath']);
        if($userPhoto){
          $photoDir = '/images/uploads/';
          $path = '';
          foreach ($userPhoto as $item) {
            $path = $item -> EmpPhotoPath;
          }
          if(File::exists(public_path().$photoDir.$path)){
            File::delete(public_path().$photoDir.$path);

            $profileData = ['EmpId' => $empId,'EmpPhotoPath' => ''];
            UserModel::UpdateUserPhoto($profileData);
          }
        }
        return response()->json(array('status'=> 'OK'), 200);
      }
      else{
          return Redirect::to('/');
      }
  }

  public function getChangeUserPassword(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
            return view('/user/changepassword',array('empName' => $sessionData["employeeName"]));
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function postChangeUserPassword(Request $request){
    if(Session::get('userdata')){
        $sessionData = Session::get('userdata');
        $empId = $sessionData["userid"];
        // $validator = $this->validate($request, [
        //   'currPwd' => 'bail|required|min:6|max:12',
        //   'nwPwd' => 'bail|required|min:6|max:12',
        //   'nwPwdCnfm' => 'bail|required|min:6|max:12',
        // ]);
        // $validator =  Validator::make($request->all(), [
        //   'currPwd' => 'bail|required|min:6|max:12',
        //   'nwPwd' => 'bail|required|min:6|max:12',
        //   'nwPwdCnfm' => 'bail|required|min:6|max:12',
        // ]);

        // if ($validator->fails()){
        //   $messages = $validator->messages();
        //   return response()->json($messages, 400);
        // }
        // else{
        //     $data = ['id' => $empId,'nwPwd' => $request->nwPwd,'currPwd' => $request->currPwd];
        //     $rtrn = UserModel::updateUserPassword($data);
        //     return response()->json(array('status'=> 'OK'), 200);
        // }
        $errStr = '';
        $status = '';
        if(!$request -> currPwd){
          $errStr = $errStr.'1:';
        }
        if(!$request -> nwPwd){
          $errStr = $errStr.'2:';
        }
        if(!$request -> nwPwdCnfm){
          $errStr = $errStr.'3:';
        }
        if($request -> nwPwd && $request -> nwPwdCnfm){
          if($request -> nwPwd !== $request -> nwPwdCnfm){
            $errStr = $errStr.'4:';
          }
        }
        if(!$errStr){
          $data = ['id' => $empId,'nwPwd' => $request->nwPwd,'currPwd' => $request->currPwd];
          $rtrn = UserModel::updateUserPassword($data);
          if($rtrn && $rtrn === 'OK'){
            $status = 'OK';
          }
          else{
            $errStr = 'ERROR:';
          }
        }
        return response()->json(array('status'=> $status,'error' => $errStr), 200);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function getUserAttendance(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
            return view('/user/attendance',array('empName' => $sessionData["employeeName"]));
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function getEmpPunchDetails(){
    if(Session::get('userdata')){
        $sessionData = Session::get('userdata');
        $empId = $sessionData["userid"];
        $attendance = AttendanceModel::GetEmployeeAttendanceDetails($empId);
        return response()->json(array('punchInfo'=> $attendance), 200);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function AddAttendance(Request $request){
    if(Session::get('userdata')){
        $sessionData = Session::get('userdata');
        $empId = $sessionData["userid"];
        $time = Carbon::now() -> toDateTimeString();

        switch ($request['type']) {
            case 1:
                  $notes = $request['notes'] ? $request['notes']: '';
                  $punchData = ['EmpId' => $empId,'PunchinTime' => $time,'Notes' => $notes ];
                  $attId = AttendanceModel :: AddAttendance($punchData,$request['type']);

                  return response()->json(array('status'=> "OK",'punchInTime' => $time,'attId' => $attId), 200);
              break;
            case 2:
                  $attId = $request['attId'];
                  $notes = $request['notes'] ? $request['notes']: '';
                  $punchInTime = $request['punchInTime'] ? $request['punchInTime']: '';
                  $punchData = ['ID' => $attId, 'EmpId' => $empId,'PunchoutTime' => $time ,'Notes' => $notes ];
                  AttendanceModel :: AddAttendance($punchData,$request['type']);

                  return response()->json(array('status'=> "OK"), 200);
              break;
            default:
             break;
        }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function SearchAttendance(Request $request){
    if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          $empId = $sessionData["userid"];

          $searchData = [ 'FromDate' => $request['StartDate'],'ToDate' => $request['EndDate'],'EmpId' => $empId ];
          $searchResult = AttendanceModel::GetEmpPunchDetails($searchData,false);

          return response()->json(array('searchData'=> $searchResult), 200);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function getApplyLeave(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
            $leavetypes = LeaveModel::GetLeaveTypes();
            return view('/user/leave',['leavetypes' => $leavetypes,'empName' => $sessionData["employeeName"]]);
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function AddLeave(Request $request){
    if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          $empId = $sessionData["userid"];
          $rtrnStr = '';
          $frmDt = Carbon::now();
          $toDt = Carbon::now();

          if($request){
            if(!$request->leaveType){
              $rtrnStr = $rtrnStr.'1';
            }
            if(!$request->fromDate){
              $rtrnStr = $rtrnStr.'2';
            }
            if(!$request->toDate){
              $rtrnStr = $rtrnStr.'3';
            }
            if($request->fromDate && $request->toDate){
              $frmDt = Carbon::parse($request->fromDate);
              $toDt = Carbon::parse($request->toDate);

              if($toDt < $frmDt){
                $rtrnStr = $rtrnStr.'4';
              }
            }
            if(!$request->leaveDurType){
              $rtrnStr = $rtrnStr.'5';
            }

            if(!$rtrnStr){
              $flag = false;
              $rtrnArr = LeaveModel::CalculateLeaveStatistics($frmDt,$toDt,$request->LvTypStr,$request->lvDurTypStr,$empId);

              if($rtrnArr && count($rtrnArr) > 0){
                $flag = $rtrnArr['flag'];
              }
              if($flag){
                //enough leaves
                $lvData = ['EmpId' => $empId,'FromDate' => $frmDt,'ToDate' => $toDt,
                            'LeaveType' => $request -> leaveType,'Comments' => ($request -> comments)?$request -> comments:"",
                            'DurationType' => $request -> lvDurTypStr,'Status' => 1,
                            'IsCancel' => $request -> isCancel,'IsRejected' => false,'lvId' => $request -> leaveId,
                            'lvTypStr' => $request->LvTypStr];

                 if($request -> leaveId){
                   $rtrnStr = LeaveModel::UpdateLeave($lvData,$rtrnArr);
                 }
                 else{
                  $rtrnStr = LeaveModel::AddLeave($lvData,$rtrnArr);
                 }
              }
              else{
                //No enough leaves
                $rtrnStr = $rtrnStr.'6';
              }
            }
          }
        return response()->json(array('returnData'=> $rtrnStr), 200);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function GetLeaveStatistics(){
    if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          $empId = $sessionData["userid"];

          $leaveStatistics = LeaveModel::GetLeaveStatistics($empId);
          return response()->json(array('leaveData'=> $leaveStatistics), 200);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function getLeaveReports(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
            $leavetypes = LeaveModel::GetLeaveTypes();
            return view('/user/leavereports',['leavetypes' => $leavetypes,'empName' => $sessionData["employeeName"]]);
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function postLeaveReports(Request $request){
    if(Session::get('userdata')){
          $sessionData = Session::get('userdata');
          $empId = $sessionData["userid"];
          $role = $sessionData["userrole"];

          $month = $request -> month;
          $year = $request -> year;

          $frmDt = Carbon::createFromDate($year, $month, 1);
          $toDt = Carbon::createFromDate($year, $month, $frmDt->daysInMonth)->addDays(1);

          $searchData = ['EmpId' => $empId,'FromDate' => $frmDt->toDateString(),'ToDate' => $toDt->toDateString()];
          $leaveData = LeaveModel::GetEmployeeLeaveDetails($role,$searchData,false);
          return response()->json(array('leaveData'=> $leaveData), 200);
    }
    else{
      return Redirect::to('/');
    }
  }

  public function getEmployeeReports(){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
            return view('/user/employeereports',array('empName' => $sessionData["employeeName"]));
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }

  public function postUserReport(Request $request){
    if(Session::get('userdata')){
      $sessionData = Session::get('userdata');
      switch ($sessionData["userrole"]) {
        case 1:
           return Redirect::to('/employees');
          break;
        case 2:
            $userrole = $sessionData["userrole"];
            $empId = $sessionData["userid"];

            $month = $request -> month;
            $year = $request -> year;

            $frmDt = Carbon::createFromDate($year, $month, 1);
            $toDt = Carbon::createFromDate($year, $month, $frmDt->daysInMonth)->addDays(1);

            $searchData = ['EmpId' => $empId,'FromDate' => $frmDt,'ToDate' => $toDt];

            $punchList = AttendanceModel::GetEmpPunchDetails($searchData,true);
            $lvList = LeaveModel::GetEmployeeLeaveDetails($userrole,$searchData,true);

            if($punchList && $lvList){
              $lvcount = 0.0;$attnCnt = 0.0;
              $yearLst;
              $lvLst;
              foreach ($lvList as $lvItem) {
                  $lvLst = $lvItem['RtrnArry']['leaves'];
                  $lvcount = $lvcount + $lvLst;
              }

              foreach ($punchList as $attItem) {
                  $attnCnt = $attnCnt + $attItem->Duration;
              }             

              $UserReportModel['totalDays'] = $frmDt->daysInMonth;
              $UserReportModel['holidays'] = $UserReportModel['totalDays'] - Helper::GetBusinessDaysCount($frmDt, $toDt);
              $UserReportModel['leaveDays'] = $lvcount;
              $UserReportModel['workingDays'] = $UserReportModel['totalDays'] - $UserReportModel['holidays'];
              $UserReportModel['workingHours'] = $attnCnt;
              $UserReportModel['activeDays'] = number_format(($attnCnt / 8), 2, '.', ',');
              
              return response()->json(array('userReport'=>$UserReportModel),200);
            }
          break;
      }
    }
    else{
      return Redirect::to('/');
    }
  }
}
