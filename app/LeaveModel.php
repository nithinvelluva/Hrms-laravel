<?php

namespace App;
use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Helper;

class LeaveModel extends Model
{
    public static function GetLeaveTypes(){
      $leaveTypes = DB::table('leavetype')
                    ->get(['Id','Type']);
      return $leaveTypes;
    }

    public static function GetLeaveStatistics($empId){
      $year = Carbon::now()->year;
      $condition = ['EmpId' => $empId,'Year' => $year];
      $result = DB::table('leavestatistics')
                     ->where($condition)
                     ->get(['CasualLeave','FestiveLeave','SickLeave','LossOfPay']);
      return $result;
    }

    public static function GetEmployeeLeaveDetails($role,$lvData,$isReport){
      $applLeaveData = [];
      if($role === 1){

      }
      else{
        if($isReport){//report
        }
        else{
          $applLeaveData = DB::table('employeeleaveinfo AS eli')
                            ->join('leavetype AS lt','eli.LeaveType', '=', 'lt.Id')
                            ->where(['EmpId' => $lvData['EmpId']])
                            ->whereBetween('eli.FromDate', [$lvData['FromDate'],$lvData['ToDate']])
                            ->orWhereBetween('eli.ToDate', [$lvData['FromDate'],$lvData['ToDate']])
                            ->orderBy('eli.FromDate')
                            ->get(['eli.EmpId','eli.FromDate','eli.ToDate','lt.Type','eli.Status',
                                  'eli.Comments','eli.Id AS LeaveID','eli.DurationType','lt.Id AS LeaveTypeID']);
        }
      }

      $newValues = array();
      if($applLeaveData){
          foreach ($applLeaveData as $lvItem) {
              $lvItem->FromDate = explode(' ',$lvItem->FromDate)[0];
              $lvItem->ToDate = explode(' ',$lvItem->ToDate)[0];
              $lvItem->leaveDurTypeInt = ($lvItem->DurationType === 'Full Day')? 1:2;

              $lvItems = (array) $lvItem;
              $lvItems['RtrnArry'] = LeaveModel::CalculateLeaveStatistics(Carbon::parse($lvItem->FromDate),
                                                    Carbon::parse($lvItem->ToDate),$lvItem->Type,
                                                    $lvItem->DurationType,$lvData['EmpId']);
            switch ($lvItem->Status) {
              case -1:
                  $lvItems['_status'] = false;
                  $lvItems['_rejected'] = true;
                  $lvItems['_cancelled'] = false;
                  $lvItems['_leaveStatus'] = "REJECTED";
                break;
              case 1:
                  $lvItems['_status'] = false;
                  $lvItems['_rejected'] = false;
                  $lvItems['_cancelled'] = false;
                  $lvItems['_leaveStatus'] = "PENDING";
                  break;
              case 2:
                  $lvItems['_status'] = true;
                  $lvItems['_rejected'] = false;
                  $lvItems['_cancelled'] = false;
                  $lvItems['_leaveStatus'] = "APPROVED";
                  break;
              case 3:
                  $lvItems['_status'] = false;
                  $lvItems['_rejected'] = false;
                  $lvItems['_cancelled'] = true;
                  $lvItems['_leaveStatus'] = "PENDING";
                  break;
              default:
                break;
            }

            $newValues[] = $lvItems;
          }
      }
        return $newValues;
    }

    public static function CalculateLeaveStatistics($from, $to, $leaveType, $LeaveDurationType, $empId){
            $startYear = $from->year;
            $endYear = $to->year;
            $prvLvs = 0.0;
            $nxtLvs = 0.0;
            $leaves = 0.0;
            $NoLeaves = 0.0;
            $flag = false;
            $rtrnArr = [];

            if ($startYear != $endYear){
                //different year
                $lastDay = Carbon::create($startYear, 12, 31,0,0,0);
                $firstDay = Carbon::create($endYear, $to->month, 1,0,0,0);
                $prvFlag = false;
                $nxtFlag = false;

                if ($LeaveDurationType === "Half Day"){
                    //previous year
                    $prvLvs = Helper::GetBusinessDaysCount($from, $lastDay);
                    $prvLvs = 0.5 * $prvLvs;
                    //next year
                    $nxtLvs = Helper::GetBusinessDaysCount($firstDay, $to);
                    $nxtLvs = 0.5 * $nxtLvs;
                }
                else if ($LeaveDurationType === "Full Day"){
                    //previous year
                    $prvLvs = Helper::GetBusinessDaysCount($from, $lastDay);
                    //next year
                    $nxtLvs = Helper::GetBusinessDaysCount($firstDay, $to);
                }

                $NoLeaves = LeaveModel::EnoughLeaves($empId, $leaveType, $startYear);

                if(floatval($NoLeaves) === 0.0 || floatval($prvLvs) > floatval($NoLeaves)){
                    $prvFlag = false;
                }
                else if ($prvLvs <= $NoLeaves && $NoLeaves >= 0){
                    $prvFlag = true;
                }

                $NoLeaves = LeaveModel::EnoughLeaves($empId, $leaveType, $endYear);

                if (floatval($NoLeaves) === 0.0 || floatval($nxtLvs) > floatval($NoLeaves)){
                    $nxtFlag = false;
                }
                else if (floatval($nxtLvs) <= floatval($NoLeaves) && floatval($NoLeaves) >= 0){
                    $nxtFlag = true;
                }

                if (!$prvFlag || !$nxtFlag){
                    $flag = false;
                }
                else if ($prvFlag && $nxtFlag){
                    $flag = true;
                }
                $yearArr = [$startYear,$endYear];
                $leavesArr = [$prvLvs,$nxtLvs];
                $rtrnArr = ['yearArr' => $yearArr,'leaves' => $leavesArr,'flag' => $flag];
            }
            else{
              //same year
              if ($LeaveDurationType === "Half Day")
              {
                  $leaves = Helper::GetBusinessDaysCount($from, $to);
                  $leaves = 0.5 * $leaves;
              }
              else if ($LeaveDurationType === "Full Day")
              {
                  $leaves = Helper::GetBusinessDaysCount($from, $to);
              }
              $NoLeaves = LeaveModel::EnoughLeaves($empId, $leaveType, $startYear);

              if (floatval($NoLeaves) === 0 || floatval($leaves) > floatval($NoLeaves)){
                  $flag = false;
              }
              else if (floatval($leaves) <= floatval($NoLeaves) && floatval($NoLeaves) >= 0){
                  $flag = true;
              }
              $rtrnArr = ['yearArr' => $startYear,'leaves' => floatval($leaves),'flag' => $flag];
            }
          return $rtrnArr;
        }

    public static function EnoughLeaves($empId,$LeaveTypeStr,$startYear){
                $colName = "";
                $NoLeaves = 0.0;
                switch (trim($LeaveTypeStr))
                {
                    case "Casual":
                        $colName = "CasualLeave";
                        break;
                    case "Festive":
                        $colName = "FestiveLeave";
                        break;
                    case "Sick":
                        $colName = "SickLeave";
                        break;
                    default:
                        break;
                }
                $rtrnData = DB::table('leavestatistics')
                            ->where(['EmpId' => $empId],['Year' => $startYear])
                            ->first();
                if($rtrnData){
                    $NoLeaves = $rtrnData -> $colName;
                }
            return $NoLeaves;
    }

    public static function AddLeave($lvData,$rtrnArr){
      if($lvData){
        $existId = 0;
        $flag = false;
        $status = 1;

        $whereCondData = ['FromDate' => $lvData['FromDate'] ,'ToDate' => $lvData['ToDate'],'EmpId' => $lvData['EmpId']];
        $dupCheck = DB::table('employeeLeaveInfo')
                    ->where($whereCondData)
                    ->whereNotIn('Status',[3,-1])
                    ->first(['Id']);
          if($dupCheck){
              $existId = $dupCheck -> Id;
          }
          if($existId > 0){
            return "EXISTS";
          }
          else{
            $insertData = array();
            $insertData = $lvData;
            unset($insertData['IsCancel'],$insertData['IsRejected'],$insertData['lvId'],$insertData['lvTypStr']);

            DB::table('employeeLeaveInfo')->insert($insertData);
            $lvData['RtrnArr'] = $rtrnArr;
            LeaveModel::UpdateLeaveStatistics($lvData,null);
            return "OK";
          }
      }
    }

    public static function UpdateLeave($lvData,$rtrnArr){
      if($lvData){
        $existId = 0;
        $flag = false;
        $status = 1;

        $whereCondData = ['FromDate' => $lvData['FromDate'] ,'ToDate' => $lvData['ToDate'],'EmpId' => $lvData['EmpId']];
        $dupCheck = DB::table('employeeleaveinfo')
                    ->where($whereCondData)
                    ->where('Id','!=',$lvData['lvId'])
                    ->whereNotIn('Status',[3,-1])
                    ->first(['Id']);
          if($dupCheck){
              $existId = $dupCheck -> Id;
          }
          if($existId > 0){
            return "EXISTS";
          }
          else{

            $exisLvData = DB::table('employeeleaveinfo')
                          ->where(['Id' => $lvData['lvId']],['EmpId' => $lvData['EmpId']])
                          ->first(['FromDate','ToDate','DurationType']);
            if($exisLvData){
              $frmdt = Carbon::parse($exisLvData->FromDate);
              $todt = Carbon::parse($exisLvData->ToDate);
              $LvDurType = $exisLvData->DurationType;

              $rtrnArrExis = LeaveModel::CalculateLeaveStatistics($frmdt, $todt, $lvData['lvTypStr'], $LvDurType, $lvData['EmpId']);

              if($lvData['IsCancel']){
                DB::table('employeeleaveinfo')
                    ->where(['EmpId' => $lvData['EmpId'],'Id' => $lvData['lvId']])
                    ->update(['FromDate' => $lvData['FromDate'],'ToDate' => $lvData['ToDate'],
                              'DurationType' => $lvData['DurationType'],'Status' => 3,'Comments' => $lvData['Comments']]);
              }
              else{
                DB::table('employeeleaveinfo')
                    ->where(['EmpId' => $lvData['EmpId'],'Id' => $lvData['lvId']])
                    ->update(['FromDate' => $lvData['FromDate'],'ToDate' => $lvData['ToDate'],
                              'DurationType' => $lvData['DurationType'],'Comments' => $lvData['Comments']]);
              }

              $lvData['RtrnArr'] = $rtrnArr;
              LeaveModel::UpdateLeaveStatistics($lvData,$rtrnArrExis);

              return "OK";
            }
          }
      }
    }

    public static function UpdateLeaveStatistics($lvData,$rtrnArrExis = null){
        $leaves = 0.0;
        $yearArr = array($lvData['RtrnArr']['yearArr']);
        $leaveArr = array($lvData['RtrnArr']['leaves']);

        if($rtrnArrExis){
              $exisYearArr = array($rtrnArrExis['yearArr']);
              $exisLeaveArr = array($rtrnArrExis['leaves']);
         }

        $lvInfo = ['casualLeave' => 0,'festiveLeave' => 0,'sickLeave' => 0,'yearArr' => $yearArr,'leaveArr' => $leaveArr];

        for ($i=0; $i < count($yearArr) ; $i++) {
          $leaves = $leaveArr[$i];
          $leaves = (empty($rtrnArrExis)) ? $leaveArr[$i] : $exisLeaveArr[$i];
          switch ($lvData['LeaveType']) {
            case 1://"Casual"
                    $lvInfo['casualLeave'] = $leaveArr[$i];
                    break;
            case 2://"Festive"
                    $lvInfo['festiveLeave'] = $leaveArr[$i];
                    break;
            case 3://"Sick"
                    $lvInfo['sickLeave'] = $leaveArr[$i];
                    break;
            default:
                    break;
          }

          $exisLvStatus = DB::table('leavestatistics')
                          ->where(['EmpId' => $lvData['EmpId']],['Year' => $lvData['FromDate']->year])
                          ->first(['CasualLeave','FestiveLeave','SickLeave']);

          if($exisLvStatus){
              $cl = 0.0;
              $fl = 0.0;
              $sl = 0.0;

              $cl = floatval($exisLvStatus -> CasualLeave) - $lvInfo['casualLeave'];
              $fl = floatval($exisLvStatus -> FestiveLeave) - $lvInfo['festiveLeave'];
              $sl = floatval($exisLvStatus -> SickLeave) - $lvInfo['sickLeave'];

              switch ($lvData['LeaveType']) {
                case 1://"Casual"
                        $cl = (!$lvData['IsCancel'] && !$lvData['IsRejected']) ?
                              ($lvData['lvId'] > 0 ? ($cl + $leaves) : $cl) : ($cl + 2 * $leaves);
                        break;
                case 2://"Festive"
                        $fl = (!$lvData['IsCancel'] && !$lvData['IsRejected']) ?
                              ($lvData['lvId'] > 0 ? ($fl + $leaves) : $fl) : ($fl + 2 * $leaves);
                        break;
                case 3://"Sick"
                        $sl = (!$lvData['IsCancel'] && !$lvData['IsRejected']) ?
                              ($lvData['lvId'] > 0 ? ($sl + $leaves) : $sl) : ($sl + 2 * $leaves);
                        break;
                default:
                        break;
              }
          }

          DB::table('leavestatistics')
              ->where(['EmpId' => $lvData['EmpId']],
                      ['Year' => $lvData['FromDate']->year])
              ->update(['CasualLeave' => $cl,'FestiveLeave' => $fl,'SickLeave' => $sl]);
        }
    }
}
