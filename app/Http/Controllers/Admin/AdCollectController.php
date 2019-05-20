<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdCollect;
use Illuminate\Http\Request;
use App\Http\Requests;

use App\Models\Config;

use Input;
use FileTool;
use Redis;
use Session;
use DB;
use PDO;
use Auth;
use Storage;
use Log;

class AdCollectController extends AdminBaseController
{
    protected $modelName = 'ad_collect';

    public function adShowList()
	{
		$device = Input::get('device','ios');
		$list = ['coinPage','homePromote','finishGame','nextScreen','getTools','appLaunch','startGame'];
		DB::setFetchMode(PDO::FETCH_ASSOC);
		if (!Redis::exists('adshowlist')) {
			AdCollect::AdShowList();
		}
		$data = Redis::hget('adshowlist','ios');
		if ($device == 'android'){$data = Redis::hget('adshowlist','android');}
		return $this->render(
			'admin.ad_index',
			[
				'list' 		=> json_decode($data,true),
				'device'	=> $device,
				'title' 	=> $list
			]
		);
	}

}
