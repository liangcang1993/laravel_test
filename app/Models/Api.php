<?php

namespace App\Models;

use App\Services\FileService;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Material;
use App\Models\Banner;

use FileTool;
use Storage;
use DB;
use Redis;
use Log;

class Api extends BaseModel
{
	use SoftDeletes;

	public static function getHomeListData($params)
	{
		if ($params['clt'] == 'ios') {
			if ($params['version']>= 20004){
				return self::getHomeListIosNew($params);
			}else{
				return self::getHomeListIos($params);
			}

		} else {
			return self::getHomeListAndroid($params);
		}
	}

	public static function getHomeListIosNew($params)
	{
		$datas = [];

		$datas['type'] = Type::getTypesIosNew($params);

		$datas['banner'] = [];
		$list            = Banner::getBannerList($params);
		foreach ($list as $key => &$value) {
			$sign = 0;
			if ($params['zone'] != 0) {
				$add = substr($params['zone'], 0, 1);
				if ($add == '-') {
					$sign = 1;
				}
				$params['zone'] = substr($params['zone'], 1);
			}
			$reqTime = gmdate('Y-m-d', time() + $params['zone'] * 3600);
			if ($sign == 1) {
				$reqTime = gmdate('Y-m-d', time() - $params['zone'] * 3600);
			}
			$time = gmdate('Y-m-d', strtotime($value['date']));
			if ($time > $reqTime) {
				continue;
			}

			$params['id']     = $value['ios_mid'];
			$data['date']          = $value['date'];
			$data['banner']        = Material::getMaterials($params);
			$datas['banner'][]     = $data['banner'];

		}

		return $datas;

	}
	public static function getHomeListIos($params)
	{
		$datas = [];

		$datas['type'] = Type::getTypesIos($params);
		//whatsnew栏目 规则：puzzle中所有二级类按is_new(order)字段desc排序  分类是否有new角标按照拥有的素材是否是is_new

		$datas['banner'] = [];
		$list            = Banner::getBannerList($params);
		foreach ($list as $key => &$value) {
			$sign = 0;
			if ($params['zone'] != 0) {
				$add = substr($params['zone'], 0, 1);
				if ($add == '-') {
					$sign = 1;
				}
				$params['zone'] = substr($params['zone'], 1);
			}
			$reqTime = gmdate('Y-m-d', time() + $params['zone'] * 3600);
			if ($sign == 1) {
				$reqTime = gmdate('Y-m-d', time() - $params['zone'] * 3600);
			}
			$time = gmdate('Y-m-d', strtotime($value['date']));
			if ($time > $reqTime) {
				continue;
			}

			$params['id']     = $value['ios_mid'];
			$data['date']          = $value['date'];
			$data['banner']        = Material::getMaterials($params);
			$datas['banner'][]     = $data['banner'];

		}

		return $datas;

	}


	//color2 是新版Android banner的背景色
	public static function getHomeListAndroid($params)
	{
//		$isToday = self::get_time($_SERVER['REQUEST_TIME']);
		$datas = [];
		$datas['type'] = Type::getTypesAndroid($params);
		$datas['banner'] = [];
//		$date = date('Y-m-d',time());
//		Redis::del('AndroidBannerInfo');
		if (!Redis::exists('AndroidBannerInfo'))
		{
			if (20000000>$params['version'])
			{
				$materials = Material::where(['device'=>1,'is_vip'=>0,'is_pass'=>1])->orderby('weight','desc')->get();
			}else{
				$materials = Material::where(['device'=>1,'is_vip'=>0])->orderby('weight','desc')->get();
			}
			foreach ($materials as $v)
			{
				if (Redis::hexists('AndroidBanner',$v->id)){continue;}
				$params['id'] = $v->id;
				$materialData = Material::getMaterialById($params);
//				Log::info(json_encode($materialData));
				$materialDataType = Type::getTypeByName($materialData['subType']);
//				Log::info('$materialDataType\n');
//				Log::info(json_encode($materialDataType));
				$materialData['color2'] = $materialDataType['type_color2'];
//				Log::info('final material\n');
//				Log::info(json_encode($materialData));
				Redis::hset('AndroidBanner',$v->id,json_encode($materialData));
				Redis::set('AndroidBannerInfo',json_encode($materialData));
				break;
			}
		}
		$datas['banner'][] = json_decode(Redis::get('AndroidBannerInfo'),true);

//		$list            = Banner::getBannerList($params);
//		foreach ($list as $key => &$value) {
//			$sign = 0;
//			if ($params['zone'] != 0) {
//				$add = substr($params['zone'], 0, 1);
//				if ($add == '-') {
//					$sign = 1;
//				}
//				$params['zone'] = substr($params['zone'], 1);
//			}
//			$reqTime = gmdate('Y-m-d', time() + $params['zone'] * 3600);
//			if ($sign == 1) {
//				$reqTime = gmdate('Y-m-d', time() - $params['zone'] * 3600);
//			}
//			$time = gmdate('Y-m-d', strtotime($value['date']));
//			if ($time > $reqTime) {
//				continue;
//			}
//
//			$params['android_mid'] = $value['android_mid'];
//			$params['ios_mid']     = $value['ios_mid'];
//			$data['date']          = $value['date'];
//			$data['banner']        = Material::getJigBannerNew($params);
//			$datas['banner'][]     = $data['banner'];
//			$params['android_mid'] = 0;
//			$params['ios_mid']     = 0;
//
//		}

		return $datas;

	}

	public static function getConfig($params)
	{
		$dev = 0;
		if (isset($params['clt']) && $params['clt'] == 'android') {
			$dev = 1;
		}
		$cs   = [];
		$list = Config::where('device', $dev)->get();
		foreach ($list as $l) {
			$c['key']   = $l->key;
			$c['value'] = $l->value;
			$c['desc']  = $l->desc;
			$cs[]       = $c;
		}
		return $cs;
	}


}
