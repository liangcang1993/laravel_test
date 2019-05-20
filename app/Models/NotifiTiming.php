<?php

namespace App\Models;

use Aws\Sns\SnsClient;

class NotifiTiming extends BaseModel
{
    protected $table = 'notifi_timing';

    public static function getList($app,$type){
        return self::onWriteConnection()->where('type' , $type)->
                                            where('app' , $app)->
                                            where('status' , 0)->
                                            get();
    }
    public static function getCreateDay($udid){
        $user = self::onWriteConnection()->where('udid',$udid)->first();
        $day = date('Y-m-d',time())-date('Y-m-d',strtotime($user->created_at));
        return $user->create_at;
    }
}
