<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\NotifiTiming; 

use Validator;
use Input;
use Storage;
use Redis;

class NotifiTimingController extends AdminBaseController
{

	protected $modelName = 'NotifiTiming';   

    public function create(){

        $pageInfo = array(
            'title'         => "添加推送",
            'post_link'     => url('admin/notifiTiming/'),
        );

        $page = view('admin.notifiTiming_create')->with('pageInfo',$pageInfo);
        $html = response($page)->getContent();

        $array = array(
            'html'=>$html,
            'status'=>0
        );

        return json_encode($array);     
    }

//添加
    public function store(Request $request){
        // dd($_POST);
        $this->validate($request, [
            'title' => 'required|max:255',
            'msg' => 'required',
            'push_day' => 'required|max:255',
        ]);
      
        $model = new NotifiTiming;
        $model->app = Input::get('app');
        $model->title = Input::get('title');
        $model->msg = Input::get('msg');
        $model->type = Input::get('type');
        $model->push_from = Input::get('push_from');
        $model->action = Input::get('action');
        $model->push_day = Input::get('push_day');
        $model->out_id = Input::get('out_id');
        $model->clt = Input::get('clt');
        $model->token = Input::get('token');
        $model->is_pass = Input::get("ispass",0);

        $ret = $model->save();
        
        return  redirect('admin/pushStrategy')->with('status', '添加成功!');
    }

    public function edit($id){
        
        $notifiInfo = NotifiTiming::find($id);

        $pageInfo = array(
            'title'         => "编辑推送",
            'post_link'     => url('admin/notifiTiming/'.$id),
        );
        $page = view('admin.notifiTiming_create')->
                with('pageInfo',$pageInfo)->
                with("notifiInfo",$notifiInfo);
        $html = response($page)->getContent();

        $array = array(
            'html'=>$html,
            'status'=>0
        );

        return json_encode($array); 
    }

    public function update($id){
      

        $model = NotifiTiming::find($id);

        $model->app = Input::get('app');
        $model->title = Input::get('title');
        $model->msg = Input::get('msg');
        $model->type = Input::get('type');
        $model->push_from = Input::get('push_from');
        $model->action = Input::get('action');
        $model->push_day = Input::get('push_day');
        $model->out_id = Input::get('out_id');
        $model->clt = Input::get('clt');
        $model->token = Input::get('token');
        $model->is_pass = Input::get("ispass",0);

        $ret = $model->save();

        return  redirect('admin/pushStrategy')->with('status', '修改成功!');

    }

    public function destroy($id){
        $model = NotifiTiming::find($id);
        if (is_null($model)) {
            abort(404);
        }

        $model->status = 2;
        if($model->save()){
            $array = array(
                'info'=>"删除成功！",
                'status'=>0
            );
        }else{
            $array = array(
                'info'=>"删除失败！",
                'status'=>1
            );
        }
       
        return json_encode($array); 
    }

}



















