<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Type; 
use App\Models\Material; 
use App\Models\Material_cn; 

use App\Models\Banner; 
use App\Models\RedisKey; 


use Input;
use FileTool;
use Redis;
use Session;
use Log;
use Auth;

class BannerController extends AdminBaseController
{
    protected $modelName = 'banner';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $filter = [
            'sort'      => Input::get('sort'),
            'position'  => Input::get('position'),
            'dev'       => Input::get('dev',0)
        ];
        Session::set('dev',$filter['dev']);
        $list = Banner::getPageQuery($filter);
        foreach ($list as &$value) 
        {
            if ($value['open_num'] == 0 || $value['view_num'] == 0) 
            {
                $value['openrate'] = 0;
            }else{
                $value['openrate'] = sprintf("%.4f",$value['open_num']/$value['view_num']) * 100;
            }
            
        }
        return $this->render(
            'admin.' . $this->modelName . '_index', 
            [
                'list'      => $list,
                'filter'    => $filter,
                'modelName' => $this->modelName
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
                'modelName'         => $this->modelName
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
        $username = Auth::user()->name;
        if ( !isset($username)|| empty($username)) 
        {
            return redirect('admin/' . $this->modelName )->with('status', '非法!');
        }
        set_time_limit(0);
        $dev = Session::get('dev',0);
        $r          = null;
        $model      = new Banner;
        $large_pic          = Input::file('large_pic');
        $model->position    = Input::get('position', 'home');
        $model->app         = 'jigsaw';
        $model->title       = Input::get('title', '');
        $model->title_cn    = Input::get('title_cn', '');
        $model->title_tw    = Input::get('title_tw', '');
        $model->title_jp    = Input::get('title_jp', '');
        $model->title_ru    = Input::get('title_ru', '');
        $model->title_es    = Input::get('title_es', '');

        $model->desc        = Input::get('desc', '');
        $model->desc_cn     = Input::get('desc_cn', '');
        $model->desc_tw     = Input::get('desc_tw', '');
        $model->desc_jp     = Input::get('desc_jp', '');
        $model->desc_ru     = Input::get('desc_ru', '');
        $model->desc_es     = Input::get('desc_es', '');

        $model->weight      = Input::get('weight', 0);
        $model->version     = Input::get('version', 90909);
        $model->color       = Input::get('color', '');
        $model->device      = $dev;
        $model->handle_user = $username;
		if ($dev == 1 && $model->version >= 200000000){
			$model->version     	= 200000000;
		}
		$model->recommend_date = Input::get('recommend_date', '');
		$model->android_mid = Input::get('android_mid', 0);
		$model->ios_mid = Input::get('ios_mid', 0);

        if(!empty($large_pic))
        {
            $res = FileTool::upload($large_pic, 'material', 'jpg', 1); 
            $model->large_pic = $res['path'];
            if($res['size'] > 1024 * 1024){
                return redirect('admin/' . $this->modelName .'/?dev='.$dev )->with('status', '添加失败!图片必须小于1M');
            }
        }
       
        $r = $model->save();
        
        if($r)
        {
            return redirect('admin/' . $this->modelName.'/?dev='.$dev )->with('status', '添加成功!');
        }else{
            return back()->withErrors(['添加失败!'])->withInput();
        }
    }

   


   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $model = MaterialProduct::onWriteConnection()->find($id);

        if (is_null($model)) {
            abort(404);
        }
        
        return $this->render(
            'admin.' . $this->modelName . '_edit',
            [
                'model'         => $model,
                'modelName'     => $this->modelName        
            ]
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
        $username = Auth::user()->name;
        if ( !isset($username)|| empty($username)) 
        {
            return redirect('admin/' . $this->modelName )->with('status', '非法!');
        }
        set_time_limit(0);
        $model = Banner::onWriteConnection()->find($id);
        $model->position = Input::get('position', $model->position);
        $model->weight = Input::get('weight', $model->weight);
        $model->title = Input::get('title', $model->title);
        $model->title_cn = Input::get('title_cn', $model->title_cn);
        $model->title_tw = Input::get('title_tw', $model->title_tw);
        $model->title_jp = Input::get('title_jp', $model->title_jp);
        $model->title_ru = Input::get('title_ru', $model->title_ru);
        $model->title_es = Input::get('title_ru', $model->title_es);

        $model->desc    = Input::get('desc', $model->desc);
        $model->desc_cn = Input::get('desc_cn', $model->desc_cn);
        $model->desc_tw = Input::get('desc_tw', $model->desc_tw);
        $model->desc_jp = Input::get('desc_jp', $model->desc_jp);
        $model->desc_ru = Input::get('desc_ru', $model->desc_ru);
        $model->desc_es = Input::get('desc_ru', $model->desc_es);
        
        $model->color           = Input::get('color', $model->color);
        
        $model->version         = Input::get('version', $model->version);
        $model->handle_user     = $username;
        
        $large_pic = Input::file('large_pic');
        if(!empty($large_pic))
        {

           $res = FileTool::upload($large_pic, 'material', 'jpg', 1); 
           $model->large_pic = $res['path'];
           if($res['size'] > 1024 * 1024){
                return redirect()->back()->with('status',  '添加失败!图片必须小于1M');
            }
            
        }
        // if ($model->app == 'puzzle') 
        // {
            $model->android_mid = Input::get('android_mid', $model->android_mid);
            $model->ios_mid = Input::get('ios_mid', $model->ios_mid);
            $model->recommend_date  = Input::get('recommend_date', $model->recommend_date);
        // }
        if ($model->save()){
            return redirect()->back()->with('status', '修改成功!');
        } else {
            return redirect()->back()->with('status', '修改失败!');
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
        $username = Auth::user()->name;
        if ( !isset($username)|| empty($username)) 
        {
            return redirect('admin/' . $this->modelName )->with('status', '非法!');
        }
        $model = Banner::onWriteConnection()->find($id);
        $model->handle_user = $username . '_' . date("Y-m-d H:i:s",time()) . '_delete';
        $model->save();
        if (is_null($model)) {
            abort(404);
        }
        $model->delete();
        return redirect()->back()->with('status', '删除成功!');
    }

    public function bannerBatch()
    {
        $filter = [
            'sort'      => Input::get('sort'),
            'position'  => Input::get('position'),
            'dev'       => Session::get('dev',0)
        ];

        $list = Banner::getPageQuery($filter);
        return $this->render(
            'admin.' . $this->modelName . '_batch', 
            [
                'list'      => $list,
                'filter'    => $filter,
                'modelName' => $this->modelName,


            ]
        );

    }
    public function updateBatch()
    {
        $username = Auth::user()->name;
        if ( !isset($username)|| empty($username)) 
        {
            return redirect('admin/' . $this->modelName )->with('status', '非法!');
        }
        $ids        = Input::get('id');
        $weight     = Input::get('weight',[]);
        $position   = Input::get('position',[]);
        $title      = Input::get('title',[]);
        $title_cn   = Input::get('title_cn',[]);
        $title_tw   = Input::get('title_tw',[]);
        $title_jp   = Input::get('title_jp',[]);
        $title_ru   = Input::get('title_ru',[]);
        $title_es   = Input::get('title_es',[]);

        $desc       = Input::get('desc',[]);
        $desc_cn    = Input::get('desc_cn',[]);
        $desc_tw    = Input::get('desc_tw',[]);
        $desc_jp    = Input::get('desc_jp',[]);
        $desc_ru    = Input::get('desc_ru',[]);
        $desc_es    = Input::get('desc_es',[]);

        $color              = Input::get('color',[]);
        $recommend_date     = Input::get('recommend_date',[]);
        $version            = Input::get('version',[]);
        $large_pic          = Input::file('large_pic',[]);
        $android_mid        = Input::get('android_mid',[]);
        $ios_mid            = Input::get('ios_mid',[]);
        foreach ($ids as $key => $id) 
        {   
            $model = Banner::onWriteConnection()->find($id);
            if(!empty($large_pic[$key]))
            {
                
                $res = FileTool::upload($large_pic[$key], 'material', 'jpg', 1);
                $model->large_pic = $res['path'];
                if($res['size'] > 1024 * 1024){
                    return redirect('admin/' . $this->modelName )->with('status', '添加失败!图片必须小于1M');
                }
            }
            
            
            $model->weight      = $weight[$key];
            $model->position    = $position[$key];
            $model->color       = $color[$key];
            $model->title       = $title[$key];
            $model->title_cn    = $title_cn[$key];
            $model->title_tw    = $title_tw[$key];
            $model->title_jp    = $title_jp[$key];
            $model->title_ru    = $title_ru[$key];
            $model->title_es    = $title_es[$key];

            $model->desc        = $desc[$key];
            $model->desc_cn     = $desc_cn[$key];
            $model->desc_tw     = $desc_tw[$key];
            $model->desc_jp     = $desc_jp[$key];
            $model->desc_ru     = $desc_ru[$key];
            $model->desc_es     = $desc_es[$key];
            
            $model->version     = $version[$key];
            // if ($app == 'puzzle') 
            // {
                $model->android_mid     = $android_mid[$key];
                $model->ios_mid         = $ios_mid[$key];
                $model->recommend_date  = $recommend_date[$key];
            // }
            $model->save();
        }
        return redirect()->back()->with('status', '修改成功!');
    }
    
 
}
