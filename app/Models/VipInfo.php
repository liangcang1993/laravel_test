<?php

namespace App\Models;

use DB;
use Redis;

class VipInfo
{
    protected $table = 'vip_info';

    protected static $fieldL = ['uid','expired_at'];    
 
    public static function add($dt)
    {
        $fieldL = SELF::$fieldL;

        foreach ($fieldL as $field) {

            if (!empty($dt[$field])) {
                $addDt[$field] = $dt[$field];
            }
        }

        $addDt['created_at'] = $addDt['updated_at'] = date('Y-m-d H:i:s');

        $res = DB::table('vip_info')->insert($addDt);

        return $res;
    }

    /*
      param dt['map'] 作where条件，其余字段作update的字段
    */ 
    public static function update($dt)
    {
        $fieldL = SELF::$fieldL;

        foreach ($fieldL as $field) {

            if (!empty($dt[$field])) {
                $updateDt[$field] = $dt[$field];
            }
        }

        $updateDt['updated_at'] = date('Y-m-d H:i:s');

        $res = DB::table('vip_info')
        ->where($dt['map'])
        ->update($updateDt);

        return $res;
    }

    /*
      @param field [array]
      @param where [array]
    */
    public static function find($field=['*'],$where)
    {   
        $field = implode(',', $field);//here

        $info = DB::table('vip_info')
        ->select($field)
        ->where($where)
        ->get();

        $info = obj_to_arr($info);

        return $info;
    }

    public static function addToRedis($dt)
    {
        $fieldL = SELF::$fieldL;

        foreach ($fieldL as $field) {

            if (!empty($dt[$field])) {
                $addDt[$field] = $dt[$field];
            }
        }

        $key = "vip_info:${addDt['uid']}";
        $res = Redis::hMset($key, $addDt);

        return $res;
    }

    public static function getInfoFromRedis($dt)
    {
        $key = "vip_info:${dt['uid']}";
        $res = Redis::hgetall($key);

        return $res;
    }

    public static function getInfoFromTb($dt)
    {
        
        $where['uid'] = $dt['uid'];
        $info = @SELF::find(['*'], $where)[0];

        if (empty($info['expired_at'])) {// if db has not vip info, add no info to redis
            $info['expired_at'] = '0000-00-00 00:00:00';
            $info['uid'] = $dt['uid'];
        }

        // add to redis
        SELF::addToRedis($info);

        return $info;
    }

    public static function isVipUser($uid)
    {   

        // get from redis
        $vInfo = @SELF::getInfoFromRedis(['uid'=>$uid]);

        if (empty($vInfo)) {// get from db
            $vInfo = @SELF::getInfoFromTb(['uid'=>$uid]);
        }

        if ($vInfo && @$vInfo['expired_at'] > date('Y-m-d H:i:s')) {
            return ['is_vip'=>1,'expired_at'=>$vInfo['expired_at']];
        }

        return ['is_vip'=>0,'expired_at'=>'0000-00-00 00:00:00'];

    }



}
