<?php

namespace App\Models;

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Models\Sso;
use DB;
use Config;
use Redis;
use App\Services\FileService;

use Excel;
use Storage;
use Log;


class User extends BaseModel
{
    protected $table = 'user';
    protected $fillable = ['name'];

    public static function addUser($udid){
        if (empty($udid)){
            return null;
        }
        $model = UserDevice::where('udid',$udid)->first();
        if ($model){
            return $model->id;
        }
        $model = new User();
        $model->name = md5(time());
        $model->app = '';
        $model->save();

        if ($model){
            $token = $model->id.md5(time());
            $device = new UserDevice();
            $device->udid = $udid;
            $device->token = $token;
            $device->user_id = $model->id;
            $device->save();
            $data['updated_by'] = $device->id;
            $model->updated_by = $device->id;
            $model->save();
            $data['token'] = $token;
            if ($device->id){
                Redis::hmset(RedisKey::USER.$model->id,$data);
                self::getData($model);
                return $model->id;
            }else{
                return null;
            }
        }
    }

    public static function getData($model){

        $data['user_id'] = $model->id;
        $data['star'] = $model->star;
        $data['coin'] = $model->coin;
        $data['status'] = $model->status;
        $data['created_at'] = strtotime($model->created_at);
        $data['updated_at'] = strtotime($model->updated_at);
        $data['updated_by'] = $model->updated_by;
        $data['finished'] = $model->finished;
        $data['tools'] = empty($model->tools)?'':$model->tools;
        $data['award'] = empty($model->award)?'':$model->award;
        $data['unlock'] = empty($model->unlock)?'':$model->unlock;
        $data['watch_ad'] = 50;

//        新建vip表做判断

        $is_vip = VipInfo::isVipUser($model->id);
        $device = UserDevice::getToken($model->id);
        if (empty($data['updated_by'])){
            $data['updated_by'] = $device['updated_by'];
        }
        $data['token'] = $device['token'];
        $data['is_vip'] = $is_vip['is_vip'];
        $data['expireat'] = $is_vip['expired_at'] ;

        Redis::hmset(RedisKey::USER.$model->id, $data);


        return $data;
    }
    public static function userInfo($user_id, $udid='', $clt_id=1){
        $info = Redis::hgetall(RedisKey::USER.$user_id);
        if (empty($info['user_id'])||empty($info['token'])||empty($info['star'])||empty($info['coin'])||empty($info['finished'])||empty($info['unlock'])||empty($info['created_at'])||empty($info['updated_at'])){
            $model = User::where('id',$user_id)->first();
            if ($model){
                $info =  self::getData($model);
                $info = Redis::hgetall(RedisKey::USER.$user_id);
            }else{
                return [];
            }
        }

        if (!empty($udid)){
            self::userStatic($info, $udid, $clt_id);
        }
        $info['now'] = (string)time();
        if (!empty($info)){
            if ('local'!=getenv('APP_ENV')){
                $info = self::requestEncrypt($info,$user_id);
            }
        }else{
            self::recommendLogs($user_id.$udid,'upError'.$user_id);
        }
        return $info;
    }

    public static function updateInfo($req,$user_id,$did){
        if (empty($req->data)||!isset($req->data)){
            return 0;
        }
        if ('local'!=getenv('APP_ENV')){
            $request = self::requestDecrypt($req->data,$user_id);
        }else{
            $request = json_decode($req->data,true);
        }

        isset($request['star'])?$data['star'] = $request['star']:'';
        isset($request['coin'])?$data['coin'] = $request['coin']:'';
        isset($request['tools'])?$data['tools'] = $request['tools']:'';
        isset($request['award'])?$data['award'] = $request['award']:'';
        isset($request['finished'])?$data['finished'] = $request['finished']:'';
        isset($request['unlock'])?$data['unlock'] = $request['unlock']:'';
        if(isset($request['system'])&&1==$request['system']){
            $data['system'] = $request['system'];
        }
        $data['updated_by'] = self::headerDecrypt($did);

        $res = User::where('id',$user_id)->update($data);
//        Redis::sadd(RedisKey::UPDATE_USER,$user_id);
        isset($request['watch_ad'])?$data['watch_ad'] = $request['watch_ad']:'';
        $data['updated_at'] = time();
        Redis::hmset(RedisKey::USER.$user_id,$data);

        return 1;
    }

    public static function requestEncrypt($info, $user_id){
        $filename = 'info/down'.$user_id.'resxxx'.md5(time()).'.log';
        Storage::disk('local')->put($filename,json_encode($info));
        $logname = 'logs/down'.$user_id.'resxxx'.md5(time()).'.log';
        Storage::disk('local')->put($logname,'ss'.json_encode($info));
        $shell = '/usr/local/en/alphaencrypt 2 '.storage_path('app/'.$filename);
        $res = exec($shell);
        if (Storage::disk('local')->exists($filename)){
            Storage::disk('local')->delete($filename);
            Storage::disk('local')->delete($logname);
        }
        return $res;
    }

    public static function requestDecrypt($req, $user_id){
        $filename = 'info/upload'.$user_id.'reqxxx'.md5(time()).'.log';
        Storage::disk('local')->put($filename, $req);
        $logname = 'logs/up'.$user_id.'resxxx'.md5(time()).'.log';
        Storage::disk('local')->put($logname,'ss'.json_encode($req));
        $shell = '/usr/local/en/alphaencrypt 3 '.storage_path('app/'.$filename);
        $res = exec($shell);
        if (Storage::disk('local')->exists($filename)){
            Storage::disk('local')->delete($filename);
            Storage::disk('local')->delete($logname);
        }
        return json_decode($res,true);
    }

    public static function headerEncrypt($str){
        if ('local'!=getenv('APP_ENV')) {
            $shell = "/usr/local/en/alphaencrypt 4 $str";
            $res = exec($shell);
        }else{
            $res = $str;
        }
        return $res;
    }

    public static function headerDecrypt($str){
        if ('local'!=getenv('APP_ENV')) {
            $shell = "/usr/local/en/alphaencrypt 5 $str";
            $res = exec($shell);

        }else{
            $res = $str;
        }
        return $res;
    }

    public static function getUpdateBy($id){
        $updated_by = Redis::hget(RedisKey::USER.$id,'updated_by');
        if (!$updated_by){
            $model = User::where('id',$id)->first();
            $updated_by = $model->updated_by;
            if (!$updated_by){
                $device = UserDevice::where('user_id',$id)->orderBy('updated_at','desc')->first();
                if ($device){
                    $model->updated_by = $device->id;
                    $model->updated_at = $device->updated_at;
                    $model->save();
                    $updated_by = $device->id;
                }
            }

        }
        Redis::hset(RedisKey::USER.$id,'updated_by',$updated_by);
        return $updated_by;
    }

    public static function getTime($user_id){
        $created_at = Redis::hget(RedisKey::USER.$user_id,'created_at');

        if (!$created_at){
            $model = self::where('id',$user_id)->first();

            $created_at = $model->created_at;
            Redis::hset(RedisKey::USER.$user_id,'created_at',$created_at);
        }
        $data = array('created_at'=>strtotime($created_at),'now'=>time());
        return $data;
    }

    public static function userStatic($info, $udid, $clt_id){
        if (isset($info['created_at'])&&!empty($udid)){
//            self::recommendLogs(json_encode($info).'///'.$udid,$info['user_id']);

            $today = date("Y-m-d", time());

            $model = UserStatis::where('user_id',$info['user_id'])->where('udid',$udid)->whereBetween('created_at',[date("Y-m-d 00:00:00",time()),date("Y-m-d 00:00:00",strtotime("+1 days"))])->first();
//            $last_login_time = Redis::hget(RedisKey::USER.$info['user_id'],'last_login_time');
            if (!$model){
                Redis::Hincrby(RedisKey::INSTALL_DAY.$today.':'.$clt_id, 'dau', 1);
                $time = (int)ceil((time()-$info['created_at'])/3600/24);
                if (0===$time){
                    $time += 1;
                }
//              $month = date('t', strtotime(date('Y-m-01',time()))); //每个月有几天

                if ($time<=181){
                    Redis::Hincrby(RedisKey::INSTALL_DAY.date("Y-m-d",$info['created_at']).':'.$clt_id, $time,1);
                }

                $model = new UserStatis();
                $model->user_id = $info['user_id'];
                $model->udid = $udid;
                $model->clt = $clt_id;
                $model->save();
            }
        }
    }

    public static function recommendLogs($content,$path){
        $file = storage_path("logs/$path.log");
        $content = '__'.time().'__'.$content;
        file_put_contents($file, $content.PHP_EOL, FILE_APPEND);
    }

    public static function getPercentage($data=[],$day=0){
        if (empty($data)||$day==0){
            return 0;
        }elseif(!isset($data[$day])||!isset($data[1])){
            return 0;
        }else{
            return round($data[$day]/$data[1]*100,2).'%';
        }
    }

    public static function getExcelFile($data=[]){
        Excel::create("用户统计",function($excel) use ($data){

            $excel->sheet("用户统计", function($sheet) use ($data){
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->cells('A1:A2', function($cells)
                {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('B1:B2', function($cells)
                {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('C1:C2', function($cells)
                {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                $sheet->cells('D1:D2', function($cells)
                {
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $sheet->cell('A1','日期');
                $sheet->cell('B1','DAU');
                $sheet->cell('C1','当日新增');

                $sheet->mergeCells('D1:E1');
                $sheet->cell('D1','test');
                $locate = [
                    'D1:E1','F1:G1','H1:I1','J1:K1','L1:M1','N1:O1','P1:Q1','R1:S1','T1:U1','V1:W1',
                    'X1:Y1','Z1:AA1','AB1:AC1','AD1:AE1','AF1:AG1','AH1:AI1','AJ1:AK1','AL1:AM1','AN1:AO1','AP1:AQ1',
                    'AR1:AS1','AT1:AU1','AV1:AW1','AX1:AY1','AZ1:BA1','BB1:BC1','BD1:BE1','BF1:BG1','BH1:BI1','BJ1:BK1','BL1:BM1','BN1:BO1',
                ];
                for ($i=0;$i<31;$i++){
//                     var_dump($locate[$i]);
                    $sheet->mergeCells($locate[$i]);
                    $item = explode(':', $locate[$i]);
                    $sheet->cell($item[0],'第'.($i+2).'天');
                    $key1 = substr($item[0],0,-1).'2';
                    $key2 = substr($item[1],0,-1).'2';
                    $sheet->cell($key1,'留存数');
                    $sheet->cell($key2,'留存率');
                }
                $insert = array();
                foreach ($data as $k=>$v){
                    $row = [];
                    $row[] = $k;
                    $row[] = isset($v['dau'])?$v['dau']:0;
                    for ($i=1;$i<32;$i++){
                        if (isset($v[$i])&&isset($v[1])){
                            $row[] = round(($v[$i]/$v[1]*100),2).'%';
                        }else{
                            $row[] = '0%';
                        }
                        $row[] = isset($v[$i])?$v[$i]:0;
                    }
                    $insert[] = $row;
                }
                $sheet->rows($insert);
//                foreach ($infos as $key => $value)
//                {
//                    $sheet->mergeCells($locate[$key]);
//                    $item = explode(':', $locate[$key]);
//                    $sheet->cell($item[0],$value->name);
//                    $sitem = explode(':', $smalllocate[$key]);
//                    foreach ($sitem as $skey => $svalue)
//                    {
//                        switch ($skey) {
//                            case '0':
//                                $sheet->cell($svalue,'使用次数');
//                                break;
//                            case '1':
//                                $sheet->cell($svalue,'付费页展示次数');
//                                break;
//                            case '2':
//                                $sheet->cell($svalue,'付费成功');
//                                break;
//                            case '3':
//                                $sheet->cell($svalue,'付费成功率');
//                                break;
//
//                            default:
//                                # code...
//                                break;
//                        }
//                    }
//                }
//                $datas = [];
//                foreach($news as $date=>$new)
//                {
//                    $info = [];
//                    $info['date'] = $date;
//                    $info['new']  = isset($new)?$new:0;
//                    $info['view'] = isset($view[$date])?$view[$date]:0;
//                    $info['pays'] = isset($pays[$date])?$pays[$date]:0;
//                    foreach ($infos as $ink => $inval)
//                    {
//                        $info['usetime'.$ink]     = isset($data[$inval->name][1][$date])?$data[$inval->name][1][$date]:0;
//                        $info['viewtime'.$ink]    = isset($data[$inval->name][2][$date])?$data[$inval->name][2][$date]:0;
//                        $info['paysccess'.$ink]   = isset($data[$inval->name][3][$date])?$data[$inval->name][3][$date]:0;
//                        $info['payrate'.$ink]     = 0;
//                        if(isset($data[$inval->name][2][$date]) && !empty($data[$inval->name][2][$date]) && isset($data[$inval->name][3][$date]))
//                        {
//                            $info['payrate'.$ink] = number_format($data[$inval->name][3][$date]*100/$data[$inval->name][2][$date],2).'%';
//                        }
//                    }
//                    $datas[] = $info;
//                }
//                $sheet->rows($datas);

            });
        })->export('xls');

    }




}
