<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;
use Hash;
use Carbon\Carbon;

class UserModel extends Model
{
  public static function validateUser($data){
    $user = DB::table('users')->where($data)->first();
    return $user;
  }

  public static function updateUser($userData,$profileData){
    $user = DB::table('users')->where('id',$userData['id'])->update($userData);
    $profile = DB::table('employeeinfo')->where('EmpId',$userData['id'])->update($profileData);
    return true;
  }

  public static function updateUserPhoto($profileData){
    $profile = DB::table('employeeinfo')->where('EmpId',$profileData['EmpId'])->update($profileData);
    return true;
  }

  public static function updateUserPassword($data){
    $userdata = DB::table('users')->where('id',$data['id'])->get(['password']);
    if(empty($userdata)){
      return 'ERROR';
    }
    else{
      $stordPwd = $userdata[0]-> password;
      if (Hash::check($data['currPwd'], $stordPwd)){
        DB::table('users')->where('id',$data['id'])->update(['password' => bcrypt($data['nwPwd'])]);
        return 'OK';
      }
    }
    return 'ERROR';
  }

  public static function resetUserPassword($data){
    DB::table('users')->where('id',$data['id'])->update(['password' => bcrypt($data['password'])]);   
  }

  public static function createResetPasswordToken($data){
    DB::table('password_reset_token')
    ->where('EmpId',$data['EmpId'])
    ->insert($data);
  }

  public static function validateResetPasswordToken($tokenData){
    $isValid = false;      
    if($tokenData){
      $token = DB::table('password_reset_token')
      ->where($tokenData)
      ->first();       

      if(!empty($token)){          
        $created_at = $token->created_at; 

        if(Carbon::now() <= Carbon::parse($created_at)){            
          $isValid = true;
        }
      }
    }

    return $isValid;
  }

  public static function updateResetPasswordToken($tokenData){
    DB::table('password_reset_token')
    ->where($tokenData)
    ->update(['created_at' => Carbon::now()]);
  }
}
