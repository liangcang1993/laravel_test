<?php

namespace App\Models;

use App\Http\Controllers\Admin\ConfigController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Models\Sso;
use DB;
use Config;
use Redis;
use App\Services\FileService;

use Storage;
use Log;


class UserReceiveTools extends BaseModel
{
    protected $table = 'user_receive_tools';

    public static function recordToolsDeal($request,$user_id,$udid){
        if (!isset($request)){
            return null;
        }



        $model = new UserReceiveTools();
        $model->user_id = $user_id;

        $model->type = $request['type'];
        $model->num = $request['num'];
        $model->param1 = $request['param1'];
        $model->param2 = $request['param2'];
        $res = $model->save();

        if ($res){
            return 1;
        }else{
            return 0;
        }
    }

    public static function isReceive($user_id){
        $model = UserReceiveTools::where('user_id',$user_id)
            ->orderBy('created_at','desc')
            ->first();
        if ($model){
            $data['lasttime'] = strtotime($model->created_at);
            $data['now'] = time();
        }else{
            $data['lasttime'] = '';
            $data['now'] = time();
        }

        $data['config'] = json_decode(Redis::hget(RedisKey::CONFIG,'iosjigsawsvip_dailygift'));
        if (empty($data['config'])){
            ConfigController::addToRedis();
        }
        return $data;
    }
}

