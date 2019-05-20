<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecommendType;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\User;
use App\Models\Notifi;
use App\Models\RedisKey;

use App\Models\UserToken;

use App\Models\MaterialProduct;
use App\Models\NotifiTiming;

use App\Services\HttpService;


use Validator;
use Input;
use Storage;
use FileTool;
use Redis;

class NotifiController extends AdminBaseController
{
    protected $modelName = 'notifi';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
//        $app = $this->getApp();
        $app = 'jigsaw';
        $filter = [
            'keyword' => Input::get('keyword'),
            'sort' => Input::get('sort'),
//            'app' => Input::get('app'),
            'clt' => Input::get('clt'),
            'type' => Input::get('type'),
            'app' => $app
        ];
        $list = Notifi::getPageQuery($filter);

        foreach ($list as $key => $l) {
            $l->msg = Redis::get(RedisKey::NOTIFI_MSG . 'msg' . $l->id);
            $l->title = Redis::get(RedisKey::NOTIFI_MSG . 'title' . $l->id);
            $l->receive_num = Redis::get(RedisKey::NOTIFI_STATISTC . '1' . ":" . $l->id);
            $l->open_num = Redis::get(RedisKey::NOTIFI_STATISTC . '2' . ":" . $l->id);
        }

//        if($app == 'manly'||$app== 'facey'||$app== 'Everlook'||$app== 'bodyApp'||$app=='puzzle'){
//            $bladeName = $this->modelName."_manly";
//        }else{
//            $bladeName = $this->modelName;
//        }
        $bladeName = $this->modelName;

        return $this->render(
            'admin.' . $bladeName . '_index',
            [
                'list' => $list,
                'filter' => $filter,
                'modelName' => $this->modelName,
            ]
        );

    }

    public function newUserTask(){

        $app = $this->getApp();
        $pushType = 'newUserTask';

        $newUserTaskNotifiList = NotifiTiming::getList($app,$pushType);

        $notifiStatistcData = Redis::hgetall(RedisKey::NOTIFI_STATISTC . $pushType . ":". $app);
        krsort($notifiStatistcData);
        $newUserTaskList = array();
        foreach ($notifiStatistcData as $key => $value) {
            $newUserTaskList[$key] = json_decode($value,true);
        }
// dd($newUserTaskList);
        return $this->render(
            'admin.notifi_newUserTask_index',
            [
                'newUserTaskNotifiList' => $newUserTaskNotifiList,
                'newUserTaskList'       => $newUserTaskList,
                // 'filter' => $filter,
                // 'modelName' => $this->modelName,
            ]
        );
    }

    public function lostRecall(){

        $app = $this->getApp();
        $pushType = 'lostRecall';

        $lostRecallNotifiList = NotifiTiming::getList($app,$pushType);

        $notifiStatistcData = Redis::hgetall(RedisKey::NOTIFI_STATISTC . $pushType . ":". $app);
        krsort($notifiStatistcData);
        $lostRecallList = array();
        foreach ($notifiStatistcData as $key => $value) {
            $lostRecallList[$key] = json_decode($value,true);
        }

        return $this->render(
            'admin.notifi_lostRecall_index',
            [
                'lostRecallNotifiList' => $lostRecallNotifiList,
                'lostRecallList'       => $lostRecallList,
                // 'filter' => $filter,
                // 'modelName' => $this->modelName,
            ]
        );
    }

    public function pushStrategy(){

        $app = $this->getApp();

        $newUserTaskNotifiList = NotifiTiming::getList($app,'newUserTask');
        $lostRecallNotifiList = NotifiTiming::getList($app,'lostRecall');

        return $this->render(
            'admin.notifi_pushStrategy_index',
            [
                'newUserTaskNotifiList' => $newUserTaskNotifiList,
                'lostRecallNotifiList' => $lostRecallNotifiList,
                // 'filter' => $filter,
                // 'modelName' => $this->modelName,
            ]
        );
    }

    public function create()
    {
        $params['app'] = $this->getApp();
        $params['is_use'] = 0;
//        $list = RecommendType::getPageQuery($params);
        return $this->render(
            'admin.' . $this->modelName . '_create',
            [
                'modelName' => $this->modelName,
                'list' => [],
            ]
        );
    }

    // public function resend()
    // {
    //     $model = Notifi::find(Input::get('id'));
    //     if($model->range == 'token'){
    //        $res = Notifi::send($model, $model->token, $model->app);
    //        $model->send_time = date("Y-m-d H:i:s");
    //        $model->status = 1;
    //        $model->save();
    //     }

    //     // if($r){
    //     return redirect('admin/' . $this->modelName )->with('status', $res);
    //     // }else{
    //     //     return redirect('admin/' . $this->modelName )->with('status', '添加失败!' . $res . $r2);
    //     // }
    // }

    public function store()
    {
        $model = new Notifi;
        $model->msg = Input::get('msg');
        $msg = $model->msg;
        $model->title = Input::get('title','');
        $title = $model->title;
        $model->action = Input::get('action');
        $model->out_id = (int)Input::get('out_id',0);
        $model->token = Input::get('token');
        $model->clt = Input::get('clt');
        $model->app = 'jigsaw';
        $model->send_time = Input::get("send_time");
        $model->lastlogin_at = Input::get("lastlogin_at");
        $model->createstart_time = Input::get("createstart_time");
        $model->createend_time = Input::get("createend_time");
        $model->is_pass = Input::get("ispass",0);

//        $model->push_type = Input::get('push_type','operations');
        $model->range = Input::get('range', '');
        $pic = Input::file('pic');
        if(!empty($pic)){
           $model->pic = FileTool::upload($pic, 'notifi');
        }
        $icon = Input::file('icon');
//        if(!empty($icon)){
//           $model->icon = FileTool::upload($icon, 'notifi');
//        }
//        if ($model->createend_time>$model->lastlogin_at){
//            return redirect('admin/' . $this->modelName )->with('status','最晚注册时间必须小于最后登陆时间');
//        }
//        if(!empty($model->out_id) && $model->action != 'material' && $model->action != 'posterDetail'){
//            return redirect('admin/' . $this->modelName )->with('status','action 错误');
//        }
//
//        if($model->action == 'material'){
//            $item = MaterialProduct::where('is_postcard', 0)->where('id', $model->out_id)->first();
//            if(!$item){
//                return redirect('admin/' . $this->modelName )->with('status','out_id 错误');
//            }
//        }
//        if($model->action == 'posterDetail'){
//            $item = MaterialProduct::where('is_postcard', 1)->where('id', $model->out_id)->first();
//            if(!$item){
//                return redirect('admin/' . $this->modelName )->with('status','out_id 错误');
//            }
//        }

        // dd(strtotime($params['send_time']),time());
        if($model->range != 'token' &&  strtotime($model->send_time)<= time()){
            return redirect('admin/' . $this->modelName )->with('status','推送时间必须大于当前时间!');
        }

        $model->status = 0;
        $r = $model->save();
        $res = '';
        $r2 = '';

        Redis::set(RedisKey::NOTIFI_MSG . 'msg' . $model->id, $msg);
        Redis::set(RedisKey::NOTIFI_MSG . 'title' . $model->id, $title);
        Redis::set(RedisKey::NOTIFI_STATISTC . '1' . ":" . $model->id, 0);
        Redis::set(RedisKey::NOTIFI_STATISTC . '2' . ":" . $model->id, 0);

        if($model->range == 'token'){
           $model = Notifi::find($model->id);
           $registration_ids[] = $model->token;
           $res = Notifi::send($model, $registration_ids, $model->app);
           $model->send_time = date("Y-m-d H:i:s");
           $model->status = 1;
           // $model->num = 1;
           $model->save();
        }

        if($r){
            return redirect('admin/' . $this->modelName )->with('status', '添加成功!' . $res . $r2);
        }else{
            return redirect('admin/' . $this->modelName )->with('status', '添加失败!' . $res . $r2);
        }

    }

    public function sendNotif($model){
            $data['action'] = $model->action;
            if($model->pic){
                $data['imgUrl'] = MaterialProduct::getRealUrl($model->pic);
            }else{
                $data['imgUrl'] = '';
            }

            if($data['action'] == 'posterDetail'){
                $data['id'] = $model->out_id;
                $data['type'] = 'poster';
                $poster = MaterialProduct::find($model->out_id);
                $data['binaryData'] = MaterialProduct::getRealUrl($poster->binary_data);
                $data['ver'] = 0;
                $data['posterPreviewUrl'] = MaterialProduct::getRealUrl($poster->android_main_cover);
                $data['isVip'] = $poster->is_vip;
            }
            if($data['action'] == 'material'){
                $data['id'] = $model->out_id;
                $data['type'] = 'material';
                $material = MaterialProduct::find($model->out_id);
                $data['ver'] = 0;
                $data['materialPreviewUrl'] = MaterialProduct::getRealUrl($material->android_main_cover);
                $data['isVip'] = $material->is_vip;
            }

            if($model->clt == UserToken::CLT_ANDROID){
                $url = 'https://fcm.googleapis.com/fcm/send';
                $header = array(
                                'Authorization: key=' . env('FCM_KEY_' . $model->app . '_new'),
                                'Content-Type: application/json'
                            );
                $d['to'] = $model->token;
                $data['message_title'] = $model->app;
                $data['message_title'] == 'poto' && $data['message_title'] = 'POTO';
                $data['message'] = $model->msg;

                $data['message_id'] = $model->id;
                $d['data'] = $data;
                $r2 = HttpService::post($url, json_encode($d), $header);
                if(strpos($r2, '"success":1')){
                    $res = '1';
                }else{
                    $res = $r2;
                }
            }elseif($model->clt == UserToken::CLT_IOS){
                //  $config = array(
                //     'region' => env('S3_REGION'),
                //     'credentials' => [
                //         'key'    => env('S3_KEY') ,
                //         'secret' => env('S3_SECRET')
                //     ],
                //     'version' => 'latest',
                // );
                // $client =  new SnsClient($config);
                // try{
                //     $r = $client->publish(array(
                //                 'PlatformApplicationArn' => 'arn:aws:sns:us-east-1:561020269087:app/APNS/poto',
                //                 // Token is required
                //                 'MessageStructure' => 'json',
                //                 'Message' => json_encode(array(
                //                     'APNS' => json_encode(array(
                //                         'aps' => array(
                //                             'alert' => $msg,
                //                             'messageid' => $message_id,
                //                             'content-available' => 1
                //                             ),
                //                         ))
                //                     )),
                //                 'TargetArn' => $this->push_token,
                //     ));
                //     var_dump($r);
                //     return true;
                // }catch (SnsException $e) {
                //         // echo 'Message: ' .$e->getMessage();
                //     if($this->token_status != -1){
                //         $this->token_status = -1;
                //         $this->save();
                //     }

                //     return false;
                // }
            }
            return $res;
    }
    public function update($id){
        $time = Input::get('send_time');
        $status = Input::get('status');
        $is_pass = Input::get('is_pass');
        $model = Notifi::find($id);
        if (!$model){
            return redirect('admin/' . $this->modelName)->with('status', '修改失败!');
        }
        $model->send_time = isset($time)?$time:$model->send_time;
        $model->is_pass = isset($is_pass)?$is_pass:$model->is_pass;
        $model->status = isset($status)?$status:$model->status;
        $model->save();
        return redirect('admin/' . $this->modelName)->with('status', '修改成功!');
    }
    public function destroy($id)
    {
        $model = Notifi::find($id);
        if (is_null($model)) {
            abort(404);
        }

        $model->delete();

        return redirect('admin/' . $this->modelName)->with('status', '删除成功!');
    }

}
