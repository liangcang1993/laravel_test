<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Services\CSV;
use App\Models\LogEvent;
use App\Models\Material;


use Validator;
use Input;
use FileTool;
use Storage;
use Redis;

class StatisticController extends AdminBaseController
{
	protected $modelName = 'statistic';

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		set_time_limit(0);
		$startTime = date('Y-m-d',strtotime("-1 month"));
		$endTime = date('Y-m-d',time());
		$filter = [
			'id' 			=> Input::get('id'),
			'status' 		=> Input::get('status',''),
			'start_date' 	=> Input::get('start_date',$startTime),
			'end_date' 		=> Input::get('end_date',$endTime),
			'sort' 			=> Input::get('sort')
		];
		$list   = LogEvent::getPageQuery($filter);
		$data = [];
		foreach ($list as $k=>&$l)
		{
			$sinfo = LogEvent::where('sid',$l->sid)->where('event','!=','request')->first();
			$sinfo = json_decode(json_encode($sinfo), true);
			$linfo = json_decode(json_encode($l), true);
			$data[$k]['id'] = $linfo['id'];
			$data[$k]['created_at'] = $linfo['created_at'];
			$data[$k]['material_id'] = $linfo['material_id'];
			if (is_null($sinfo))
			{
				$data[$k]['loadtime'] = 0;
				$data[$k]['loadevent'] = 'no success or fail';
			}else{
				$data[$k]['loadtime'] = $sinfo['time'];
				$data[$k]['loadevent'] = $sinfo['event'];
				if ($filter['status'] === 'success' || $filter['status'] === 'fail')
				{
					$status = 'success';
					$filter['status'] === 'success' && $status = 'fail';
					if($sinfo['event'] === $status){unset($data[$k]);}

				}

			}
			unset($sinfo);
			unset($linfo);
		}
		if (isset($filter['sort']) && $filter['sort'] != '')
		{
			array_multisort(array_column($data,'loadtime'),SORT_DESC,$data);
		}
		$download = Input::get('download',0);
		if ($download == 1)
		{
			$title = ['id',iconv('UTF-8', 'GBK', '日期'),iconv('UTF-8', 'GBK', '素材ID'),iconv('UTF-8', 'GBK', '加载时长'),iconv('UTF-8', 'GBK', '状态')];
			array_unshift($data,$title);
			$fileName = '加载上报_'.$filter['start_date'].'_'.$filter['end_date'];
			CSV::init($fileName);
			foreach ($data as $key => $value) {
				CSV::exportData($value);
			}
			CSV::del();
			exit;
		}
		return $this->render(
			'admin.' . $this->modelName . '_index',
			[
				'list' => $data,
				'filter' => $filter,
				'modelName' => $this->modelName,
			]
		);

	}

	public function downLoad()
	{
//		$list = LogEvent::select('material_id')->where(['first'=>1,'clt'=>'ios','version'=>20101])->where('created_at','>=','2019-05-10 10:00:00')->where('created_at','<=','2019-05-12 21:00:00')->where('material_id','>',0)->get();
////		$list = LogEvent::where('material_id','>',0)->get();
//		$data = [];
//		foreach ($list as $l)
//		{
//			$a = Material::select('id','large_pic_size')->where('id',$l->material_id)->first();
//			$res['id'] = $a->id;
//			$res['size'] = $a->large_pic_size;
//			$data[] = $res;
//		}
////		$Data = json_decode(Redis::get('mids'),1);
//		$title = ['id','lsize'];
//		array_unshift($data,$title);
//		$fileName = 'size上报';
//		CSV::init($fileName);
//		foreach ($data as $key => $value) {
//			CSV::exportData($value);
//		}
//		CSV::del();
//
//		exit;
		
		$download = Input::get('download','');
		if ($download == 'ios')
		{
			$Data = json_decode(Redis::get('iosData'),1);
			$title = ['udid','request','success','country'];
			array_unshift($Data,$title);
			$fileName = 'udid_new_ios上报';
			CSV::init($fileName);
			foreach ($Data as $key => $value) {
				CSV::exportData($value);
			}
			CSV::del();

			exit;
		}
		if ($download == 'android')
		{
			$Data = json_decode(Redis::get('andData'),1);
			$title = ['udid','request','success','country'];
			array_unshift($Data,$title);
			$fileName = 'udid_new_android上报';
			CSV::init($fileName);
			foreach ($Data as $key => $value) {
				CSV::exportData($value);
			}
			CSV::del();

			exit;
		}
//		if ($download == 'android2')
//		{
//			$Data = json_decode(Redis::get('deviceDataand'),1);
//			$title = ['udid','request','success'];
//			array_unshift($Data,$title);
//			$fileName = 'udid_fix_android2上报';
//			CSV::init($fileName);
//			foreach ($Data as $key => $value) {
//				if (strlen($value['udid']) <= 20){continue;}
//				CSV::exportData($value);
//			}
//			CSV::del();
//
//			exit;
//		}
//		if ($download == 'mids')
//		{
//			$Data = json_decode(Redis::get('mids'),1);
//			$title = ['lsize'];
//			array_unshift($Data,$title);
//			$fileName = 'size上报';
//			CSV::init($fileName);
//			foreach ($Data as $key => $value) {
//				CSV::exportData($value);
//			}
//			CSV::del();
//
//			exit;
//		}
	}


}
