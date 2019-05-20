<?php

namespace App\Models;

use Redis;
use DB;

class RedisToMysql 
{ 
    const INSERT_DATA = 'insertData';

//    const USER_ACTION    	= 'user_action';
//    const PAY_ACTION     	= 'pay_action';
    const MATERIAL_USED   	= 'material_used';
    const AD_COLLECT   		= 'ad_collect';
    const LOAD_STATE   		= 'load_state';
    const LOG_EVENT   		= 'log_event';


    public static function getTables(){
        return [
//            self::USER_ACTION,
//            self::PAY_ACTION,
            self::AD_COLLECT,
            self::MATERIAL_USED,
            self::LOG_EVENT,
            self::LOAD_STATE
        ];
    }

    public static function addToQueue($model){
        $data = json_encode($model->getAttributes());
        Redis::rpush($model->getTable(), $data);
    }

    public static function run(){

        $tables = self::getTables();
        foreach ($tables as $key => $table) {
            
            $datas = [];
            $item = Redis::lpop($table);
            $n = 0;
            while (!empty($item)) {
                $datas[] = json_decode($item, 1);
                $item = Redis::lpop($table);
                ++$n;
                if ($n > 1000){
					DB::table($table)->insert($datas);
					unset($datas);
					$n = 0;
				}
            }
//            $start_time = time();
            DB::table($table)->insert($datas);
//            $end_time = time();
//            $time = $end_time - $start_time;
//            echo  count($datas) . $table . ":" . $time . "\n";
        }
   }
}
