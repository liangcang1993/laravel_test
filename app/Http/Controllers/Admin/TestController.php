<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Logics\OrderLogics;
use App\Logics\VipInfoLogics;
use App\Logics\ProgressLogics;
use App\Logics\RestoreLogics;

use App\Services\CommonServices;

use App\Models\VipInfo;
use App\Models\User;
use App\Models\JigsawProgress;

use DB;
use Redis;
use Event;
use Input;
use Response;

 
use App\Listeners\UserUpdateListener;

class TestController extends Controller
{   

    public function __construct()
    {
        ini_set("max_execution_time", 300);
        ini_set('memory_limit', '512M');
        set_time_limit(300);
    }
    
    public function a(Request $request)
    {   
       out(123);

       //SELF::putProgressRedis();

    }

    

    public static function putProgressRedis()
    {
        $info = DB::table('jigsaw_progress')
        ->select('uid')
        ->groupBy('uid')    
        ->get();

        $info = obj_to_arr($info);
        $uidL = to_1array($info, 'uid');
        
        foreach ($uidL as $uid) {
            SELF::putUidP($uid);//out(123);
        }
        

    }

    
    public static function putUidP($uid)
    {   

        $where['uid'] = $uid;
        $info = @JigsawProgress::getList(['*'], $where, ['limit'=>100])['list'];
        //out($info);

        if (empty($info)) {
            return;
        }

        foreach ($info as $val) {

            JigsawProgress::addListToRedis($val);
            JigsawProgress::addToRedis($val);
        }
        
        
        log_out("uid deal $uid");
    }
    
    

}



