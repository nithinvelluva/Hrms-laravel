<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class AttendanceModel extends Model
{
  public static function GetEmployeeAttendanceDetails($empId){
    $attendance = DB::table('attendance')
                   ->where('EmpId','=',$empId)
                   ->limit(1)
                    ->orderBy('PunchinTime','desc')
                   ->get(['ID','PunchinTime','PunchoutTime']);
    return $attendance;
  }

  public static function AddAttendance($punchData,$type){
      if($type === 1){
        $attId = DB::table('attendance')->insertGetId($punchData);
        return $attId;
      }
      else if($type === 2){
        DB::table('attendance')->where('ID',$punchData['ID'])->update($punchData);
        return 0;
      }
  }

  public static function GetEmpPunchDetails($searchData,$isReport = false){
        $sdate = "";
        $edate = "";
        if (!is_null($searchData['FromDate']) && !is_null($searchData['ToDate']))
        {
            $sdate = Carbon::parse($searchData['FromDate'])->format('Y-m-d');
            $edate = Carbon::parse($searchData['ToDate'])->addDays(1)->format('Y-m-d');
         }
        else if (is_null($searchData['ToDate']))
        {
           $sdate = Carbon::parse($searchData['FromDate'])->format('Y-m-d');
           $edate = Carbon::now();
        }
      if($isReport){
        $searchResult =  DB::table('attendance')
                       ->where('EmpId','=',$searchData['EmpId'])
                       ->whereBetween('PunchinTime', [$sdate,$edate])
                       ->orderBy('PunchinTime')
                       ->get(['PunchinTime','PunchoutTime']);
      }
      else{
          $searchResult =  DB::table('attendance')
                         ->where('EmpId','=',$searchData['EmpId'])
                         ->whereBetween('PunchinTime', [$sdate,$edate])
                         ->orderBy('PunchinTime')
                         ->get(['PunchinTime','PunchoutTime']);
        }
            if($searchResult){
                $pin = Carbon::now();
                $pout = Carbon::now();
              foreach ($searchResult as $resultItem) {

                if($resultItem -> PunchinTime){
                  $pin = Carbon::parse($resultItem -> PunchinTime);
                }
                if($resultItem -> PunchoutTime){
                  $pout = Carbon::parse($resultItem -> PunchoutTime);
                }
                else{
                    $pout = $pin;
                }
                $timespan =  $pout->diff($pin)->format('%H:%i');
                $resultItem -> Duration = $timespan;
              }
            }
      return $searchResult;
  }
}
