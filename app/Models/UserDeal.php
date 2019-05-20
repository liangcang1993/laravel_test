<?php

namespace App\Models;

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Models\Sso;
use DB;
use Config;
use Redis;
use App\Services\FileService;

use Storage;
use Log;


class UserDeal extends BaseModel
{
    protected $table = 'user_deal';

    public static function recordDeal($req,$user_id){
        if (!isset($req->data)){
            return null;
        }

        if ('local'!=getenv('APP_ENV')){
            $request = User::requestDecrypt($req->data,$user_id);
            $udid = User::headerDecrypt($req->headers->get('udid'));
        }else{
            $request = json_decode($req->data,true);
            $udid = $req->headers->get('udid');
        }

        if ('' == $request['type']){
            return 0;
        }elseif ('tools' === $request['type']){
            $res = UserReceiveTools::recordToolsDeal($request,$user_id,$udid);
            if ($res){
                return 1;
            }else{
                return 0;
            }
        }
        $model = new UserDeal();
        $model->user_id = $user_id;
        $model->udid = $udid;
        $model->type = $request['type'];
        $model->num = $request['num'];
        $model->param1 = $request['param1'];
        $model->param2 = $request['param2'];
        $model->deal_time = $request['deal_time'];
        $res = $model->save();

        if ($res){
            //return 1; //edit by wjy
            return $request;
        }else{
            return 0;
        }
    }
}
