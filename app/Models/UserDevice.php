<?php

namespace App\Models;

use App\Http\Models\Sso;
use DB;
use Config;
use Illuminate\Database\Eloquent\SoftDeletes;
use Redis;
use App\Services\FileService;

use Storage;
use Log;


class UserDevice extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'user_device';

    public static function getToken($id){
        $model = UserDevice::onWriteConnection()->where('user_id',$id)->orderBy('updated_at','desc')->first();
        $res['updated_by'] = $model->id;
        $res['token'] = $model->token;

        return $res;
    }

    public static function getUpdatedBy($udid){
        $model = UserDevice::where('udid',$udid)->first();

        return $model->id;
    }

}
