<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Type; 
use App\Models\RedisKey; 
use App\Models\Gif; 
use App\Models\Material; 
use App\Models\Material_cn; 
use App\Models\Banner; 

use Validator;
use Input;
use Storage;
use Session;
use FileTool;
use Auth;


class TypeController extends AdminBaseController
{
    protected $modelName = 'type';

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $filter = [
            'name'      => Input::get('name'),
            'parent_id' => Input::get('parent_id'),
            'sort'      => Input::get('sort'),
			'dev'       => Input::get('dev',0)

        ];
		$sign = Input::get('sign');			//素材列表栏 权重 标识
		if (isset($sign))
		{
			if ($sign == 1)
			{
				$filter['sort'] = 'weight asc';
				$sign = 0;
			}else{
				$filter['sort'] = 'weight desc';
				$sign = 1;
			}
		}else{
			$sign = 0;
		}

		Session::set('dev',$filter['dev']);
        $list = Type::getPageQuery($filter);
        return $this->render(
            'admin.' . $this->modelName . '_index', 
            [
                'list'      => $list,
                'filter'    => $filter,
                'modelName' => $this->modelName,
                'system'    => $this->getSystem(),
                'sign'    	=> $sign

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
		$dev = Session::get('dev',0);
        $types = Type::onWriteConnection()->where('parent_id', 0)->where('weight','>=',0)->where('device',$dev)->get();
        return $this->render(
            'admin.' . $this->modelName . '_create',
            [
                'modelName' => $this->modelName,
                'device' 	=> $dev,
                'types'     => $types
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
		$android_parent_id   = Input::get('android_parent_id', 0);
		$android_name   = Input::get('androidname', '');
		$android_weight   = Input::get('android_weight', '');
		$device = Session::get('dev',0);
		$dev = Input::get('dev', 2);
		$devSign = $dev;
		$dev == 2 && $devSign = 0;
		$app = 'jigsaw';
		$model = new Type;
		$model->name        = trim(Input::get('name', ''));
		$model->parent_id   = Input::get('parent_id', 0);
		$model->weight      = Input::get('weight', '');
		$model->app         = $app;
		$model->desc        = Input::get('desc','');
		$model->color1      = trim(Input::get('color1',''));
		$model->color2      = trim(Input::get('color2', ''));
		$model->bg_color    = trim(Input::get('bg_color', ''));
		$pic                = Input::file('pic','');
		$android_pic        = Input::file('android_pic','');
		$pic_asia           = Input::file('pic_asia','');
		$ios_pic_frame      = Input::file('ios_pic_frame','');
		$model->name_cn     = trim(Input::get('name_cn', ''));
		$model->name_tw     = trim(Input::get('name_tw', ''));
		$model->name_ru     = trim(Input::get('name_ru', ''));
		$model->name_jp     = trim(Input::get('name_jp', ''));
		$model->name_es     = trim(Input::get('name_es', ''));
		$model->desc_tw     = trim(Input::get('desc_tw', ''));
		$model->desc_ru     = trim(Input::get('desc_ru', ''));
		$model->desc_jp     = trim(Input::get('desc_jp', ''));
		$model->desc_es     = trim(Input::get('desc_es', ''));
		$model->desc_cn     = trim(Input::get('desc_cn', ''));
		$model->is_new      = Input::get('is_new', 0);
		$model->is_new_color    = trim(Input::get('is_new_color', ''));
		$model->font            = trim(Input::get('font', ''));
		$model->packagepay      = Input::get('packagepay', 0);
		$model->is_vip          = Input::get('is_vip', 0);
		$model->handle_user     = $username;
		$model->device     		= $devSign;
		$model->version     	= Input::get('version', 90909);
		if ($devSign == 1 && $model->version >= 200000000){
			$model->version     	= 200000000;
		}


		$checkName = Type::where('name',trim(Input::get('name')))->where('device',$dev)->first();
		if (!is_null($checkName))
		{
			return back()->withErrors(['type name 重复，请检查！'])->withInput();
		}
		if(!empty($pic))
		{
			$extension  = $pic->getClientOriginalExtension();
			$pic        = FileTool::upload($pic, 'material', $extension, 1);
			$model->pic = $pic['path'];
		}

		if(!empty($ios_pic_frame))
		{
			$extension  = $ios_pic_frame->getClientOriginalExtension();
			$ios_pic_frame        = FileTool::upload($ios_pic_frame, 'material', $extension, 1);
			$model->ios_pic_frame = $ios_pic_frame['path'];
		}
		if(!empty($android_pic))
		{
			$extension  = $android_pic->getClientOriginalExtension();
			$android_pic        = FileTool::upload($android_pic, 'material', $extension, 1);
			$model->android_pic = $android_pic['path'];
		}
		if(!empty($pic_asia))
		{
			$extension       = $pic_asia->getClientOriginalExtension();
			$pic_asia        = FileTool::upload($pic_asia, 'material', $extension, 1);
			$model->pic_asia = $pic_asia['path'];
		}
		$r = $model->save();
		if (!empty($model->pic))
		{
			Type::getSmallSizePic($model,'small_pic','304x336');
			Type::getSmallSizePic($model,'icon','150x150');
			Type::getSmallSizePic($model,'small_pic_new','1200x1085');
		}
		if ($dev == 2)
		{
			$fid = $model->id;
			$fmodel = Type::onWriteConnection()->find($fid)->toArray();
			$fmodel['parent_id']	=$android_parent_id;
			$fmodel['name']			=$android_name;
			$fmodel['weight']		=$android_weight;
			$fmodel['device']		=1;
			$fmodel['version']  	= 200000000;
			unset($fmodel['id']);
			Type::insert($fmodel);

		}
		if($r){
			if ($model->parent_id != 0 && ($dev == 0)){
				Type::updateWhatsNewIds($model->name);
			}
			return redirect('admin/' . $this->modelName.'/?dev='.$device )->with('status', '添加成功!');
		}else{
			return redirect('admin/' . $this->modelName.'/?dev='.$device )->with('status', '添加失败!');
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
        $username = Auth::user()->name;
        if ( !isset($username)|| empty($username)) 
        {
            return redirect('admin/' . $this->modelName )->with('status', '非法!');
        }
        set_time_limit(0);
        $model = Type::onWriteConnection()->find($id);

        if (is_null($model)){
            return redirect('admin/' . $this->modelName)->withErrors("纪录不存在!")->withInput();
        }
        $is_new = Input::get('is_new');
        $old_is_new = $model->is_new;
        $old_name = $model->name;
        $model->name            = trim(Input::get('name', $model->name));
        $model->name_cn         = trim(Input::get('name_cn', $model->name_cn));
        $model->name_tw         = trim(Input::get('name_tw', $model->name_tw));
        $model->name_ru         = trim(Input::get('name_ru', $model->name_ru));
        $model->name_jp         = trim(Input::get('name_jp', $model->name_jp));
        $model->name_es         = trim(Input::get('name_es', $model->name_es));
        $model->is_new          = Input::get('is_new', $model->is_new);
        $model->is_new_color    = Input::get('is_new_color', $model->is_new_color);
        $model->font            = Input::get('font', $model->font);
        $model->packagepay      = Input::get('packagepay', $model->packagepay);
        $is_vip = Input::get('is_vip');
        if ($is_vip != $model->is_vip && $model->parent_id !=0) 
        {
            $model->is_vip          = $is_vip;
            Material::updateMaterialVip($model->name,$is_vip);
        }
        $model->weight          = Input::get('weight', $model->weight);
        $model->desc            = Input::get('desc', $model->desc);
        $model->desc_tw         = trim(Input::get('desc_tw', $model->desc_tw));
        $model->desc_ru         = trim(Input::get('desc_ru', $model->desc_ru));
        $model->desc_jp         = trim(Input::get('desc_jp', $model->desc_jp));
        $model->desc_es         = trim(Input::get('desc_es', $model->desc_es));
        $model->desc_cn         = trim(Input::get('desc_cn', $model->desc_cn));
        $model->color1          = trim(Input::get('color1', $model->color1));
        $model->color2          = trim(Input::get('color2', $model->color2));
        $model->bg_color        = trim(Input::get('bg_color', $model->bg_color));
        $model->version         = trim(Input::get('version', $model->version));
        $model->device          = trim(Input::get('device', $model->device));
        $pic                    = Input::file('pic','');
        $android_pic            = Input::file('android_pic','');
        $ios_pic_frame          = Input::file('ios_pic_frame','');
        $model->handle_user  = $username . '_' . date("Y-m-d H:i:s",time()) . '_edit';
        if(!empty($pic)) {
            $pic = FileTool::upload($pic, 'material', 'jpg', 1);
            $model->pic = $pic['path'];
        }
        if(!empty($ios_pic_frame)) {
            $ios_pic_frame = FileTool::upload($ios_pic_frame, 'material', 'jpg', 1);
            $model->ios_pic_frame = $ios_pic_frame['path'];
        }
        if(!empty($android_pic)) {
            $android_pic = FileTool::upload($android_pic, 'material', 'jpg', 1);
            $model->android_pic = $android_pic['path'];
        }
        if($model->save())
        {
            if (!empty($pic)) 
            {
                Type::getSmallSizePic($model,'small_pic','304x336');
                Type::getSmallSizePic($model,'icon','150x150');
				Type::getSmallSizePic($model,'small_pic_new','1200x1085');
            }
            if ($is_new != $old_is_new && ($model->device == 0 || $model->device == 2))
			{
				Type::updateWhatsNewIds(null,1);
			}
			if ($model->name != $old_name)
			{
				Type::updateMaterialType($model,$old_name);
			}
            return redirect()->back()->with('status', '修改成功!');
        }else {
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
        set_time_limit(0);
        $model = Type::onWriteConnection()->find($id);
        $model->handle_user  = $username . '_' . date("Y-m-d H:i:s",time()) . '_delete';
        $model->save();
        if (is_null($model)) {
            abort(404);
        }
		if ($model->device == 0)
		{
			Type::updateWhatsNewIds($model->name,null,1);
		}
		Type::delMaterials($model->name);
        $model->delete();
       
        return redirect('admin/' . $this->modelName)->with('status', '删除成功!');
    }


    public function typeBatch()
    {
        $filter = [
            'name'      => Input::get('name'),
            'parent_id' => Input::get('parent_id'),
            'sort'      => Input::get('sort'),
            'desc'      => Input::get('desc'),
            'color'     => Input::get('color'),
			'dev'     	=> Session::get('dev',0)
        ];

        $list = Type::getPageQuery($filter);

        return $this->render(
            'admin.' . 'type_batch', 
            [
                'list'      => $list,
                'filter'    => $filter,
                'modelName' => $this->modelName
            ]
        );
    }

    
    public function updateTypes()
    {
        $username = Auth::user()->name;
        if ( !isset($username)|| empty($username)) 
        {
            return redirect('admin/' . $this->modelName )->with('status', '非法!');
        }
        set_time_limit(0);
        $ids = Input::get('id');
        $name    = Input::get('name');
        $name_cn = Input::get('name_cn');
        $name_tw = Input::get('name_tw');
        $name_ru = Input::get('name_ru');
        $name_jp = Input::get('name_jp');
        $name_es = Input::get('name_es');
        $desc    = Input::get('desc',[]);
        $desc_tw = Input::get('desc_tw',[]);
        $desc_ru = Input::get('desc_ru',[]);
        $desc_jp = Input::get('desc_jp',[]);
        $desc_es = Input::get('desc_es',[]);
        $desc_cn = Input::get('desc_cn',[]);
        $color1   = Input::get('color1',[]);
        $color2   = Input::get('color2',[]);
        $bg_color   	= Input::get('bg_color',[]);
        $packagepay   	= Input::get('packagepay',[]);
        $is_vip   		= Input::get('is_vip',[]);
        $weight  		= Input::get('weight',[]);
        $version  		= Input::get('version',[]);
        $pic     		= Input::file('pic',[]);
        $android_pic  	= Input::file('android_pic',[]);
        $ios_pic_frame  	= Input::file('ios_pic_frame',[]);

        foreach ($ids as $key => $id) 
        {
            $model = Type::onWriteConnection()->find($id);
			$old_name = $model->name;
            $model->name    = trim($name[$key]);
            $model->name_cn = trim($name_cn[$key]);
            $model->name_tw = trim($name_tw[$key]);
            $model->name_ru = trim($name_ru[$key]);
            $model->name_jp = trim($name_jp[$key]);
            $model->name_es = trim($name_es[$key]);
            $model->desc    = trim($desc[$key]);
            $model->desc_cn = trim($desc_cn[$key]);
            $model->desc_tw = trim($desc_tw[$key]);
            $model->desc_ru = trim($desc_ru[$key]);
            $model->desc_jp = trim($desc_jp[$key]);
            $model->desc_es = trim($desc_es[$key]);
            $model->color1  = trim($color1[$key]);
            $model->color2  = trim($color2[$key]);
            $model->bg_color    = trim($bg_color[$key]);
            $model->packagepay  = trim($packagepay[$key]);
            $vip = trim($is_vip[$key]);
//			if ($weight[$key] != $model->weight && $model->device == 0)
//			{
//				Type::updateWhatsNewIds(null,1);
//			}
            if ($vip != $model->is_vip && $model->parent_id !=0) 
            {
                $model->is_vip          = $vip;
                Material::updateMaterialVip($model->name,$vip);
            }
            $model->weight      = trim($weight[$key]);
            $model->version      = trim($version[$key]);
            $model->handle_user = $username . '_' . date("Y-m-d H:i:s",time()) . '_batchedit';
            
            if(!empty($pic[$key])) {
                $pics = FileTool::upload($pic[$key], 'material', 'jpg', 1);
                $model->pic = $pics['path'];
            }
            if(!empty($android_pic[$key])) {
                $android_pics = FileTool::upload($android_pic[$key], 'material', 'jpg', 1);
                $model->android_pic = $android_pics['path'];
            }
			if(!empty($ios_pic_frame[$key])) {
				$ios_pic_frames = FileTool::upload($ios_pic_frame[$key], 'material', 'jpg', 1);
				$model->ios_pic_frame = $ios_pic_frames['path'];
			}
            $model->save();
            if (!empty($pic[$key])) 
            {
                Type::getSmallSizePic($model,'small_pic','304x336');
                Type::getSmallSizePic($model,'icon','150x150');
				Type::getSmallSizePic($model,'small_pic_new','1200x1085');
            }
			if ($model->name != $old_name)
			{
				Type::updateMaterialType($model,$old_name);
			}

        }
        return redirect()->back()->with('status', '修改成功!');
    }

     public function getCreateTypes()
	 {
	 	$callback = $_GET['callback'];
	 	$dev = Input::get('dev',0);
	 	$type = Input::get('flag',0);
		 $data = [];
		 $datandroid = [];
	 	if ($dev == 2 && $type === 'android'){
			$lists = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',0)->get();
			foreach ($lists as $key => $l) {
				$sub_types = Type::onWriteConnection()->where('parent_id', $l->id)->where('weight', '>=', 0)->where('device', '!=',0)->get();
				$d = [];
				foreach ($sub_types as $key => $value) {
					$d[] = $value->name;

				}
				$datandroid[$l->name] = $d;
			}
			return $callback . '(' . json_encode($datandroid) .')' ;
		}
		 if ($dev == 2 && $type === 'type'){			//create type all的情况
			 $datas = [];
			 $android = [];
			 $ios = [];
			 $iosLists = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',1)->get();
			 $andLists = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',0)->get();
			 foreach ($iosLists as $key => $l) {
				 $ios[$l->id] = $l->name;
			 }
			 foreach ($andLists as $key => $l) {
				 $android[$l->id] = $l->name;
			 }
			 $datas['ios'] = $ios;
			 $datas['and'] = $android;
			 return $callback . '(' . json_encode($datas) .')' ;
		 }
	 	if ($type === 0 || $dev == 2){
			$dev == 2 && $dev = 0;
		}
	 	$de = 0;
	 	$dev == 0 && $de = 1;
		$list = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',$de)->get();

		$typedata = [];
		foreach ($list as $key => $l) {
		 $sub_types = Type::onWriteConnection()->where('parent_id', $l->id)->where('weight', '>=', 0)->where('device', '!=',$de)->get();
		 $d = [];
		 foreach ($sub_types as $key => $value) {
			 $d[] = $value->name;

		 }
		 $data[$l->name] = $d;
		 $typedata[$l->id] = $l->name;
		}

		 if ($type === 'type'){
			 return $callback . '(' . json_encode($typedata) .')' ;
		 }else{
			 return $callback . '(' . json_encode($data) .')' ;
		 }

	 }
 
}
