<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Validator;


class ApisController extends Controller
{
    public function servicedata(Request $request){
        try{

            $service_app_data=$request->service_app_data;
            $obj = json_decode($service_app_data, true); 
            // return $obj;
            $LAT=$obj['Lat'];
            $LAN=$obj['lan'];
            if($LAT==0 && $LAN==0){
                return response()->json([
                    'success'=> false,
                    'message'=> 'Lat and lan are 0'
                ]);
            }
            $SCANID=$obj['UUIDphone'];
            // return $obj['Lat'];
            $current_time = Carbon::now()->toDateTimeString();

            if(is_array($obj['detailsBT'])){
                $valuesArr = array();
                foreach($obj['detailsBT'] as $row){
        
                    $uuid=$row['uuid'] ;
                    $maj_num=$row['major'] ;
                    $min_num= $row['minor'] ;
                    $mp= $row['txPower'] ;
                    $mac=$row['mac'] ;
                    $rssi= $row['rssi'] ;
        
                    // $query = "INSERT INTO `ServiceApp_reciver` (SCANNERID,UUID,MAC,RSSI,TIME,LATITUDE,LONGITUDE,MAJOR_VALUE,MINOR_VALUE,MEASURED_POWER)values('$SCANID','$uuid','$mac','$rssi','$current_Date','$LAT','$LAN','$maj_num','$min_num','$mp')";
                    
                    DB::table('serviceapp')->insert([
                        'SCANNERID'         => $SCANID,
                        'UUID'              => $uuid,
                        'MAC'               => $mac,
                        'RSSI'              => $rssi,
                        'TIME'              => $current_time,
                        'LATITUDE'          => $LAT,
                        'LONGITUDE'         => $LAN,
                        'MAJOR_VALUE'       => $maj_num,
                        'MINOR_VALUE'       =>$min_num,
                        'MEASURED_POWER'    =>$mp,
                        'created_at'        =>$current_time,
                    ]);
    
                    return response()->json([
                        'success'=> true,
                        'message'=> 'success'
                    ]);
            
                }
            }

        }
        catch(Exception $e)
        {
        Log::error($e->getMessage());
        return response()->json([
        'error'=> $e->getMessage(),
       'response' => $e],400);
        }
    }

    public function getdata(Request $request){
        try{
            $rules = [
                'mac'      => 'required'
            ];
    
            $input = $request->only(
                'mac'
            );
            $validator = Validator::make($input, $rules);
            if($validator->fails()) {
                $error = $validator->messages()->toJson();
                return response()->json(['success'=> false, 'error'=> $error]);
            } 

            $sql=DB::select("SELECT MAC, LONGITUDE, LATITUDE, TIME, SCANNERID, UUID, RSSI
            FROM serviceapp
            WHERE MAC ='$request->mac' ORDER BY ID DESC LIMIT 1"
            );
        if(empty($sql)){
            return response()->json(['success'=> false,
            'message'=> "mac id doesn't exits"]);
        }  
        $current_date = Carbon::today()->format('Y-m-d');
        $sql2=DB::select("SELECT MAC, LONGITUDE, LATITUDE,TIME FROM serviceapp
        where MAC ='$request->mac' and date(TIME)='$current_date' ORDER BY ID asc LIMIT 1" 
            );
        $mac=$sql[0]->MAC;
        $lan=$sql[0]->LONGITUDE;
        $lat=$sql[0]->LATITUDE;
        $time=$sql[0]->TIME;
        $lan_st=$sql2[0]->LONGITUDE;
        $lat_st=$sql2[0]->LATITUDE;
        $time_st=$sql2[0]->TIME;

            return response()->json([
                'success'          => true,
                'MAC'              => $mac,
                'LONGITUDE'        => $lan,
                'LATITUDE'         => $lat,
                'Time'             => $time,
                'LONGITUDE_start'  => $lan_st,
                'LATITUDE_start'   => $lat_st,
                'TIME_start'       => $time_st,
                'message'          =>"success"
            ]);

        }
        catch(Exception $e)
        {
        Log::error($e->getMessage());
        return response()->json([
        'error'=> $e->getMessage(),
       'response' => $e],400);
        }
    }
}
