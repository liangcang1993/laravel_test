<?php

namespace App\Models;

use App\Models\Material;

use Redis;
use DB;
use Log;
use FileTool;

class RedisToCnMysql
{ 

    public static function addToQueue($id){
        Redis::rpush('manly_material',$id);
    }

    public static function run(){

		$item = Redis::lpop('manly_material');
		while (!empty($item))
		{
			Log::info('item =======   '. $item);
			$data = [];
			$model = DB::table('manly_material')->select('id','large_pic','en_material','small_pic','small_pic_new','icon','width','height','en_material1','en_material2','en_material6')->find($item);
			Log::info('$model========   '.$model->id);
			$data['en_material1']    = Material::getEncryptBySize($model->large_pic,$model->en_material,$model->width*0.4 . 'x' . $model->height*0.4,1);
			$data['en_material2']    = Material::getEncryptBySize($model->large_pic,$model->en_material,$model->width*0.5 . 'x' . $model->height*0.5,2);
			$data['en_material6']    = Material::getEncryptBySize($model->large_pic,$model->en_material,$model->width*0.8 . 'x' . $model->height*0.8,6);
			DB::table('manly_material')->where('id',$item)->update($data);
			Log::info('en_material1========   '.$data['en_material1']);
			Log::info('en_material2========   '.$data['en_material2']);
			Log::info('en_material6========   '.$data['en_material6']);

			FileTool::getImageUrlCnNew($model->large_pic,1);
			FileTool::getImageUrlCnNew($model->small_pic,1);
			FileTool::getImageUrlCnNew($model->small_pic_new,1);
			FileTool::getImageUrlCnNew($model->icon,1);
			FileTool::getImageUrlCnNew($model->en_material,1);
			FileTool::getImageUrlCnNew($data['en_material1'],1);
			FileTool::getImageUrlCnNew($data['en_material2'],1);
			FileTool::getImageUrlCnNew($data['en_material6'],1);
			unset($data);

			$item = Redis::lpop('manly_material');
		}
   }
}
