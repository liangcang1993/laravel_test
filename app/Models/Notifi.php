<?php

namespace App\Models;

use Aws\Sns\SnsClient;
use DB;
use Illuminate\Database\Eloquent\SoftDeletes;
use Redis;

class Notifi extends BaseModel
{
    use SoftDeletes;
    protected $connection = 'mysql_utf8mb4';
    protected $table = 'notifi';

    public static function getPageQuery($filter = array())
    {
        $query = self::onWriteConnection()->selectRaw('*');

        if (isset($filter['keyword']) && trim($filter['keyword']) != '') {
            $query->where('msg', 'like', '%' . $filter['keyword'] . '%');
        }
        if (isset($filter['app']) && trim($filter['app']) != '') {
            $query->where('app', '=', $filter['app']);
        }
        if (isset($filter['clt']) && trim($filter['clt']) != '') {
            $query->where('clt', '=', $filter['clt']);
        }
        if (isset($filter['type']) && trim($filter['type']) != '') {
            $query->where('type', '=', $filter['type']);
        }
        if (isset($filter['sort']) && trim($filter['sort']) != '') {
            $d = explode(' ', $filter['sort']);
            $query->orderBy($d[0], $d[1]);
        }else{
            $query->orderBy('id', 'desc');
        }
        return $query->paginate(10);
    }

    public static function send($notifi, $registration_ids, $app, $client, $version=1, $system=1, $material=''){
        $model = $notifi;

        if($system == 2){
            $url = 'https://fcm.googleapis.com/fcm/send';
            $header = array(
                'Authorization: key=' . env('FCM_KEY_' . $app . '_new'),
                'Content-Type: application/json'
            );
            // $d['to'] = $token;
            $d['registration_ids'] = array($registration_ids);
            $data['message_title'] = $notifi->title;
            $data['message'] = $notifi->msg;
            $data["pushType"] = 'operations';

            $data['message_id'] = $notifi->id;
            $d['data'] = $data;
            $r2 = HttpService::post($url, json_encode($d), $header);
            $num = self::getSuccessNum($r2);
            $notifi->num = $notifi->num + $num;


            if($num > 0){
                if($model->range == 'token'){
                    $res = $r2 . '推送成功';
                }else{
                    $res = '1';
                }
            }else{
                $res = $r2 . '推送失败';
            }
        }elseif($system == 1){
//              $config = array(
//                 'region' => env('S3_REGION'),
//                 'credentials' => [
//                     'key'    => env('S3_KEY') ,
//                     'secret' => env('S3_SECRET')
//                 ],
//                 'version' => 'latest',
//             );
            if (90909==$version){
                $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS/jigsaw';
//                $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';
            }else{
                $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';
            }
//            $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';

//             $client =  new SnsClient($config);
            try{
                $r = $client->publish(array(
                    'PlatformApplicationArn' => $arn,
                    // Token is required
                    'MessageStructure' => 'json',
                    'Message' => json_encode(array(
                        'APNS' => json_encode(array(
                            'aps' => array(
                                "alert"=>$notifi->msg,
                                "sound"=>"default",
                                "messageid"=>$notifi->id,
                                "badge"=>1,
                                "type"=>$notifi->action,
                                "material"=>$material,
                                "mutable-content" =>1,
                                "pushType"=>'operations',
                            ),
                        )),
                        'APNS_SANDBOX' => json_encode(array(
                            'aps' => array(
                                "alert"=>$notifi->msg,
                                "sound"=>"default",
                                "messageid"=>$notifi->id,
                                "badge"=>1,
                                "type"=>$notifi->action,
                                "material"=>$material,
                                "mutable-content" =>1,
                                "pushType"=>'operations',
                            ),
                        ))
                    )),
                    'TargetArn' => $registration_ids,
                ));

//                var_dump(array(
//                    'aps' => array(
//                        "alert"=>$notifi->msg,
//                        "sound"=>"default",
//                        "badge"=>1,
//                        "type"=>$notifi->action,
//                        "metrial"=>$material,
//                    ),
//                ));
                echo '///////';
                var_dump($r);
                $notifi->num = $notifi->num +1;
                $res = true;
                return $res;
            }catch (SnsException $e) {
                echo 'Message: ' .$e->getMessage();

                $res = false;
                return $res;
            }
        }
        return $res;
    }
    public function getTypeStr(){
        switch ($this->type) {
            case 1:
                return "首次安装次日未登录";
                break;
            case 2:
                return "连续3天未登录";
                break;
            case 3:
                return "连续7天以上未登录";
                break;

            default:
                return "";
                break;
        }
    }
    public function getCltStr(){
        switch ($this->clt) {
            case 0:
                return $this->app . "(all)";
                break;
            case 1:
                return $this->app . "(ios)";
                break;
            case 2:
                return $this->app . "(android)";
                break;
            default:
                return "";
                break;
        }
    }
    public function getStatusStr(){
        switch ($this->status) {
            case 0:
                return "未推送";
                break;
            case 1:
                return "已推送";
                break;

            default:
                return "";
                break;
        }
    }

    public static function getSuccessNum($res){
        $i = strpos($res, '"success"');
        $j = strpos($res, '"failure"');
        return (int)substr($res, $i+10, $j-$i-11);
    }

    public static function pushNotifiTiming($notifi, $registration_ids, $client,$installDate,$app,$version){

        $model = $notifi;
        $data['action'] = $model->action;

        if($model->clt == UserToken::CLT_ANDROID){
            $url = 'https://fcm.googleapis.com/fcm/send';
            $header = array(
                'Authorization: key=' . env('FCM_KEY_' . $notifi->app . '_new'),
                'Content-Type: application/json'
            );
            // $d['to'] = $token;
            $d['registration_ids'] = $registration_ids;
            $data['message_title'] = Redis::get(RedisKey::NOTIFI_MSG . 'title' . $model->id);
            $data['message'] = Redis::get(RedisKey::NOTIFI_MSG . 'msg' . $model->id);
//            $data["pushType"]='operations';
            $data["pushType"]=$notifi->type;
            $data["installDate"]=$installDate;
            $data["pushDay"]=$notifi->push_day;
            $data['message_id'] = $model->id;
            $d['data'] = $data;
            $r2 = HttpService::post($url, json_encode($d), $header);
            $num = self::getSuccessNum($r2);
            $notifi->num = $notifi->num + $num;
            // var_dump($r2);
            // Log::info($r2);

            if($num > 0){
                if($model->range == 'token'){
                    $res = $r2 . '推送成功';
                }else{
                    $res = '1';
                }
            }else{
                $res = $r2 . '推送失败';
            }
        }elseif($model->clt == UserToken::CLT_IOS){
//              $config = array(
//                 'region' => env('S3_REGION'),
//                 'credentials' => [
//                     'key'    => env('S3_KEY') ,
//                     'secret' => env('S3_SECRET')
//                 ],
//                 'version' => 'latest',
//             );
            // $material = Material::find($notifi->out_id);
            if (90909==$version){
                $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS/jigsaw';
//                $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';
            }else{
                $arn = 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/jigsaw_dev';
            }
//             $client =  new SnsClient($config);
            try{
                $r = $client->publish(array(
                    'PlatformApplicationArn' => $arn,
                    // 'PlatformApplicationArn' => 'arn:aws:sns:us-east-1:561020269087:app/APNS_SANDBOX/manly_test',
                    // Token is required
                    'MessageStructure' => 'json',
                    'Message' => json_encode(array(
                        'APNS' => json_encode(array(
                            'aps' => array(
                                "alert"=>$notifi->msg,
                                "sound"=>"default",
                                "messageid"=>$notifi->id,
                                "badge"=>1,
                                "type"=>$notifi->action,
                                "pushType"=>$notifi->type,
                                "installDate"=>$installDate,
                                "pushDay"=>$notifi->push_day,
                            ),
                        )),
                        'APNS_SANDBOX' => json_encode(array(
                            'aps' => array(
                                "alert"=>$notifi->msg,
                                "sound"=>"default",
                                "messageid"=>$notifi->id,
                                "badge"=>1,
                                "type"=>$notifi->action,
                                "pushType"=>$notifi->type,
                                "installDate"=>$installDate,
                                "pushDay"=>$notifi->push_day,

                            ),
                        ))
                    )),
                    'TargetArn' => $registration_ids,
                    // 'TargetArn' => 'arn:aws:sns:us-east-1:561020269087:endpoint/APNS/manly/346ba648-21db-3be1-8748-698d2b290366',
                    // 'TargetArn' => 'arn:aws:sns:us-east-1:561020269087:endpoint/APNS_SANDBOX/manly_test/099fe037-1d45-312a-aa07-369d198bf641',
                ));
                var_dump(array(
                    'aps' => array(
                        "alert"=>$notifi->msg,
                        "sound"=>"default",
                        "badge"=>1,
                        "sucai"=>"beard",
                        "content-available"=>1,
                        "type"=>$notifi->action,
                        "materialid"=>$notifi->out_id,
                        "pushType"=>$notifi->type,
                        "installDate"=>$installDate,
                        "pushDay"=>$notifi->push_day,

                    ),
                ));
                echo '///////';
                var_dump($r);

                return true;
            }catch (SnsException $e) {
                echo 'Message: ' .$e->getMessage();
                return false;
            }
        }
        return $res;
    }

}