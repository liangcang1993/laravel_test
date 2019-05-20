<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\ApiBaseController;

use App\Http\Controllers\Auth\PasswordController;
use App\Models\Banner;
use App\Models\Notifi;
use App\Models\User;

use App\Models\UserDeal;
use App\Models\UserGifLike;
use App\Models\UserGifFavorite;
use App\Models\UserGifUsed;
use App\Models\Gif;
use App\Models\Tag;
use App\Models\UserReceiveTools;
use App\Models\UserTagUsed;
use App\Models\UserFollow;
use App\Models\UserMessage;
use App\Models\UserGifShare;
use App\Models\NotifiToken;
use App\Models\UserStatistic;
use App\Models\UserPushStatistic;
use App\Models\UserDevice;
use App\Models\Adid;
use App\Models\RedisKey;
use App\Models\RedisToMysql;

use App\Logics\OrderLogics;

use App\Models\VipInfo;
use App\Services\FileService;


use App\Services\HttpService;
use Input;
use DB;
use Illuminate\Http\Request; 
use Log;
use Redis;
 

class  UserController extends ApiBaseController
{

    public function userInfo(Request $request){
        date_default_timezone_set('Asia/Shanghai');
        $udid = empty($request->headers->get('udid'))?$request->headers->get('uuid'):$request->headers->get('udid');
        $user_id = $this->checkToken($request->headers->get('token',''),$udid,$request->headers->get('uid',''),0);
        $udid = $this->getDeviceId($udid);
        $clt_id = Input::cookie('clt',$request->headers->get('clt',1));

        $info = User::userInfo($user_id, $udid, $clt_id);
        return response(array('code' => 0, 'message' => 'success', 'data' => $info))->header('Content-Type', 'application/json')->header('from','MF'.getenv('machine'));

//        $this->returnJsonResult(1,'success',$info);
    }

    public function updateInfo(Request $request){
        date_default_timezone_set('Asia/Shanghai');
        $user_id = $this->checkToken($request->headers->get('token',''),$request->headers->get('udid',''),$request->headers->get('uid',''));
        $did = $this->getDeviceId($request->headers->get('udid'));
        $res = User::updateInfo($request,$user_id,$did);
        if ($res){
            return response(array('code' => 0, 'message' => 'success', 'data' => $res))->header('Content-Type', 'application/json')->header('from','MF'.getenv('machine'));
        }else{
            return response(array('code' => 1, 'message' => 'failed', 'data' => []))->header('Content-Type', 'application/json')->header('from','MF'.getenv('machine'));
        }
    }

    public function deal(Request $request){
        $user_id = $this->checkToken($request->headers->get('token',''),$request->headers->get('udid',''),$request->headers->get('uid',''));

        $res = UserDeal::recordDeal($request,$user_id);

        if ($res){

            // add by wjy 3.4
            if (is_array($res) && $res['type'] == 0) {
                $res['uid'] = $user_id;
                OrderLogics::dealPurchaseInfo($res);
            }

            $this->returnJsonResult(0,'success',[]);
        }else {
            $this->returnJsonResult(1, 'error', []);
        }
    }

    public function receive(Request $request){
        $user_id = $this->checkToken($request->headers->get('token',''),$request->headers->get('udid',''),$request->headers->get('uid',''));

        $res = UserReceiveTools::isReceive($user_id);

        $this->returnJsonResult(0,'success',$res);

    }

    public function addNotifi(Request $request){
        $user_id = $this->checkToken($request->headers->get('token',''),$request->headers->get('udid',''),$request->headers->get('uid',''));

        $version = $request->headers->get('version',Input::get('version'));
        $token = Input::get('awstoken');
        $udid = User::headerDecrypt($request->headers->get('udid',''));
        $app   = Input::get('app', $request->headers->get('app','jigsaw'));
        $timezone = Input::get('timezone', 20);
        $isvip = VipInfo::isVipUser($user_id);
        $push_level = Input::get('pushLevel', 1);
        $new_element_push = Input::get('new_element_push', 1);
        $daily_quote_push = Input::get('daily_quote_push', 1);
        $new_updates_push = Input::get('new_updates_push', 1);
        if($push_level == '0'){
            $new_element_push = 0;
            $daily_quote_push = 0;
            $new_updates_push = 0;
        }
        // if($app == 'manly'){
        //     Log::info($app . $udid . $token);
        // }
        if(!empty($token)){
            $data = NotifiToken::where('token', $token)->first();
            if(!$data){
                $data = new NotifiToken;
                $data->token = $token;
                $data->udid = $udid;
                $data->user_id = $user_id;
                $data->created_at = date("Y-m-d H:i:s",time());
                $data->is_add = 0;
                $data->is_vip = $isvip["is_vip"];
                $data->push_level = $push_level;
                $data->new_element_push = $new_element_push;
                $data->daily_quote_push = $daily_quote_push;
                $data->new_updates_push = $new_updates_push;
                $data->lastlogin_at = date('Y-m-d H:i:s',time());
                $data->login_num = 1;
                $data->timezone = $timezone;
                $data->app = $app;
                $data->version = $version;
                if($data->udid !='00000000-0000-0000-0000-000000000000'){
                    $pre_token =  NotifiToken::where('udid', $data->udid)->orderBy('id', 'desc')->first();
                    if($pre_token){
                        if($pre_token->token == $data->token){
                            return;
                        }
                        $pre_token->is_add = -2;
                        $pre_token->save();
                        $data->login_num = $pre_token->login_num + 1;
                    }
                }
                $data->addToAws();
            }

        }
        $this->returnJsonResult(0,'success',[]);


    }

    public function getTime(Request $request){
//        $user_id = $this->checkToken($request->headers->get('token',''),$request->headers->get('udid',''),$request->headers->get('uid',''));
//
//        $data = User::getTime($user_id);
        $this->returnJsonResult(0,'success',time());

    }
    public function addPushToken(Request $request){
        $user_id = $this->checkToken($request->headers->get('token',''),$request->headers->get('udid',''),$request->headers->get('uid',''));

        $pushToken = Input::get('pushToken');
//        var_dump($pushToken);die();
        $version = Input::get('version', 0);
        $model = NotifiToken::where('token', $pushToken)->first();
        if (!$model&&$user_id){
            $model = new NotifiToken;
            $model->user_id = $user_id;
            $model->created_at = date('Y-m-d H:i:s',time());
            $model->updated_at = date('Y-m-d H:i:s',time());
            $model->timezone = Input::get('timezone',20);
            $model->token = $pushToken;
            $model->app = 'puzzle';
            $model->arn = $pushToken;
            $model->version = $version;
            $model->system = 2;
            $model->save();
        }else{
            $model->updated_at = date('Y-m-d H:i:s',time());
            $model->timezone = Input::get('timezone',20);
            $model->token = $pushToken;
            $model->arn = $pushToken;
            $model->save();
        }
        $res =['code'=>1, 'msg'=>'success', 'data' => []];
        return response()->json($res);
    }

    public function getPush(Request $request){
        $uuid = $this->getUuid($request);
        $type  = Input::get('type', '0');
        $notifi_id = Input::get("messageid" , 0);
        $pushType = Input::get('pushType','null');
        $installDate = Input::get('installDate',"2001-01-01");
        $pushDay = Input::get('pushDay',0);

        $app = 'jigsaw';

        $model = new UserPushStatistic;
        $model->uuid = $uuid;
        $model->type = $type;
        $model->notifi_id = $notifi_id;
        $model->push_type = $pushType;
        $model->install_date = $installDate;
        $model->push_day = $pushDay;
        $model->messageid = $notifi_id;
        $model->save();

        if($pushType == 'operations'){
            if($app == 'lipix'){
                $notifi_id = Input::get("ownermessageid");
            }

            // $model = new UserPushStatistic;
            // $model->uuid = $uuid;
            // $model->type = $type;
            // $model->notifi_id = $notifi_id;
            // $model->save();

            Redis::incr(RedisKey::NOTIFI_STATISTC . $type . ":" . $notifi_id);
            if (0!=$notifi_id){
                $model = Notifi::where('id',$notifi_id)->first();
                if (1==$type){
                    $model->receive_num = $model->receive_num+1;
                }else{
                    $model->open_num = $model->open_num+1;
                }
                $model->save();
            }

        }else{
            // $model = new UserPushStatistic;
            // $model->uuid = $uuid;
            // $model->type = $type;
            // $model->notifi_id = $notifi_id;
            // $model->push_type = $pushType;
            // $model->install_date = $installDate;
            // $model->push_day = $pushDay;
            // $model->save();


            $notifiStatistcData = Redis::hget(RedisKey::NOTIFI_STATISTC . $pushType . ":". $app, $installDate);

            if (isset($notifiStatistcData)){
                $notifiStatistcData = json_decode($notifiStatistcData,true);
            }else{
                $notifiStatistcData = array();
            }
            if($type == 1){
                $typeStr = 'receiveNum';
            }else{
                $typeStr = 'openNum';
            }
            isset($notifiStatistcData[$pushDay][$typeStr])?$notifiStatistcData[$pushDay][$typeStr] = $notifiStatistcData[$pushDay][$typeStr]+1:$notifiStatistcData[$pushDay][$typeStr] = 1;

            $notifiStatistcData = json_encode($notifiStatistcData);

            $res = Redis::hset(RedisKey::NOTIFI_STATISTC . $pushType . ":". $app, $installDate, $notifiStatistcData);

            // dd($notifiStatistcData);

        }



        $this->returnJsonResult(0,'success',[]);
    }

    public function test(){
//        $item = ['a1','ab1'];
//        $key1 = substr($item[0],0,-1);
//var_dump($key1);die();
//        $data = [];
//        $res = User::getExcelFile($data);
//die();
//        ini_set('date.timezone','Asia/Shanghai');
        date_default_timezone_set('Asia/Shanghai');
var_dump(date("Y-m-d",time()));
        die();
        $info = User::find(1)->toArray();
        $time = time()-strtotime($info['created_at']);
        $time = ceil($time/3600/24);
        $time = date('m', 3);
        var_dump(date('t', strtotime(date('Y-m-01',time()))));
        die();
        $id = Input::get('id');
        var_dump($id);
        $url = 'https://fcm.googleapis.com/fcm/send';
        $header = array(
            'Authorization: key=' . env('FCM_KEY_puzzle_new'),
            'Content-Type: application/json'
        );
        // $d['to'] = $token;
        $d['registration_ids'] = array($id);
        $data['message_title'] = '';
        $data['message'] = 'ssss😄🍓🍌🍇🍉🎉🦋🎀❕🔚💯🐶😫😠🎺🎈ssss';

        $data['message_id'] = 22;
        $d['data'] = $data;
        $r2 = HttpService::post($url, json_encode($d), $header);
        $num = Notifi::getSuccessNum($r2);
        var_dump($num,$r2);
        die();
        Notifi::addToAws('538bd39f9a0af15f7c1ecc6fd1b03cab6e3dda6e3219d30d8c4f6a6f7850961e');
        exit;
        $str = 'zRbvvQrTOt16jenahb8ShvCp1KMaG8pTKAKa0amXdYPy0rkXb+PM1xEZa24ne4pQyV6Zitf2lHY3nM6IXWb1Nyjj7bK1dVaxCr6w0EMei9+jVD8CvSGzrUsHOPcLVDvKIDSE7pRToBuaicpa+b4QhGO/sVIjKHqf';

        $str = base64_decode($str);
        $myfile = fopen("/usr/local/encrypt/newfile.txt", "w") or die("Unable to open file!");
        fwrite($myfile, $str);
        fclose($myfile);
        $shell = '/usr/local/en/alphaencrypt 2 /usr/local/encrypt/newfile.txt';
        $res = exec($shell);
        var_dump($str,$res);die();
        $data = array("updated_by"=> "18",
            "token"=> "18d2ee6fe8a171e8eb291406be0704e7eb",
            "user_id"=> "18",
            "star"=> "21",
            "coin"=> "0",
            "status"=> "1",
            "updated_at"=> "1551175682",
            "tools"=> "",
            "award"=> "",
            "watch_ad"=> "50",
            "is_vip"=> "0",
            "expireat"=> "1551238445",
            "finished"=> "");
        return json_encode($data);
    }


}
