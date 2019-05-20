<?php

namespace App\Models;

use DB;
use Redis;

class UserPushStatistic extends BaseModel
{
    protected $table = 'user_push_statistic';

    public static function getPageQuery($filter = array())
    {
        $query = UserStatistic::selectRaw('*');

        if (isset($filter['keyword']) && trim($filter['keyword']) != '') {
            $query->where('sid', '=', $filter['keyword']);
        }
        if (isset($filter['sort']) && trim($filter['sort']) != '') {
            $d = explode(' ', $filter['sort']);
            $query->orderBy($d[0], $d[1]);
        }else{
            $query->orderBy('id', 'desc');
        }
        return $query->paginate(10);
    }
}
