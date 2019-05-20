<?php

namespace App\Models;

use Redis;
 
class UserAction extends BaseModel
{
    protected $table = 'user_action';
    public $timestamps = false;

    public static function isPay($udid){
        $res = self::where('udid',$udid)->where('type',2)->first();
        if ($res){
            return 1;
        }else{
            return 0;
        }
    }
    public static function getList($udid){
        return self::select("*")->where('udid','=',"$udid" )->get()->toArray();
    }
    public static function selectType($date,$endDate,$app =''){
        if(!empty($app))
        {
            return self::where('created_at','>=',"$date" )->where('created_at','<',$endDate)->where('type','=',1)->where('app','=',$app)->count();
        }else{
            return self::where('created_at','>=',"$date" )->where('created_at','<',$endDate)->where('type','=',1)->count();
        }
    }

   

}
