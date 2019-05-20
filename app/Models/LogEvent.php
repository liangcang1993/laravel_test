<?php

namespace App\Models;

use Redis;
use DB;
use PDO;

class LogEvent extends BaseModel
{
    protected $table = 'log_event';
    public $timestamps = false;

	public static function getPageQuery($filter = array())
	{
//		$three = date('Y-m-d H:i:s',time()-90*60*60*24);
		$query = self::onWriteConnection()->select(['id','created_at','sid','event','time','material_id']);
		$query->where('created_at','>=',$filter['start_date']);
		$query->where('created_at','<=',$filter['end_date']);
		$query->where('event','request');
		if (isset($filter['id']) && trim($filter['id']) != '')
		{
			$query->where('material_id',$filter['id']);
		}
//		if (isset($filter['status']) && $filter['status'] !== '')
//		{
//			$status = 'success';
//			$filter['status'] == 'success' && $status = 'fail';
//			$query->where('event','!=',$status);
//		}
		if(isset($filter['sort']) && trim($filter['sort']) != '')
		{
			if(starts_with($filter['sort'], 'time') || starts_with($filter['sort'], 'status'))
			{
				$filter['sort'] = $filter['sort'];
			}
			$d = explode(' ', $filter['sort']);
			$query->orderBy($d[0], $d[1]);
		}else{
			$query->orderBy('id', 'desc');
		}
		return $query->get();
	}
}
