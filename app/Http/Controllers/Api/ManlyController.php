<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;

use App\Models\Config;
use App\Models\Material;
use App\Models\Api;
use App\Models\MaterialUsed;
use App\Models\User;
use App\Models\ManlyPayAction;
use App\Models\Banner;

use App\Models\RedisKey;
use App\Models\RedisToMysql;
use App\Models\AdCollect;
use App\Models\Type;
use App\Models\LoadState;
use App\Models\LogEvent;


use Input;
use DB;
use Illuminate\Http\Request;
use Redis;
use Log;

class  ManlyController extends ApiBaseController
{

	public function getMaterials(Request $request)
	{
		$t1 = microtime(true);
		$key                 = Input::path() . ':' . Input::getQueryString();
		$params['page_size'] = Input::get('pageSize', 20);
		$params['page']      = Input::get('page', 1);
		$params['type']      = Input::get('type', '');
		$params['sub_type']  = Input::get('subtype', '');
		$params['types']     = Input::get('types', '');
		$params['china']     = self::isChina($request);
		$params['asia']      = self::isAsia($request);
		$key                 .= $params['china'];
		$params['version']   = $request->headers->get('version', Input::get('version', 0));
		!is_numeric($params['version']) && $params['version'] = 0;
		$key .= $params['version'];

		$clt = Input::cookie('clt_id', Input::get('clt_id', ''));
		if (strlen($params['version']) == 8) {
			$params['clt'] = 'android';
		} else {
			$params['clt'] = 'ios';
		}
		$key      .= $params['clt'];
		$language = $request->headers->get('Accept-Language');
		if (strstr($language, 'zh-Hans')) {
			$language = 'cn';
		} elseif (strstr($language, 'zh-Hant')) {
			$language = 'tw';
		} elseif (strstr($language, 'ru')) {
			$language = 'ru';
		} elseif (strstr($language, 'ja')) {
			$language = 'jp';
		} else {
			$language = 'en';
		}
		$key                .= $language;
		$params['language'] = $language;

		$params['country'] = Input::get('country', $request->headers->get('country'));
		empty($params['country']) && $params['country'] = 'US';
		$params['country'] = strtoupper($params['country']);
		$key               .= $params['country'];
		// Log::info($parmas['country']);
		if (!Redis::exists($key)) {
			$data = Material::getMaterials($params);
			$t2 = microtime(true);
			$t3 = round($t2-$t1,3);
			if($t3 >= 2)
			{
				Log::info('getMaterials =='.$params['sub_type'].'==total 耗时  :  '.round($t2-$t1,3).'秒');
			}
			$res  = ['code' => 1, 'message' => 'success', 'data' => $data];
			Redis::set($key, json_encode($res));
		}
		$res = Redis::get($key);
		return response($res)->header('Content-Type', 'application/json');
	}

	public function getHomeList(Request $request)
	{
		$key                 = Input::path() . ':' . Input::getQueryString();
		$params['page_size'] = Input::get('pageSize', 15);
		$params['page']      = Input::get('page', 1);
		$params['version']   = Input::get('version', $request->headers->get('version'));
		!is_numeric($params['version']) && $params['version'] = 0;
		$key             .= $params['version'];
		$params['china'] = self::isChina($request);
		$params['asia']  = self::isAsia($request);
		$params['zone']  = 0;
		$zone            = $request->headers->get('zone');
		if (!is_null($zone)) {
			$num = substr($zone, 3);
			empty($num) ? $num = 0 : $num = $num;
			$params['zone'] = $num;
		}
		$key .= $params['china'];
		$key .= $params['version'];
		$clt = Input::cookie('clt_id', Input::get('clt_id', ''));
		if (strlen($params['version']) == 8) {
			$params['clt'] = 'android';
		} else {
			$params['clt'] = 'ios';
		}
		$key      .= $params['clt'];
		$language = $request->headers->get('Accept-Language');
		if (starts_with($language, 'zh-CN') || starts_with($language, 'zh-cn') || strstr($language, 'zh-Hans')) {
			$language = 'cn';
		} elseif (starts_with($language, 'zh-TW') || strstr($language, 'zh-Hant')) {
			$language = 'tw';
		} elseif (starts_with($language, 'zh-tw')) {
			$language = 'tw';
		} elseif (starts_with($language, 'ru') || strstr($language, 'ru')) {
			$language = 'ru';
		} elseif (starts_with($language, 'ja-JP') || strstr($language, 'ja')) {
			$language = 'jp';
		} elseif (starts_with($language, 'ja-jp')) {
			$language = 'jp';
		} elseif (starts_with($language, 'es') || strstr($language, 'es')) {
			$language = 'es';
		} else {
			$language = 'en';
		}
		if (!empty(Input::get('language'))) {
			$language = Input::get('language');
		}
		$key                .= $language;
		$params['language'] = $language;

		$params['country'] = Input::get('country', $request->headers->get('country'));

		if (!Redis::exists($key)) {
			$data = Api::getHomeListData($params);
			$res  = ['code' => 1, 'message' => 'success', 'data' => $data];
			Redis::set($key, json_encode($res));
		}
		$res = Redis::get($key);
		return response($res)->header('Content-Type', 'application/json');
	}


	public function getAllMaterials(Request $request)						//hot  is_new=2
	{
		$key                 = Input::path() . ':' . Input::getQueryString();
		$params['page_size'] = Input::get('pageSize', 20);
		$params['page']      = Input::get('page', 1);
		$params['type']      = Input::get('type', '');
		$params['sub_type']      = Input::get('sub_type', '');
		$params['type'] === 'all' && $params['type'] = '';
		$params['sub_type'] === 'all' && $params['sub_type'] = '';
		$params['types']     = Input::get('types', '');
		$params['china']     = self::isChina($request);
		$params['asia']      = self::isAsia($request);
		$key                 .= $params['china'];
		$params['version']   = $request->headers->get('version', Input::get('version', 0));
		!is_numeric($params['version']) && $params['version'] = 0;
		$key .= $params['version'];

		$clt = Input::cookie('clt_id', Input::get('clt_id', ''));
		if (strlen($params['version']) == 8) {
			$params['clt'] = 'android';
		} else {
			$params['clt'] = 'ios';
		}
		$key      .= $params['clt'];
		$language = $request->headers->get('Accept-Language');
		if (strstr($language, 'zh-Hans')) {
			$language = 'cn';
		} elseif (strstr($language, 'zh-Hant')) {
			$language = 'tw';
		} elseif (strstr($language, 'ru')) {
			$language = 'ru';
		} elseif (strstr($language, 'ja')) {
			$language = 'jp';
		} else {
			$language = 'en';
		}
		$key                .= $language;
		$params['language'] = $language;

		$params['country'] = Input::get('country', $request->headers->get('country'));
		empty($params['country']) && $params['country'] = 'US';
		$params['country'] = strtoupper($params['country']);
		$key               .= $params['country'];
		// Log::info($parmas['country']);
		if (!Redis::exists($key)) {
			$data = Material::getMaterials($params,null,1);
			$res  = ['code' => 1, 'message' => 'success', 'data' => $data];
			Redis::set($key, json_encode($res));
		}
		$res = Redis::get($key);
		return response($res)->header('Content-Type', 'application/json');
	}

	public function getMaterialById(Request $request)
	{
		$params['id']   	= Input::get('id', $request->headers->get('id'));
		$params['china']     = self::isChina($request);
		$params['asia']      = self::isAsia($request);
		$language = $request->headers->get('Accept-Language');
		is_null($params['id']) && $params['id'] = 0;
		if (strstr($language, 'zh-Hans')) {
			$language = 'cn';
		} elseif (strstr($language, 'zh-Hant')) {
			$language = 'tw';
		} elseif (strstr($language, 'ru')) {
			$language = 'ru';
		} elseif (strstr($language, 'ja')) {
			$language = 'jp';
		} else {
			$language = 'en';
		}
		$params['language'] = $language;
		$params['country'] = Input::get('country', $request->headers->get('country'));
		$data = Material::getMaterials($params);
		$res  = ['code' => 1, 'message' => 'success', 'data' => $data];
		return response($res)->header('Content-Type', 'application/json');
	}
	public function getBanner(Request $request)
	{
		$key                 = Input::path() . ':' . Input::getQueryString();
		$params['page_size'] = Input::get('pageSize', 15);
		$params['page']      = Input::get('page', 1);
		$params['type']      = Input::get('type', '');
		$params['china']     = self::isChina($request);
		$params['asia']      = self::isAsia($request);
		$params['app']       = $this->getApp($request);
		$params['sex']       = Input::get('sex', 1);
		$key                 .= $params['sex'];
		$key                 .= $params['china'];
		$params['version']   = Input::get('version', $request->headers->get('version'));
		!is_numeric($params['version']) && $params['version'] = 0;
		$key      .= $params['version'];
		$language = $request->headers->get('Accept-Language');
		if (starts_with($language, 'zh-CN')) {
			$language = 'cn';
		} elseif (starts_with($language, 'zh-cn')) {
			$language = 'cn';
		} elseif (starts_with($language, 'zh-TW')) {
			$language = 'tw';
		} elseif (starts_with($language, 'zh-tw')) {
			$language = 'tw';
		} elseif (starts_with($language, 'ru')) {
			$language = 'ru';
		} elseif (starts_with($language, 'ja-JP')) {
			$language = 'jp';
		} elseif (starts_with($language, 'ja-jp')) {
			$language = 'jp';
		} elseif (starts_with($language, 'es')) {
			$language = 'es';
		} else {
			$language = 'en';
		}
		$key                .= $language;
		$params['language'] = $language;
		$key                .= $params['app'];


		if (!Redis::exists($key)) {
			// $material_id = Input::get('id');
			$data = Banner::getBanner($params);
			$res  = ['code' => 1, 'message' => 'success', 'data' => $data];
			Redis::set($key, json_encode($res));
		}
		$res = Redis::get($key);
		return response($res)->header('Content-Type', 'application/json');
	}

	public function materialUsed(Request $request)
	{
		$id = Input::get('id', '');
		if (empty($id)) {
			$ids = Input::get('ids');
			$ids = explode('_', $ids);
			foreach ($ids as $id) {
				$this->materialUsedById($id, $request);
			}
		} else {
			$this->materialUsedById($id, $request);
		}
		$res = ['code' => 1, 'message' => 'success', 'data' => ''];
		return response()->json($res);
	}

	private function materialUsedById($id, $request)
	{
		$name   = Input::get('name', '');
		$type   = Input::get('type');
		$status = Input::get('status', 1);       //puzzle  1未拼完。0拼完
		$level  = Input::get('level', 0);           //puzzle  难度等级
		$clt    = Input::cookie('clt_id', Input::get('clt_id', ''));
		$china  = self::isChina($request);
		$type   = $status;

		if (!empty($id)) {
			//存入拓展表manly_material_used
			$m2              = new MaterialUsed;
			$m2->material_id = $id;
			$m2->clt         = $clt;
			// type 0 下载 1 使用
			$m2->type          = isset($type) ? $type : 0;
			$m2->app           = 'jigsaw';
			$m2->uuid          = User::headerDecrypt($request->headers->get('udid',''));
			$m2->idfa          = User::headerDecrypt($request->headers->get('idfa',''));
			$m2->puzzle_status = $status;
			$m2->puzzle_level  = $level;
			$m2->created_at    = date('Y-m-d H:i:s', time());
			RedisToMysql::addToQueue($m2);
		}
	}

	public function AdCollect(Request $request)
	{
		$content 	= Input::get('content', '');
		$event 		= Input::get('event', '');
		$contentArr = json_decode($content,true);

		//存入表  ad_collect
		$model = new AdCollect;
		$model->content 	= $content;
		$model->position 	= isset($contentArr['position']) ? $contentArr['position'] : '';
		$model->adid 		= isset($contentArr['ADID']) ? $contentArr['ADID'] : '';
		$model->reward 		= isset($contentArr['reward']) ? $contentArr['reward'] : '';
		$model->event 		= $event;
		$model->udid        = User::headerDecrypt($request->headers->get('udid',''));
		$model->idfa        = User::headerDecrypt($request->headers->get('idfa',''));
		$model->created_at  = date('Y-m-d H:i:s', time());
		$model->is_android = 0;
		if (isset($contentArr['device']) && $contentArr['device'] == 'android'){$model->is_android = 1;}
		unset($contentArr);
		RedisToMysql::addToQueue($model);
		$res = ['code' => 1, 'message' => 'success', 'data' => ''];
		return response()->json($res);
	}

	public function loadStateReport(Request $request)					//弃用
	{
		$version   = Input::get('version', $request->headers->get('version'));
		$clt = 'ios';
		strlen($version) == 8 && $clt = 'android';
		$material_id 	= Input::get('material_id', 0);
		$time 			= Input::get('time', 0);
		$status 		= Input::get('status', 0);
		$json 			= Input::get('json', '');
		$jsonInfo 		= json_decode($json,1);
		$time 			= isset($jsonInfo['time']) ? $jsonInfo['time'] :$time;
		$material_id 	= isset($jsonInfo['material_id']) ? $jsonInfo['material_id'] :$material_id;
		$status 		= isset($jsonInfo['status']) ? $jsonInfo['status'] :$status;		//0 失败  1成功


		//存入表
		$model = new LoadState;
		$model->time 			= $time;
		$model->material_id 	= $material_id;
		$model->app         	= 'Jigsaws';
		$model->clt         	= $clt;
		$model->status 			= $status;
		$model->json 			= $json;
		$model->udid        	= User::headerDecrypt($request->headers->get('udid',''));
		$model->idfa        	= User::headerDecrypt($request->headers->get('idfa',''));
		$model->created_at  	= date('Y-m-d H:i:s', time());
		RedisToMysql::addToQueue($model);
		$res = ['code' => 1, 'message' => 'success', 'data' => ''];
		return response()->json($res);
	}

	public function logEvent(Request $request)
	{
		$version   = Input::get('version', $request->headers->get('version'));
		$clt = 'ios';
		$first = 2;					//老版本无first字段
		strlen($version) == 8 && $clt = 'android';
		$event 	= Input::get('event', '');					//request  success fail
		$json 			= Input::get('json', '');
		$jsonInfo 		= json_decode($json,1);
		$time 			= isset($jsonInfo['time']) ? $jsonInfo['time'] :0;
		$sid 			= isset($jsonInfo['sid']) ? $jsonInfo['sid'] :'';
		$material_id 	= isset($jsonInfo['material_id']) ? $jsonInfo['material_id'] :0;
		$country 		= isset($jsonInfo['country']) ? $jsonInfo['country'] :'';
		$fromLevel 		= isset($jsonInfo['fromLevel']) ? $jsonInfo['fromLevel'] :'';
		$networkType 	= isset($jsonInfo['networkType']) ? $jsonInfo['networkType'] :'';
		$size 			= isset($jsonInfo['size']) ? $jsonInfo['size'] :0;
		$width 			= isset($jsonInfo['width']) ? $jsonInfo['width'] :0;
		$height 		= isset($jsonInfo['height']) ? $jsonInfo['height'] :0;
		if (isset($jsonInfo['first']))
		{
			$first = 0;
			$jsonInfo['first'] === true && $first=1;
		}
		$headers 		= json_encode($request->header());
		$headerArr 		= json_decode($headers,1);
		if ($clt === 'android' && empty($country))
		{
			$c = explode('; ',$headerArr['cookie'][0]);
			foreach ($c as $cc)
			{
				if (substr($cc,0,strpos($cc, '=')) === 'country')
				{
					$country = trim(strrchr($cc, '='),'=');
				}
			}
		}
		unset($headerArr);


		//存入表
		$model = new LogEvent;
		$model->time 			= $time;
		$model->material_id 	= $material_id;
		$model->app         	= 'Jigsaws';
		$model->clt         	= $clt;
		$model->event 			= $event;
		$model->sid 			= $sid;
		$model->json 			= $json;
		$model->headers 		= $headers;
		$model->first 			= $first;
		$model->country 		= $country;
		$model->fromLevel 		= $fromLevel;
		$model->networkType 	= $networkType;
		$model->size 			= $size;
		$model->width 			= $width;
		$model->height 			= $height;
		$model->version 		= is_null($version) ? '':$version;
		$model->udid        	= User::headerDecrypt($request->headers->get('udid',''));
		$model->idfa        	= User::headerDecrypt($request->headers->get('idfa',''));
		$model->created_at  	= date('Y-m-d H:i:s', time());
//		$model->save();
		RedisToMysql::addToQueue($model);
		$res = ['code' => 1, 'message' => 'success', 'data' => ''];
		return response()->json($res);
	}

	public function getConfig(Request $request)
	{
		$params            = [];
		$key               = Input::path() . ':' . Input::getQueryString();
		$params['version'] = Input::get('version', $request->headers->get('version'));
		!is_numeric($params['version']) && $params['version'] = 0;
		$key .= $params['version'];
		if (strlen($params['version']) == 8) {
			$params['clt'] = 'android';
		} else {
			$params['clt'] = 'ios';
		}
		if (!Redis::exists($key)) {
			$data = Api::getConfig($params);

			$res = ['code' => 1, 'message' => 'success', 'data' => $data];
			Redis::set($key, json_encode($res));
			// Redis::expire($key, RedisKey::EXPIRE_TIME);
		}
		$res = Redis::get($key);

		return response($res)->header('Content-Type', 'application/json');
	}


}
