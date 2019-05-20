<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

use Response;
use App\Http\Models\Sso;
use Log;
use Input;
use Session;

abstract class ApiBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function returnJsonResult($code=0, $msg='success', $data=[])
    {
        echo json_encode(array('code' => $code, 'message' => $msg, 'data' => $data));
        die();
    }

    // add by wjy
    protected function getUid()
    {   
        if (env("APP_ENV") === 'local') {
            return 123;
        }

        $uid = $this->checkToken(@$_SERVER['HTTP_TOKEN'],@$_SERVER['HTTP_UDID'],@$_SERVER['HTTP_UID']);

        return $uid;

    }

    protected function checkToken($token='',$udid,$uid,$check=1){
        if (1==$check){
            if (empty($udid)||empty($token)||empty($uid)){
                $this->returnJsonResult(0,'success',[]);
            }
        }
        if (empty($udid)&&empty($token)&&empty($uid)){//token、udid、uid均为空直接返回
            $this->returnJsonResult(2,'token invalid');
        }
        if (!empty($token)){
            $token = User::headerDecrypt($token);
        }
        if (!empty($udid)){
            $udid = User::headerDecrypt($udid);
        }
        if (!empty($uid)){
            $uid = User::headerDecrypt($uid);
        }
//        if (empty($token)||empty($uid)){//直接创建新用户
//            $user_id = User::addUser($udid);
//        }else{
        $model = UserDevice::where('udid',$udid)->orderBy('created_at', 'desc')->first();

        if (!$model){//设备未发现，创建新用户
            $user_id = User::addUser($udid);
        }else{
            $user_id = $model->user_id;
        }
//        }
        return $user_id;
    }

    protected function getDeviceId($udid){
        if (!empty($udid)){
            if (env("APP_ENV") != 'local') {
                $udid = User::headerDecrypt($udid);
            }
            $model = UserDevice::where('udid',$udid)->orderBy('created_at', 'desc')->first();
            if ($model){
                return $model->id;
            }
        }else{
            $this->returnJsonResult(2,'token invalid');
        }

    }

    protected function getUuid($request){
        $clt = Input::cookie('clt', Input::get('clt', ''));
        if($clt == '2'){
            $params['clt'] = 'android';
        }else{
            $params['clt'] = 'ios';
        }
        if($params['clt'] == 'android'){
            $uuid =  Input::cookie('uuid', Input::get('uuid'));
        }else{
            $uuid =  Input::get('udid', $request->headers->get('udid', ''));
        }
        return $uuid;
    }

    protected function getQueryString($queryString){
        $a = explode('&', $queryString);
        foreach ($a as $key => $t) {
            if (starts_with($t, 'udid')){
                $queryString = str_replace('&'. $t, '', $queryString);
                return $queryString;
            }
        }

        return $queryString;
    }

    protected function isChina($req){
         
        $country = Input::get('country', $req->headers->get('country'));
        empty($country) && $country = Input::cookie('country');
        if(!empty($country)){
            if($country == 'cn' || $country == 'CN'){
               return 1;
            }else{
               return 0;
            }
        }

    }

    protected function isAsia($req){
         
        $country = Input::get('country', $req->headers->get('country'));
        empty($country) && $country = Input::cookie('country');

        if(!empty($country)){
            if(in_array($country, ['cn','CN','jp','JP','KR','kr','my','MY','sg','SG','tw','TW','HK','hk','mo','MO'])){
               return 1;
            }else{
               return 0;
            }
        }

    }
}
