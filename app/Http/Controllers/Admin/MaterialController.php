<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Material;
use App\Models\Type;
use App\Models\MaterialUsed;
use App\Models\RedisKey;
use App\Models\AdCollect;
use App\Models\RedisToCnMysql;


use Input;
use FileTool;
use Redis;
use Session;
use DB;
use Auth;
use Storage;
use Log;

class MaterialController extends AdminBaseController
{
	protected $modelName = 'material';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$filter = [
			'sub_type' => Input::get('sub_type'),
			'sort' => Input::get('sort'),
			'type' => trim(Input::get('type')),
			'is_vip' => Input::get('is_vip'),
			'is_pass' => Input::get('is_pass', ''),
			'dev' => Input::get('dev', 0)
		];
		Session::set('dev', $filter['dev']);
		$devflag = 0;
		$filter['dev'] == 0 && $devflag = 1;
		$list = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',$devflag)->select('id','name','parent_id','weight','device')->get();
		$data = array();
		foreach ($list as $key => $l) {
			$sub_types = Type::where('parent_id', $l->id)->where('weight', '>=', 0)->where('device',  '!=',$devflag)->select('id','name','parent_id','weight','device')->get();
			$d = [];
			foreach ($sub_types as $key => $value) {
				$d[] = $value->name;
			}
			$data[$l->name] = $d;
		}
		$type_json = json_encode($data);
//        $roleArr = explode(',', Auth::user()->role_id);

		header('Content-type:text/html; charset=utf-8');
		if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $filter['type']) > 0 || preg_match('/[\x{4e00}-\x{9fa5}]/u', $filter['sub_type']) > 0) {
			return back()->withErrors(['查询含有非法字符!']);
		}
		$list = Material::getPageQuery($filter);
		return $this->render(
			'admin.' . $this->modelName . '_index',
			[
				'list' => $list,
				'filter' => $filter,
				'modelName' => $this->modelName,
				'type_json' => $type_json
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
		$dev = 0;
		$list = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',1)->get();
		$data = [];
		foreach ($list as $key => $l) {
			$sub_types = Type::onWriteConnection()->where('parent_id', $l->id)->where('weight', '>=', 0)->where('device', '!=',1)->get();
			$d = [];
			foreach ($sub_types as $key => $value) {
				$d[] = $value->name;

			}
			$data[$l->name] = $d;
		}
		$type_json = json_encode($data);

		//android
		$lists = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',0)->get();
		$datas = [];
		foreach ($lists as $key => $l) {
			$sub_types = Type::onWriteConnection()->where('parent_id', $l->id)->where('weight', '>=', 0)->where('device', '!=',0)->get();
			$d = [];
			foreach ($sub_types as $key => $value) {
				$d[] = $value->name;

			}
			$datas[$l->name] = $d;
		}
		$atype_json = json_encode($datas);
		return $this->render(
			'admin.' . $this->modelName . '_create',
			[
				'modelName' => $this->modelName,
				'type_json' => $type_json,
				'atype_json' => $atype_json

			]
		);


	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  request $request
	 * @return Response
	 */
	public function storeTest(Request $request)
	{
		set_time_limit(0);
		$username = Auth::user()->name;
		if (!isset($username) || empty($username)) {
			return redirect('admin/' . $this->modelName)->with('status', '非法!');
		}
		set_time_limit(0);
//		$dev             = Session::get('dev', 0);
		$devflag         = Input::get('dev', 0);
		$dev = $devflag;
		$dev == 2 && $dev = 0;
		$r               = null;
		$type            = Input::get('type', '');
		$app             = 'jigsaw';
		$sub_type        = Input::get('sub_type', '');
		$android_type        = Input::get('android_type', '');
		$android_sub_type    = Input::get('android_sub_type', '');
		$unique_name     = Input::get('unique_name', []);
		$display_name    = Input::get('display_name', []);
		$display_color   = Input::get('display_color', []);
		$display_color2  = Input::get('display_color2', []);
		$desc            = Input::get('desc', []);
		$weight          = Input::get('weight', []);
		$display_name_cn = Input::get('display_name_cn', []);
		$display_name_tw = Input::get('display_name_tw', []);
		$display_name_jp = Input::get('display_name_jp', []);
		$display_name_ru = Input::get('display_name_ru', []);
		$desc_cn         = Input::get('desc_cn', []);
		$desc_tw         = Input::get('desc_tw', []);
		$desc_jp         = Input::get('desc_jp', []);
		$desc_ru         = Input::get('desc_ru', []);
		$android_vip     = Input::get('is_vip', []);
		$ios_vip     	 = Input::get('ios_is_vip', []);
		$large_pic       = Input::file('large_pic', []);

		$coin				=	Input::get('coin', []);
		$level_20_coin		=	Input::get('level_20_coin', []);
		$level_704_coin		=	Input::get('level_704_coin', []);
		$level_48_coin		=	Input::get('level_48_coin', []);
		$level_108_coin		=	Input::get('level_108_coin', []);
		$level_288_coin		=	Input::get('level_288_coin', []);
		$level_432_coin		=	Input::get('level_432_coin', []);
		$level_24_coin		=	Input::get('level_24_coin', []);
		$level_70_coin		=	Input::get('level_70_coin', []);
		$level_180_coin		=	Input::get('level_180_coin', []);
		$level_336_coin		=	Input::get('level_336_coin', []);
		$level_504_coin		=	Input::get('level_504_coin', []);

		if (empty($large_pic)) {
			return redirect('admin/' . $this->modelName . '/?dev=' . $dev)->with('status', '素材图为空！添加失败!');
		}
		$rcount = 0;
		DB::beginTransaction();
		try {

			foreach ($large_pic as $k => $v)
			{
				if (empty($v)) {
					continue;
				}
				$puMod           = new Material;
				$puMod->score    = 999999;
				$puMod->is_new   = 1;
				$puMod->type     = $type;
				$puMod->sub_type = $sub_type;

				$puMod->weight = $weight[$k];

				$puMod->app            = trim($app);
				$puMod->display_color  = $display_color[$k];
				$puMod->display_color2 = $display_color2[$k];

				$puMod->unique_name     = trim($unique_name[$k]);
				$puMod->display_name    = trim($display_name[$k]);
				$puMod->display_name_cn = trim($display_name_cn[$k]);
				$puMod->display_name_tw = trim($display_name_tw[$k]);
				$puMod->display_name_jp = trim($display_name_jp[$k]);
				$puMod->display_name_ru = trim($display_name_ru[$k]);
				$puMod->desc            = trim($desc[$k]);
				$puMod->desc_cn         = trim($desc_cn[$k]);
				$puMod->desc_tw         = trim($desc_tw[$k]);
				$puMod->desc_jp         = trim($desc_jp[$k]);
				$puMod->desc_ru         = trim($desc_ru[$k]);
				$puMod->coin            = $coin[$k];
				$puMod->level_20_coin   = $level_20_coin[$k];
				$puMod->level_704_coin  = $level_704_coin[$k];
				$puMod->level_48_coin   = $level_48_coin[$k];
				$puMod->level_108_coin  = $level_108_coin[$k];
				$puMod->level_288_coin  = $level_288_coin[$k];
				$puMod->level_432_coin  = $level_432_coin[$k];
				$puMod->level_24_coin   = $level_24_coin[$k];
				$puMod->level_70_coin  	= $level_70_coin[$k];
				$puMod->level_180_coin  = $level_180_coin[$k];
				$puMod->level_336_coin  = $level_336_coin[$k];
				$puMod->level_504_coin  = $level_504_coin[$k];



				$extension             = $v->getClientOriginalExtension();
//			$t1 = microtime(true);
				$large_pic             = FileTool::upload($v, 'material', $extension, 1);
//			$t2 = microtime(true);
//			Log::info('save  $large_pic::::'.'耗时'.round($t2-$t1,3).'秒');
				$puMod->large_pic      = $large_pic['path'];
				$puMod->large_pic_size = $large_pic['size'];
				$puMod->width 			= $large_pic['width'];
				$puMod->height 			= $large_pic['height'];
//			$t1 = microtime(true);
				$puMod->en_material    	= Material::getEncrypt($puMod->large_pic);
//			$puMod->en_material1    = Material::getEncryptBySize($puMod->large_pic,$puMod->en_material,$large_pic['width']*0.4 . 'x' . $large_pic['height']*0.4,1);
//			$puMod->en_material2    = Material::getEncryptBySize($puMod->large_pic,$puMod->en_material,$large_pic['width']*0.5 . 'x' . $large_pic['height']*0.5,2);
//			$puMod->en_material6    = Material::getEncryptBySize($puMod->large_pic,$puMod->en_material,$large_pic['width']*0.8 . 'x' . $large_pic['height']*0.8,6);
//			$t2 = microtime(true);
//			Log::info('save  加密::::'.'耗时'.round($t2-$t1,3).'秒');
				$puMod->user   = $username;
				if ($dev == 1){
					$puMod->is_vip = $android_vip[$k];
				}else{
					$puMod->is_vip = $ios_vip[$k];
				}
				$puMod->device	= $dev;
				$r = $puMod->save();
				if (!$r)
				{
					++$rcount;
					break;
				}
				//素材缩略图存在puzzle_small_pic字段里
				//		Material::getSmallPic($puMod,'small_pic','350x263');
				//		Material::getSmallPic($puMod, 'small_pic', '400x300');
//			$t1 = microtime(true);
				Material::getSmallPic($puMod, 'small_pic', $large_pic['width']*0.4 . 'x' . $large_pic['height']*0.4);
				Material::getSmallPic($puMod, 'small_pic_new', $large_pic['width']*0.8 . 'x' . $large_pic['height']*0.8);
				Material::getSmallPic($puMod, 'icon', $large_pic['width']*0.1 . 'x' . $large_pic['height']*0.1);
				Material::getSmallPic($puMod, 'banner_pic', '600x450');
//			$t2 = microtime(true);
//			Log::info('save  切图::::'.'耗时'.round($t2-$t1,3).'秒');
//			$t1 = microtime(true);
//			FileTool::getImageUrlCnNew($puMod->large_pic,1);
//			FileTool::getImageUrlCnNew($puMod->small_pic,1);
//			FileTool::getImageUrlCnNew($puMod->small_pic_new,1);
//			FileTool::getImageUrlCnNew($puMod->icon,1);
//			FileTool::getImageUrlCnNew($puMod->en_material,1);
//			FileTool::getImageUrlCnNew($puMod->en_material1,1);
//			FileTool::getImageUrlCnNew($puMod->en_material2,1);
//			FileTool::getImageUrlCnNew($puMod->en_material6,1);

				RedisToCnMysql::addToQueue($puMod->id);

//			$t2 = microtime(true);
//			Log::info('save  getImageUrlCnNew::::'.'耗时'.round($t2-$t1,3).'秒');
				if ($dev == 0) {
					Material::getWhatsNewIds($sub_type);
				}
				if ($devflag == 2){
					$iosItem = Material::find($puMod->id)->toArray();
					$iosItem['type'] = $android_type;
					$iosItem['sub_type'] = $android_sub_type;
					$iosItem['is_vip'] = $android_vip[$k];
					$iosItem['device'] = 1;
					unset($iosItem['id']);
					Material::insert($iosItem);
				}
			}
			if ($rcount == 0)
			{
				DB::commit();
				return redirect('admin/' . $this->modelName . '/?dev=' . $dev)->with('status', '添加成功!');
			}

		} catch (\Exception $e) {
			DB::rollBack();
		}
		return redirect('admin/' . $this->modelName . '/?dev=' . $dev)->with('status', '添加失败!');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function edit($id)
	{
		$model = Material::onWriteConnection()->find($id);

		if (is_null($model)) {
			abort(404);
		}
		$dev = Session::get('dev', 0);
		$devflag = 0;
		$dev == 0 && $devflag = 1;
		$list = Type::where('parent_id', 0)->where('weight', '>=', 0)->where('device', '!=',$devflag)->get();
		$data = [];
		foreach ($list as $key => $l) {
			$sub_types = Type::onWriteConnection()->where('parent_id', $l->id)->where('weight', '>=', 0)->where('device', '!=',$devflag)->get();
			$d = [];
			foreach ($sub_types as $key => $value) {
				$d[] = $value->name;

			}
			$data[$l->name] = $d;
		}
		$type_json = json_encode($data);
		return $this->render(
			'admin.' . $this->modelName . '_edit',
			[
				'model' => $model,
				'modelName' => $this->modelName,
				'type_json' => $type_json,]
		);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  request $request
	 * @param  int $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$username = Auth::user()->name;
		if (!isset($username) || empty($username)) {
			return redirect('admin/' . $this->modelName)->with('status', '非法!');
		}
		set_time_limit(0);
		$model = Material::onWriteConnection()->find($id);
		if (is_null($model)) {
			return redirect('admin/' . $this->modelName)->withErrors("纪录不存在!")->withInput();
		}
		$model->type           = trim(Input::get('type', $model->type));
		$model->sub_type       = trim(Input::get('sub_type', $model->sub_type));
		$model->unique_name    = trim(Input::get('unique_name', $model->unique_name));
		$model->display_color  = Input::get('display_color', $model->display_color);
		$model->display_color2 = Input::get('display_color2', $model->display_color2);
		$model->version        = Input::get('version', $model->version);
		$model->is_vip          = Input::get('is_vip', $model->is_vip);

		$large_pic = Input::file('large_pic');
		if (!empty($large_pic)) {
			$large_pic             = FileTool::upload($large_pic, 'material', 'jpg', 1,$model->large_pic);
			$model->large_pic      = $large_pic['path'];
			$model->large_pic_size = $large_pic['size'];
			$model->width 			= $large_pic['width'];
			$model->height 			= $large_pic['height'];
//			$model->en_material    = Material::getEncrypt($model->large_pic);
			$model->en_material    = Material::getEncryptOneName($model->large_pic,$model->en_material);
//			$model->en_material1    = Material::getEncryptBySize($model->large_pic,$model->en_material,$large_pic['width']*0.4 . 'x' . $large_pic['height']*0.4,1);
//			$model->en_material2    = Material::getEncryptBySize($model->large_pic,$model->en_material,$large_pic['width']*0.5 . 'x' . $large_pic['height']*0.5,2);
//			$model->en_material6    = Material::getEncryptBySize($model->large_pic,$model->en_material,$large_pic['width']*0.8 . 'x' . $large_pic['height']*0.8,6);
		}
		// $large_pic_new = Input::file('large_pic_new');
		// if(!empty($large_pic_new))
		// {
		//    $model->large_pic_new = FileTool::upload($large_pic_new, 'material', 'jpg', 0);
		// }
		$model->display_name    = trim(Input::get('display_name', $model->display_name));
		$model->display_name_cn = trim(Input::get('display_name_cn', $model->display_name_cn));
		$model->display_name_tw = trim(Input::get('display_name_tw', $model->display_name_tw));
		$model->display_name_ru = trim(Input::get('display_name_ru', $model->display_name_ru));
		$model->display_name_jp = trim(Input::get('display_name_jp', $model->display_name_jp));

		$model->desc    = trim(Input::get('desc', $model->desc));
		$model->desc_cn = trim(Input::get('desc_cn', $model->desc_cn));
		$model->desc_tw = trim(Input::get('desc_tw', $model->desc_tw));
		$model->desc_ru = trim(Input::get('desc_ru', $model->desc_ru));
		$model->desc_jp = trim(Input::get('desc_jp', $model->desc_jp));

		$model->coin           = Input::get('coin', $model->coin);
		$model->level_20_coin  = Input::get('level_20_coin', $model->level_20_coin);
		$model->level_704_coin = Input::get('level_704_coin', $model->level_704_coin);
		$model->level_48_coin  = Input::get('level_48_coin', $model->level_48_coin);
		$model->level_108_coin = Input::get('level_108_coin', $model->level_108_coin);
		$model->level_288_coin = Input::get('level_288_coin', $model->level_288_coin);
		$model->level_432_coin = Input::get('level_432_coin', $model->level_432_coin);
		$model->level_24_coin  	= Input::get('level_24_coin', $model->level_24_coin);
		$model->level_70_coin 	= Input::get('level_70_coin', $model->level_70_coin);
		$model->level_180_coin  = Input::get('level_180_coin', $model->level_180_coin);
		$model->level_336_coin 	= Input::get('level_336_coin', $model->level_336_coin);
		$model->level_504_coin 	= Input::get('level_504_coin', $model->level_504_coin);
		$model->user           	= $username . '_' . date("Y-m-d H:i:s", time()) . '_edit';

		if ($model->save()) {
			if (!empty($large_pic)) {
				Material::getSmallPic($model, 'small_pic', $model->width*0.4 . 'x' . $model->height*0.4);
				Material::getSmallPic($model, 'small_pic_new', $model->width*0.8 . 'x' . $model->height*0.8);
				Material::getSmallPic($model, 'icon', '150x150');
				Material::getSmallPic($model, 'banner_pic', '600x450');

//				FileTool::getImageUrlCnNew($model->large_pic,1);
//				FileTool::getImageUrlCnNew($model->small_pic,1);
//				FileTool::getImageUrlCnNew($model->small_pic_new,1);
//				FileTool::getImageUrlCnNew($model->icon,1);
//				FileTool::getImageUrlCnNew($model->en_material,1);
//				FileTool::getImageUrlCnNew($model->en_material1,1);
//				FileTool::getImageUrlCnNew($model->en_material2,1);
//				FileTool::getImageUrlCnNew($model->en_material6,1);
				RedisToCnMysql::addToQueue($model->id);
			}
			if ($model->device == 0) {
				Material::getWhatsNewIds($model->sub_type);
			}
			return redirect('admin/' . $this->modelName . '/?dev=' . $model->device)->with('status', '修改成功!');
		} else {
			return redirect('admin/' . $this->modelName . '/?dev=' . $model->device)->with('status', '修改失败!');
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$username = Auth::user()->name;
		if (!isset($username) || empty($username)) {
			return redirect('admin/' . $this->modelName)->with('status', '非法!');
		}
		$model = Material::onWriteConnection()->find($id);
		if (is_null($model)) {
			abort(404);
		}
		$model->user = $username . '_' . date("Y-m-d H:i:s", time()) . '_delete';
		$model->save();
		$model->delete();
		return redirect()->back()->with('status', '删除成功!');
	}

	public function materialBatch()
	{
		$filter = [
			'sub_type' => Input::get('sub_type'),
			'sort' => Input::get('sort'),
			'type' => Input::get('type'),
			'is_vip' => Input::get('is_vip'),
			'is_pass' => Input::get('is_pass', ''),
			'pageNum' => 20,
			'dev' => Session::get('dev', 0)
		];

		$list = Material::getPageQuery($filter);
		return $this->render(
			'admin.material_batch',
			[
				'list' => $list,
				'filter' => $filter,
				'modelName' => $this->modelName
			]
		);

	}

	public function updateBatchMaterials()
	{
		$username = Auth::user()->name;
		if (!isset($username) || empty($username)) {
			return redirect('admin/' . $this->modelName)->with('status', '非法!');
		}
		set_time_limit(0);
		$ids      = Input::get('id');
		$weight   = Input::get('weight');
		$sub_type = Input::get('sub_type');
		$is_pass       = Input::get('is_pass');
		$is_new       = Input::get('is_new');
		$display_name  = Input::get('display_name');
		$display_color = Input::get('display_color');
		$display_color2 = Input::get('display_color2');
		$version       = Input::get('version');
		$large_pics    = Input::file('large_pic');

		$display_name_cn = Input::get('display_name_cn', []);
		$display_name_tw = Input::get('display_name_tw', []);
		$display_name_jp = Input::get('display_name_jp', []);
		$display_name_ru = Input::get('display_name_ru', []);
		$desc            = Input::get('desc', []);
		$desc_cn         = Input::get('desc_cn', []);
		$desc_tw         = Input::get('desc_tw', []);
		$desc_jp         = Input::get('desc_jp', []);
		$desc_ru         = Input::get('desc_ru', []);
		$is_vip          = Input::get('is_vip', []);

		$coin           = Input::get('coin', []);
		$level_20_coin  = Input::get('level_20_coin', []);
		$level_48_coin  = Input::get('level_48_coin', []);
		$level_108_coin = Input::get('level_108_coin', []);
		$level_288_coin = Input::get('level_288_coin', []);
		$level_432_coin = Input::get('level_432_coin', []);
		$level_704_coin = Input::get('level_704_coin', []);
		$level_24_coin  = Input::get('level_24_coin', []);
		$level_180_coin = Input::get('level_180_coin', []);
		$level_336_coin = Input::get('level_336_coin', []);
		$level_504_coin = Input::get('level_504_coin', []);
		$level_70_coin 	= Input::get('level_70_coin', []);


		foreach ($ids as $key => $id) {
			$model = Material::onWriteConnection()->find($id);
			if (!empty($large_pics[$key])) {
				$res                   = FileTool::upload($large_pics[$key], 'material', 'jpg', 1,$model->large_pic);
				$model->large_pic      = $res['path'];
				$model->large_pic_size = $res['size'];
				$model->width 			= $res['width'];
				$model->height 			= $res['height'];
				$model->en_material    = Material::getEncryptOneName($model->large_pic,$model->en_material);
//				$model->en_material1    = Material::getEncryptBySize($model->large_pic,$model->en_material,$res['width']*0.4 . 'x' . $res['height']*0.4,1);
//				$model->en_material2    = Material::getEncryptBySize($model->large_pic,$model->en_material,$res['width']*0.5 . 'x' . $res['height']*0.5,2);
//				$model->en_material6    = Material::getEncryptBySize($model->large_pic,$model->en_material,$res['width']*0.8 . 'x' . $res['height']*0.8,6);

			}
			if (isset($is_vip[$key])){
				$model->is_vip = $is_vip[$key];
			}
			$model->weight        = !isset($weight[$key])? $model->weight :$weight[$key];
			$model->display_name  = empty($display_name[$key])? $model->display_name :$display_name[$key];
			$model->display_color = empty($display_color[$key])? $model->display_color :$display_color[$key];
			$model->display_color2 = empty($display_color2[$key])? $model->display_color2 :$display_color2[$key];
			$model->is_pass       = !isset($is_pass[$key])? $model->is_pass :$is_pass[$key];
			$model->is_new       = !isset($is_new[$key])? $model->is_new :$is_new[$key];

			$model->display_name_cn = empty($display_name_cn[$key])? $model->display_name_cn :$display_name_cn[$key];
			$model->display_name_tw = empty($display_name_tw[$key])? $model->display_name_tw :$display_name_tw[$key];
			$model->display_name_jp = empty($display_name_jp[$key])? $model->display_name_jp :$display_name_jp[$key];
			$model->display_name_ru = empty($display_name_ru[$key])? $model->display_name_ru :$display_name_ru[$key];
			$model->desc            = empty($desc[$key])? $model->desc :$desc[$key];
			$model->desc_cn         = empty($desc_cn[$key])? $model->desc_cn :$desc_cn[$key];
			$model->desc_tw         = empty($desc_tw[$key])? $model->desc_tw :$desc_tw[$key];
			$model->desc_jp         = empty($desc_jp[$key])? $model->desc_jp :$desc_jp[$key];
			$model->desc_ru         = empty($desc_ru[$key])? $model->desc_ru :$desc_ru[$key];
			$model->coin            = !isset($coin[$key])? $model->coin :$coin[$key];
			$model->level_20_coin   = !isset($level_20_coin[$key])? $model->level_20_coin :$level_20_coin[$key];
			$model->level_48_coin   = !isset($level_48_coin[$key])? $model->level_48_coin :$level_48_coin[$key];
			$model->level_108_coin  = !isset($level_108_coin[$key])? $model->level_108_coin :$level_108_coin[$key];
			$model->level_432_coin  = !isset($level_432_coin[$key])? $model->level_432_coin :$level_432_coin[$key];
			$model->level_704_coin  = !isset($level_704_coin[$key])? $model->level_704_coin :$level_704_coin[$key];
			$model->level_24_coin   = !isset($level_24_coin[$key])? $model->level_24_coin :$level_24_coin[$key];
			$model->level_180_coin  = !isset($level_180_coin[$key])? $model->level_180_coin :$level_180_coin[$key];
			$model->level_336_coin  = !isset($level_336_coin[$key])? $model->level_336_coin :$level_336_coin[$key];
			$model->level_504_coin  = !isset($level_504_coin[$key])? $model->level_504_coin :$level_504_coin[$key];
			$model->level_70_coin  	= !isset($level_70_coin[$key])? $model->level_70_coin :$level_70_coin[$key];
			$model->level_432_coin  = !isset($level_432_coin[$key])? $model->level_432_coin :$level_432_coin[$key];

			$model->version  		= !isset($version[$key])? $model->version :$version[$key];

			$model->user = $username . '_' . date("Y-m-d H:i:s", time()) . '_BatchEdit';
			$model->save();
			if (!empty($large_pics[$key])) {
				Material::getSmallPic($model, 'small_pic', $model->width*0.4 . 'x' . $model->height*0.4);
				Material::getSmallPic($model, 'small_pic_new', $model->width*0.8 . 'x' . $model->height*0.8);
				Material::getSmallPic($model, 'icon', '150x150');
				Material::getSmallPic($model, 'banner_pic', '600x450');
//				FileTool::getImageUrlCnNew($model->en_material,1);
//				FileTool::getImageUrlCnNew($model->en_material1,1);
//				FileTool::getImageUrlCnNew($model->en_material2,1);
//				FileTool::getImageUrlCnNew($model->en_material6,1);
//				FileTool::getImageUrlCnNew($model->large_pic,1);
//				FileTool::getImageUrlCnNew($model->small_pic,1);
//				FileTool::getImageUrlCnNew($model->small_pic_new,1);
//				FileTool::getImageUrlCnNew($model->icon,1);
				RedisToCnMysql::addToQueue($model->id);
			}
			unset($model);
		}

		return redirect()->back()->with('status', '修改成功!');
	}


	//大文件上传
	public function bigFileUpload()
	{
		$callback          = $_GET['callback'];
		$files             = $_FILES['data'];
		$arr['i']          = $_POST['index'];
		$arr['shardCount'] = $_POST['total'];
		$arr['totalsize']  = $_POST['totalsize'];
		$arr['fileName']   = $_POST['name'];
		$ext               = explode('.', $arr['fileName']);
		if ($files['error'] == 0) {
			$tmpname         = $ext[0] . '_' . $arr['i'] . '.' . $ext[1];
			$arr['status']   = 200;
			$arr['fileUrl']  = "";
			$arr['fileSize'] = '';
			Storage::disk('local')->put('material/' . $tmpname, file_get_contents($files['tmp_name']));
			return $callback . '(' . json_encode($arr) . ')';
		} else {
			$arr['status'] == 502;
			return $callback . '(' . json_encode($arr) . ')';
		}

	}

	public function combineFile()
	{
		$callback = $_GET['callback'];
		$name     = Input::get('name');
		$count    = Input::get('count');
		$ext      = explode('.', $name);
		$arr      = [];
		for ($i = 2; $i <= $count; $i++) {
			$comname = $ext[0] . '_' . $i . '.' . $ext[1];
			file_put_contents(storage_path('app/material/' . $ext[0] . '_1.' . $ext[1]), file_get_contents(storage_path('app/material/' . $comname)), FILE_APPEND);
			Storage::disk('local')->delete('material/' . $comname);
		}
		rename(storage_path('app/material/' . $ext[0] . '_1.' . $ext[1]), storage_path('app/material/' . $name));
		//copy(storage_path('app/material/' . $name), $_SERVER['DOCUMENT_ROOT'] . '/uploadImg/material/' .  $name);
		Storage::put('material/' . $name, file_get_contents(storage_path('app/material/' . $name)));
		$arr['fileUrl']  = "material/$name";
		$arr['fileSize'] = filesize(storage_path('app/material/' . $name));
		// $cmd = 'cd ' . storage_path() . '/app/material/; rm -rf ./'. $name;
		// exec($cmd);

		return $callback . '(' . json_encode($arr) . ')';

	}


}
