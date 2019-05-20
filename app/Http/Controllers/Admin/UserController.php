<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManlyUserAction;
use App\Models\PayAction;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\RedisKey;
use App\Models\User;
use App\Models\FunctionStatistic;

use Validator;
use Input;
use Storage;
use Redis;
use Excel;
use DB;


class UserController extends AdminBaseController
{
    protected $modelName = 'user';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $filter = [
            'name' => Input::get('name'),
            'email' => Input::get('email'),
            'sort' => Input::get('sort'),
        ];

        $list = User::getPageQuery($filter);

        return $this->render(
            'admin.' . $this->modelName . '_index', 
            [
                'list' => $list,
                'filter' => $filter,
                'modelName' => $this->modelName,
            ]
        );

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $model = User::find($id);

        if (is_null($model)) {
            abort(404);
        }
        
        return $this->render(
            'admin.' . $this->modelName . '_edit',
            [
                'model' => $model,
                'modelName' => $this->modelName,            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  request  $request
     * @param  int      $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $name = Input::get('name','');
        $info = FunctionStatistic::find($id);
        $info->name = trim($name);
        $res = $info->save();
        if ($res) 
        {
            return redirect()->back()->with('status', '修改成功!');
        }
        return redirect()->back()->with('status', '修改失败!');
    }

    
    public function del(Request $request){
        return $this->render(
            'admin.admin_' . $this->modelName . '_del',
            [

            ]
        );
    }

    public function delUser(Request $request){
        if (1==$request->en){
            $udid = $request->udid;
        }else{
            $udid = User::headerDecrypt($request->udid);
        }
        if (empty($udid)){
            return redirect()->back()->with('status', '删除失败,udid为空!');
        }

        $model = UserDevice::where('udid',$udid)->orderBy('created_at','desc')->first();

        if ($model){
            $model->udid = $model->udid.time();
            $res = $model->save();
            if ($res) {
                return redirect()->back()->with('status', '删除成功!');
            }else{
                return redirect()->back()->with('status', '删除失败!');
            }
        }else{
            return redirect()->back()->with('status', '删除失败，未查找到用户!');
        }
    }
    public function userStatis(Request $request){
//        var_dump(strtotime($request->startDate),$request->startDate,$request->system);die(); 86400
//        var_dump($request->startDate,$request->endDate);die();
        date_default_timezone_set('Asia/Shanghai');
        $system = empty($request->system)?'1':$request->system;
        $startDate = empty($request->startDate)?strtotime('-1 months'):strtotime($request->startDate);
        $endDate = empty($request->endDate)?time():strtotime($request->endDate);
        if ($startDate>$endDate){
            return redirect()->back()->with('status', '开始日期不得大于结束日期');
        }
        $data = [];
        $time = date("Y-m-d",$startDate);
        for ($i=1;$i<10000000;$i++){
            $data[$time] = Redis::hgetall(RedisKey::INSTALL_DAY.$time.':'.$system);
            $time = date("Y-m-d",strtotime($time)+86400);
            if ($time==date("Y-m-d",$endDate)){
                $data[$time] = Redis::hgetall(RedisKey::INSTALL_DAY.$time.':'.$system);
                break;
            }
        }
        $startDate = date("Y-m-d",$startDate);
        $endDate = date("Y-m-d",$endDate);
        $data = array_reverse($data);
        return $this->render(
            'admin.admin_' . $this->modelName . '_statis',
            [
                'startDate' => $startDate,
                'endDate' => $endDate,
                'data' => $data,
                'system' => $system,
            ]
        );
    }

    public function downLoadExcel(Request $request){
        date_default_timezone_set('Asia/Shanghai');
        $system = empty($request->system)?'1':$request->system;
        $startDate = empty($request->startDate)?strtotime('-1 months'):strtotime($request->startDate);
        $endDate = empty($request->endDate)?time():strtotime($request->endDate);
        if ($startDate>$endDate){
            return redirect()->back()->with('status', '开始日期不得大于结束日期');
        }
        $data = [];
        $time = date("Y-m-d",$startDate);
        for ($i=1;$i<10000000;$i++){
            $data[$time] = Redis::hgetall(RedisKey::INSTALL_DAY.$time.':'.$system);
            $time = date("Y-m-d",strtotime($time)+86400);
            if ($time==date("Y-m-d",$endDate)){
                $data[$time] = Redis::hgetall(RedisKey::INSTALL_DAY.$time.':'.$system);
                break;
            }
        }
        $data = array_reverse($data);

//        $data = [];
        $res = User::getExcelFile($data);

        return $res;

    }
}
