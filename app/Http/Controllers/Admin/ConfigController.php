<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RedisKey;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Config;

use Illuminate\Support\Facades\Redis;
use Validator;
use Input;
use Session;
use Illuminate\Support\Facades\DB;

class ConfigController extends AdminBaseController
{
    protected $modelName = 'config';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $filter = [
            'key'   => Input::get('key'),
            'sort'  => Input::get('sort'),
            'cover' => Input::get('cover'),
			'dev'   => Input::get('dev',0)
        ];
        if ($filter['dev'] != 3){
			Session::set('dev',$filter['dev']);
		}
        $list = Config::getPageQuery($filter);

        return $this->render(
            'admin.' . $this->modelName . '_index', 
            [
                'list'      => $list,
                'filter'    => $filter,
                'modelName' => $this->modelName,
            ]
        );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return $this->render(
            'admin.' . $this->modelName . '_create',
            [
                'modelName' => $this->modelName,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  request  $request
     * @return Response
     */
    public function store(Request $request)
    {
		$dev = Session::get('dev',0);
        $model = new Config;
        $model->key     = Input::get('key');
        $model->value   = Input::get('value');
        $model->desc    = Input::get('desc');
        $model->app     = 'jigsaw';
        $model->device     = $dev;
        $r = $model->save();
        $models = Config::orderBy('created_at','desc')->get();
        foreach ($models as $k=>$v){
            $data[$v->key] = $v->value;
        }
        Redis::hmset(RedisKey::CONFIG, $data);

        if($r){
            $this->addToRedis();
            return redirect('admin/' . $this->modelName .'/?dev='.$dev )->with('status', '添加成功!');
        }else{
            return redirect('admin/' . $this->modelName .'/?dev='.$dev )->with('status', '添加失败!');
        }
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
        $model = Config::find($id);

        if (is_null($model)){
            return redirect('admin/' . $this->modelName)->withErrors("纪录不存在!")->withInput();
        }
        $model->key     = Input::get('key', $model->key);
        $model->value   = Input::get('value', $model->value);
        $model->desc    = Input::get('desc', $model->desc);

        if ($model->save()){
            $this->addToRedis();
            return redirect()->back()->with('status', '修改成功!');
        } else {
            return redirect('admin/' . $this->modelName . '/' . $id . '/edit')->withErrors("更新失败!")->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $model = Config::find($id);
        if (is_null($model)) {
            abort(404);
        }
        Redis::hdel(RedisKey::CONFIG,$model->key);
        $model->delete();
        return redirect('admin/' . $this->modelName)->with('status', '删除成功!');
    }

    public function upload(){
        $file = Input::file('file');
        if($file){
            $json = file_get_contents($file->getPathname());
            $list = json_decode($json, 1);
            foreach ($list as $l) {
                $config = Config::where('key', '=', $l['key'])->first();
                if(!$config){
                    $config = new Config;
                    $config->key = $l['key'];
                }
                $config->value = is_array($l['value'])?  json_encode($l['value']) : $l['value'];
                $config->desc = $l['desc'];
                $config->save();
                $this->addToRedis();
                # code...
            }
        }
        return redirect()->back()->with('status', '导入成功!');

    }
    public function export(){
		$dev = Session::get('dev',0);
        $list = Config::where('device',$dev)->get();
       
       $res = [];
       foreach ($list as $l) {
            $r['app'] = $l->app;
            $r['key'] = $l->key;
            $r['value'] = json_decode($l->value, 1);
            empty($r['value']) && $r['value'] = $l->value;
            $r['desc'] = $l->desc;
            $res[] = $r;
       }

       $json = json_encode($res);
       $json = mb_convert_encoding($json,'UTF-8','UTF-8');
        header('Content-Disposition: attachment; filename=config.json');
        die($json);
    }
    public static function addToRedis(){
        $models = Config::orderBy('created_at','desc')->get();
        foreach ($models as $k=>$v){
            $data[$v->key] = $v->value;
        }
        Redis::hmset(RedisKey::CONFIG, $data);
    }

}
