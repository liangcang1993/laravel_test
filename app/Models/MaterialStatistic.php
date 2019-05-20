<?php

namespace App\Models;

use \App\Models\RedisKey;

use Redis;

class MaterialStatistic extends BaseModel
{
	protected $table = 'material_statistic';

	public function material()
	{
		return $this->belongsTo('App\Models\Material', 'material_id');
	}

	public static function getPageQuery($filter = array())
	{
		$query = self::selectRaw('*');

		if (isset($filter['id']) && trim($filter['id']) != '') {
			$query->where('material_id', '=', $filter['id']);
		}

		if (isset($filter['sort']) && trim($filter['sort']) != '') {
			$d = explode(' ', $filter['sort']);
			$query->orderBy($d[0], $d[1]);
		} else {
			$query->orderBy('date', 'desc')->orderBy('id', 'desc');
		}
		// $query->orderBy('id', 'desc');
		return $query->paginate(30);
	}

	public static function run($date)
	{
		$list = Material::all();
		foreach ($list as $key => $l) {
			$model = MaterialStatistic::where('date', $date)->where('material_id', $l->id)->first();
			if (!$model) {
				$model       = new MaterialStatistic;
				$model->date = $date;
				$model->save();
			}
			$model->used_num     = self::statistic($date, $l->id);
//			$model->download_num = self::statistic(0, $date, $l->id);
			$model->material_id  = $l->id;
			$model->save();
		}
	}

	public static function statistic($date, $material_id)
	{
		$start_at = date('Y-m-d H:i:s', strtotime($date));
		$end_at   = date('Y-m-d H:i:s', strtotime("+1 days", strtotime($date)));
		//初始化开始时间

		$query = MaterialUsed::where('created_at', '>', $start_at)->where('created_at', '<', $end_at)->where('material_id', $material_id);
		$num   = $query->count();
		return $num;
	}

	public function picUrl(){
		$m = Material::find($this->material_id);
		if($m){
			if(!empty($m->icon)){
				return self::getRealUrl($m->icon);
			}else{
				return '';
			}

		}
	}
}
