<?php

namespace App\Models;

use Redis;
use DB;
use PDO;

class LoadState extends BaseModel
{
    protected $table = 'load_state';
    public $timestamps = false;

	public static function getPageQuery($filter = array())
	{
		$query = self::onWriteConnection()->selectRaw('*');
		if (isset($filter['id']) && trim($filter['id']) != '')
		{
			$query->where('material_id',$filter['id']);
		}
		if (isset($filter['status']) && $filter['status'] !== '')
		{
			$query->where('status',$filter['status']);
		}
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
		$pageNum = isset($filter['pageNum']) ? $filter['pageNum']: 100;
		return $query->paginate($pageNum);
	}
}
