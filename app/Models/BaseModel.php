<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Redis;
use FileTool;

class BaseModel extends Model
{

    // protected $connection='games';
    public static function redisList($key, $params){
    	if(!array_key_exists('page', $params) || empty($params['page'])){
    		$params['page'] = 1;
    	}
    	if(!array_key_exists('page_size', $params) || empty($params['page_size'])){
    		$params['page_size'] = 15;
    	}

    	$start = ($params['page']-1) * $params['page_size'];
        $stop = $start + $params['page_size']-1;
        $list = Redis::zrevrange($key, $start , $stop);
        if (array_key_exists('info', $params)){
        	$data = [];
	        foreach ($list as $l) {
                $info = $params['info']($l);
                !empty($info) &&  $data[] = $info;
	           
	        }
        }else{
        	$data = $list;
        }
       
        $d['list'] = $data;
        $d['lastPage'] = count($data) == $params['page_size'] ? 0 : 1; 
        return $d;
    }

    public static function cleanCache()
    {
        $keys = Redis::keys('api/*');
        foreach ($keys as  $value) 
        {
            Redis::del($value);
        }
    }

    public static function getRealUrl($url, $china = null)
    {
        if(starts_with($url, './')){
            return Config::get('base.url') .'storeFile/' . substr($url, 2);
        }else{
            return FileTool::getUrl($url, $china);
        }
    }

	/**
	 * 获取已经过了多久
	 * PHP时间转换
	 * 刚刚、几分钟前、几小时前
	 * 今天昨天前天几天前
	 * @param  string $targetTime 时间戳
	 * @return string
	 */


	public static function get_time($targetTime)
	{
		// 今天最大时间
		$todayLast = strtotime(date('Y-m-d 23:59:59'));
		$agoTimeTrue = time() - $targetTime;
		$agoTime	= $todayLast - $targetTime;
		$agoDay		= floor($agoTime / 86400);

		$res = 0;
		if ($agoDay == 0) {
			$res=1;
		}
//		if ($agoTimeTrue < 60) {
//				$result = '刚刚';
//		} elseif ($agoTimeTrue < 3600) {
//				$result = (ceil($agoTimeTrue / 60)) . '分钟前';
//		} elseif ($agoTimeTrue < 3600 * 12) {
//				$result = (ceil($agoTimeTrue / 3600)) . '小时前';
//		} elseif ($agoDay == 0) {
//				$result = '今天 ' . date('H:i', $targetTime);
//		} elseif ($agoDay == 1) {
//				$result = '昨天 ' . date('H:i', $targetTime);
//		} elseif ($agoDay == 2) {
//				$result = '前天 ' . date('H:i', $targetTime);
//		} elseif ($agoDay > 2 && $agoDay < 16) {
//				$result = $agoDay . '天前 ' . date('H:i', $targetTime);
//		} else {
//				$format = date('Y') != date('Y', $targetTime) ? "Y-m-d H:i" : "m-d H:i";
//				$result = date($format, $targetTime);
//		}
		return $res;
	}


}
