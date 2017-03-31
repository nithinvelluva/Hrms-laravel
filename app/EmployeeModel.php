<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use File;

class EmployeeModel extends Model
{
    public static function getEmployeeData($id){
      $employee = DB::table('employeeinfo AS ei')
                     ->join('users AS u', 'u.id', '=', 'ei.EmpId')
                     ->join('userrole AS up', 'u.usertype', '=', 'up.ID')
                     ->join('empdesignation AS ed', 'ei.EmpDesignation', '=', 'ed.ID')
                     ->where('ei.EmpId','=',$id)
                     ->first(['u.id AS EMPID','u.email', 'u.name','up.role AS UserRole',
                             'ei.EmpGender','ei.EmpPhone','ei.EmpDob',
                             'ei.EmpPhotoPath','ed.Designation','ei.EmpPhotoPath']);
      return $employee;
    }

    public static function addEmployee($userData,$profileData){
        $id = DB::table('users')->insertGetId($userData);
        $profileData = array_add($profileData,'EmpId',$id);

        DB::table('employeeinfo')->insert($profileData);

        $casual =  DB::table('leavetype')->where('Type','Casual')->first();
        $festive =  DB::table('leavetype')->where('Type','Festive')->first();
        $sick =  DB::table('leavetype')->where('Type','Sick')->first();

        $leaves = ['EmpId' => $id,'CasualLeave' => $casual->Count,'FestiveLeave' => $festive->Count,'SickLeave' => $sick->Count,'LossOfPay' => '0','Year' => '2017'];

        DB::table('leavestatistics')->insert($leaves);
    }

    public static function removeEmployee($empId){
      DB::table('leavestatistics')->where('id',$empId)->delete();
      DB::table('employeeleaveinfo')->where('EmpId',$empId)->delete();
      DB::table('attendance')->where('EmpId',$empId)->delete();
      $userPhoto = DB::table('employeeinfo')->where('EmpId',$empId)->get(['EmpPhotoPath']);
      if($userPhoto){
        $photoDir = '/images/uploads/';
        $path = '';
        foreach ($userPhoto as $item) {
          $path = $item -> EmpPhotoPath;
        }
        if(File::exists(public_path().$photoDir.$path)){
          File::delete(public_path().$photoDir.$path);
        }
      }
      DB::table('employeeinfo')->where('EmpId',$empId)->delete();
      DB::table('users')->where('id',$empId)->delete();
    }

    public static function getEmployeeList(){
      $employee = DB::table('employeeinfo AS ei')
                     ->join('users AS u', 'u.id', '=', 'ei.EmpId')
                     ->join('userrole AS up', 'u.usertype', '=', 'up.ID')
                     ->join('empdesignation AS ed', 'ei.EmpDesignation', '=', 'ed.ID')
                     ->where('u.usertype','!=','1')
                     ->orderBy('u.name')
                     ->get(['u.id','u.email', 'u.name','up.role AS UserRole',
                            'ei.EmpGender','ei.EmpPhone','ei.EmpDob','ei.EmpPhotoPath','ed.Designation','up.id AS RoleId','ed.ID AS DesigId']);
      return $employee;
    }

    public static function getRoleSelectList(){
      return  DB::table('userrole')
                  ->get(['id','role']);
    }

    public static function getEmployeeDesignationList(){
      return  DB::table('empdesignation')
                  ->get(['ID','Designation']);
    }
}
