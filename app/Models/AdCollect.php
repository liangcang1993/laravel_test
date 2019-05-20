<?php

namespace App\Models;

use Redis;
use DB;
use PDO;

class AdCollect extends BaseModel
{
    protected $table = 'ad_collect';
    public $timestamps = false;

    public static function AdShowList(){
		$start = date('Y-m-d', strtotime("-3 month"));
		if (!Redis::exists('adshowlist')) {
			DB::setFetchMode(PDO::FETCH_ASSOC);
			$rewardList = DB::select("select count(position) as nums ,position ,DATE_FORMAT(created_at,'%Y-%m-%d') as date from `ad_collect` where reward = 1 and is_android=0 and created_at > ".$start." group by DATE_FORMAT(created_at,'%Y-%m-%d'),position order by created_at desc ;");
			$seeList = DB::select("select count(position) as nums ,position ,DATE_FORMAT(created_at,'%Y-%m-%d') as date from `ad_collect` where reward != 1 and is_android=0 and created_at > ".$start." group by DATE_FORMAT(created_at,'%Y-%m-%d'),position order by created_at desc ;");
			$data = [];
			$redata = [];
			foreach ($rewardList as $re){
				$redata[$re['date']][$re['position']] = $re;
			}
			foreach ($seeList as $see){
				$seeInfo = [];
				$seeInfo['position'] = $see['position'];
				$seeInfo['see'] = $see['nums'];
				$seeInfo['reward'] = isset($redata[$see['date']][$see['position']]['nums']) ?  $redata[$see['date']][$see['position']]['nums']:0;
				$data[$see['date']][$see['position']] = $seeInfo;
			}
			Redis::hset('adshowlist','ios', json_encode($data));

			$rewardList = DB::select("select count(position) as nums ,position ,DATE_FORMAT(created_at,'%Y-%m-%d') as date from `ad_collect` where reward = 1 and is_android=1 and created_at > ".$start." group by DATE_FORMAT(created_at,'%Y-%m-%d'),position order by created_at desc ;");
			$seeList = DB::select("select count(position) as nums ,position ,DATE_FORMAT(created_at,'%Y-%m-%d') as date from `ad_collect` where reward != 1 and is_android=1 and created_at > ".$start." group by DATE_FORMAT(created_at,'%Y-%m-%d'),position order by created_at desc ;");
			$data = [];
			$redata = [];
			foreach ($rewardList as $re){
				$redata[$re['date']][$re['position']] = $re;
			}
			foreach ($seeList as $see){
				$seeInfo = [];
				$seeInfo['position'] = $see['position'];
				$seeInfo['see'] = $see['nums'];
				$seeInfo['reward'] = isset($redata[$see['date']][$see['position']]['nums']) ?  $redata[$see['date']][$see['position']]['nums']:0;
				$data[$see['date']][$see['position']] = $seeInfo;
			}
			Redis::hset('adshowlist','android', json_encode($data));
		}

	}
}
