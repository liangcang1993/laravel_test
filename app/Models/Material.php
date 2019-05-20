<?php

namespace App\Models;

use App\Services\FileService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Storage;

use FileTool;
use Storage;
use Redis;
use Log;
use Config;

class Material extends BaseModel
{
    use SoftDeletes;
    protected $table = 'manly_material';

    public static function getPageQuery($filter = array())
    {
        $query = self::onWriteConnection()->selectRaw('*');
        if(isset($filter['name']) && trim($filter['name']) != '') {
            $query->where('name', 'like',  '%'.$filter['name'].'%');
        }
        if(isset($filter['type']) && trim($filter['type']) != '') {
            if (0!=(int)$filter['type']){
                $query->Where('id',$filter['type']);
            }else{
                $query->where('type', 'like', '%'.$filter['type'].'%');

            }
        }
        if(isset($filter['sub_type']) && trim($filter['sub_type']) != '') {
            $query->where('sub_type', 'like', '%'.$filter['sub_type'].'%');
        }

        if(isset($filter['is_vip']) && trim($filter['is_vip']) != '') {
            $query->where('is_vip', '=', $filter['is_vip']);
        }
        if(isset($filter['is_pass']) && trim($filter['is_pass']) != '') {
            $query->where('is_pass', '=', $filter['is_pass']);
        }
		if(isset($filter['dev'])) {
			$query->where('device', '=', $filter['dev']);
		}
        
         if(isset($filter['sort']) && trim($filter['sort']) != '') {
            if(starts_with($filter['sort'], 'used') || starts_with($filter['sort'], 'download')
             || starts_with($filter['sort'], 'weight')|| starts_with($filter['sort'], 'sort')
             || starts_with($filter['sort'], 'use_rate')){
                $filter['sort'] = $filter['sort'];
            }
            $d = explode(' ', $filter['sort']);
            $query->orderBy($d[0], $d[1]);
        }else{
            $query->orderBy('id', 'desc');
        }
        $pageNum = isset($filter['pageNum']) ? $filter['pageNum']: 10;
        return $query->paginate($pageNum);
    }

       
    public static function getMaterials($params,$id=null,$order=null)
    {
        $list = self::onWriteConnection()->select(
        									'id','is_vip','small_pic','icon','type','sub_type','display_name','display_color','is_new','unique_name','en_material',
													'large_pic','desc','small_pic_new','display_color','display_color2','coin','level_20_coin','level_24_coin','level_48_coin',
													'level_70_coin','level_108_coin','level_180_coin','level_288_coin','level_336_coin','level_432_coin','level_504_coin','level_704_coin',
													'banner_pic','display_name_cn','display_name_tw','display_name_jp','display_name_ru','desc_cn','desc_tw','desc_jp','desc_ru'
												);
        if (is_null($id) == false || isset($params['id']))
        {
            # code...
            $list->where('id', $params['id']);
            if (is_null($list->first()))
            {
                return [];
            }
            return $list->first()->getJson($params);

        }
		$dev = 0;
		$params['clt'] == 'android' && $dev = 1;
		$list->where('device', $dev);
        if(isset($params['type']) && empty($params['type']) && isset($params['sub_type']) && $params['sub_type'] == 'hot')
		{
			$list->where('is_new', 2);
			$params['sub_type'] = '';
		}
        !empty($params['type']) &&  $list->where('type', $params['type']);
        !empty($params['sub_type']) &&  $list->where('sub_type', $params['sub_type']);
        if (90909>$params['version'])
        {
            $list->where('is_pass', '1');
        }
        !empty($params['version']) &&  $list->where('version', '<=',$params['version']);
        if (!$order){$list->orderBy('score', 'desc');}
		$list->orderBy('weight', 'desc');
        $list = $list->paginate($params['page_size']);
        $datas = [];
        foreach ($list as $l)
        {
			$t1 = microtime(true);
            $data = $l->getJson($params);
			$t2 = microtime(true);
			$t3 = round($t2-$t1,3);
			if($t3 >= 2)
			{
				Log::info('getMaterials =='.$l->id.'==getJson 耗时  :  '.round($t2-$t1,3).'秒');
			}
            $datas[] = $data;
        }
        $d['list'] = $datas;
        $d['lastPage'] = $list->lastPage() == $params['page'] ? 1 : 0;
        return $d;
    }

    public function getJson($params)
    {
        $data['uniqueID']           = $this->id;
        $data['isVip']              = $this->is_vip;
//        $data['weight']             = $this->weight;
        $data['thumbnailUrl']       = FileTool::getImageUrl($this->small_pic, $params['china']);      //缩略图
        $data['thumbnailSmallUrl']  = FileTool::getImageUrl($this->icon, $params['china']);      //icon
        $data['type']               = $this->type;
        $data['subType']            = $this->sub_type;
        $data['disPlayName']        = $this->display_name;
        $data['disPlayColor']       = $this->display_color;
        $data['isNew']              = $this->is_new;
        $data['unique_name']        = $this->unique_name;
		$data['en_material']        = empty($this->en_material)?'':FileTool::getImageUrl($this->en_material, $params['china']);
		$data['originalUrl'] 		= '';
        if (isset($params['id']))
		{
			$data['originalUrl']        = FileTool::getImageUrl($this->large_pic, $params['china']);       //素材
		}
        if (empty($data['en_material']))
		{
			$data['originalUrl']        = FileTool::getImageUrl($this->large_pic, $params['china']);       //素材
			$data['en_material']        = FileTool::getImageUrl($this->large_pic, $params['china']);
		}
        $data['desc']               = $this->desc;

		$data['puzzle_small_pic']   = $data['thumbnailUrl'];
		$data['puzzle_small_pic_new']   = empty($this->small_pic_new) ? $data['thumbnailUrl'] : FileTool::getImageUrl($this->small_pic_new, $params['china']);
		$name = 'display_name_'.$params['language'];
		if ($params['language'] == 'en')
		{
			$name = 'display_name';
		}
		$desc = 'desc_'.$params['language'];
		if ($params['language'] == 'en')
		{
			$desc = 'desc';
		}
		$data['name']  = $this->$name;
		$data['desc']  = $this->$desc;
		if (empty($this->$name))
		{
			$data['name']      = $this->display_name;
		}
		if (empty($this->$desc))
		{
			$data['desc']      = $this->desc;
		}
		$data['color']      = $this->display_color;
		$data['color2']     = $this->display_color2;

		$data['puzzle_coin']        = $this->coin;
		$data['puzzle_20_coin']     = $this->level_20_coin;
		$data['puzzle_24_coin']     = $this->level_24_coin;
		$data['puzzle_48_coin']     = $this->level_48_coin;
		$data['puzzle_70_coin']     = $this->level_70_coin;
		$data['puzzle_108_coin']    = $this->level_108_coin;
		$data['puzzle_180_coin']    = $this->level_180_coin;
		$data['puzzle_288_coin']    = $this->level_288_coin;
		$data['puzzle_336_coin']    = $this->level_336_coin;
		$data['puzzle_432_coin']    = $this->level_432_coin;
		$data['puzzle_504_coin']    = $this->level_504_coin;
		$data['puzzle_704_coin']    = $this->level_704_coin;

		$data['banner_pic']    		= empty($this->banner_pic)?'':FileTool::getImageUrl($this->banner_pic, $params['china']);
        return $data;
    }



	public static function getMaterialById($params)
	{
		$item = Material::find($params['id']);
		$data = [];
		if ($item)
		{
			$data = $item->getJson($params);
		}
		return $data;
	}



     

    public function getWeight(){
        $name =  'weight';
        return $this->$name;
    }
    public function getIsVip(){
        $name =   'is_vip';
        return $this->$name;
    }
    
    public function getSort(){
        $name =   'sort';
        return $this->$name;
    }
    public function getUsedNum(){
        $name =   'used_num';
        return $this->$name;
    }
    public function getUsedNumDay(){
        $name =   'used_day_num';
        return $this->$name;
    }
    public function getUsedNumWeak(){
        $name =   'used_weak_num';
        return $this->$name;
    }
    public function getDownloadNum(){
        $name =  'download_num';
        return $this->$name;
    }
    public function getDownloadNumDay(){
        $name =   'download_day_num';
        return $this->$name;
    }
    public function getDownloadNumWeak(){
        $name =   'download_weak_num';
        return $this->$name;
    }
    public function getUseRate(){
        $name =   'use_rate';
        return $this->$name;
    }

    public function iconUrl(){
        return self::getRealUrl($this->icon);
    }
    public function smallPicUrl($china=null){
        return self::getRealUrl($this->small_pic);
    }
    
    public function largePicUrl(){
        return self::getRealUrl($this->large_pic);
    }
    

    public static function getSmallPic($material,$tag,$size)
    {
        $name = 'large_pic';
        $large_path = $material->$name;
        $ext = explode('.', $large_path);
        $earr = ['jpg','png','jpeg'];
        if (isset($ext[1]) && !in_array($ext[1], $earr)) 
        {
            return 0;
        }
                // echo ',' .$gif->large_path;
//        $file = Storage::get($large_path);
		if (file_exists(public_path('uploadImg/') .  $large_path))
		{
			$file = file_get_contents(public_path('uploadImg/') .  $large_path);
		}else{
			$file = Storage::get($large_path);
		}
//         echo $file;
        $path ='tmp' . $large_path ;
        if(!empty($file)){

            $r = Storage::disk('local')->put($path, $file);
            $from = substr($path,12);
            $ext = explode('.', $from);
            $to =  'small_' . time()  .'_' . str_random(4) . '.' . $ext[1];

            //压缩gif
            $cmd = 'convert ' . $from . ' -resize '.$size.'! ' . $to ;
            $cmd = 'cd ' . storage_path() . '/app/tmpmaterial/;'  . $cmd;
            // echo $cmd;
            $r = exec($cmd);
            // Log::info($r);

            $small_flle = Storage::disk('local')->get('tmpmaterial/'. $to);
            $size = strlen($small_flle);
            if($size >10){
            	if (!empty($material->$tag))
				{
					$smallpath = $material->$tag;
				}else{
					$smallpath = 'material/' . $to;
				}
                $res = Storage::put($smallpath, $small_flle);
				Storage::disk('local')->put($smallpath, $small_flle);
                if($res){
//                    if(Storage::disk('local')->exists($smallpath))
//                    {
//                        Storage::disk('local')->delete($smallpath);
//                    }
                    $material->$tag = $smallpath;
                    $material->save();
                }
             }

            $cmd = 'cd ' . storage_path() . '/app/tmpmaterial/; rm -rf ./'. $from . ' ./' .$to;
            exec($cmd);
            // if (file_exists(storage_path() . '/app/material/' . $from)) 
            // {
            //     Storage::disk('local')->delete('material/' . $from);
            // }

        }

    }


    public static function getEncrypt($img){
//        $url = self::getRealUrl($img);
		$url = public_path('uploadImg/') .  $img;
        Log::info('getEncrypt url:'.$url);
        $ext = explode('.',$img);
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $contents = file_get_contents($url,false, stream_context_create($arrContextOptions));
        $path = Storage::disk('local')->put('material/file.jpg', $contents);
        Storage::disk('local')->put($img, $contents);
		Storage::put($img,$contents);
        $path = storage_path('app/material/file.jpg');
		if(env('APP_ENV') != 'online')
		{
			$image = system("/usr/local/encrypt/alphaencrypt 0 $path");
		}else{
			$image = system("/usr/local/en/alphaencrypt 0 $path");
		}
        $path = FileService::uploadByFileEncrypt($image, 'encrypt',$ext[1]);
        return $path;
    }


	public static function getEncryptOneName($img,$name){
//        $url = self::getRealUrl($img);
		$url = public_path('uploadImg/') .  $img;
		Log::info('getEncrypt url:'.$url);
		$ext = explode('.',$img);
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);
		$contents = file_get_contents($url,false, stream_context_create($arrContextOptions));
		$path = Storage::disk('local')->put('material/file.jpg', $contents);
		Storage::disk('local')->put($img, $contents);
		Storage::put($img,$contents);
		$path = storage_path('app/material/file.jpg');
		if(env('APP_ENV') != 'online')
		{
			$image = system("/usr/local/encrypt/alphaencrypt 0 $path");
		}else{
			$image = system("/usr/local/en/alphaencrypt 0 $path");
		}
		$path = FileService::uploadByFileEncrypt($image, 'encrypt',$ext[1],0,$name);
		return $path;
	}

	public static function getEncryptBySize($img,$name,$size,$num){
		if (empty($name)){return '';}
    	$filename = explode('/',$name);
		if (!isset($filename[1]) || empty($filename[1])){return '';}
		$fname = explode('.',$filename[1]);
		if (!isset($fname[0]) || empty($fname[0])){return '';}
    	$img = self::getSmallPicUrl($img,$fname[0],$size);
		$localImg = $img;
		Log::info('getEncryptBySize2 $img  :: '.$img);
    	if (empty($img)){return '';}
		if (file_exists(storage_path() . '/app/'.$img))
		{
			$img = storage_path() . '/app/'.$img;
		}else{
			$img = Storage::get($img);
		}
//		$url = self::getRealUrl($img);
//		$url = Storage::disk('local')->get($img);
		$ext = explode('.',$img);
		$arrContextOptions=array(
			"ssl"=>array(
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			),
		);
		$contents = file_get_contents($img,false, stream_context_create($arrContextOptions));
		$path = Storage::disk('local')->put('material/file.jpg', $contents);
		$path = storage_path('app/material/file.jpg');
		if(env('APP_ENV') != 'online')
		{
			$image = system("/usr/local/encrypt/alphaencrypt 0 $path");
		}else{
			$image = system("/usr/local/en/alphaencrypt 0 $path");
		}
		$path = FileService::uploadByFileNameEncrypt($image, 'encrypt',$ext[1],0,$fname[0],$num);
//		Log::info('getEncryptBySize result ==== '.$path);
		if(Storage::disk('local')->exists($localImg))
		{
			Storage::disk('local')->delete($localImg);
		}
		return $path;
	}

	public static function getSmallPicUrl($large_pic,$name,$size)
	{
		$large_path = $large_pic;
		$ext = explode('.', $large_path);
		$earr = ['jpg','png','jpeg'];
		if (isset($ext[1]) && !in_array($ext[1], $earr))
		{
			return 0;
		}
		Log::info('getSmallPicUrl :::'.public_path('uploadImg/') .  $large_path);
		// echo ',' .$gif->large_path;
		if (file_exists(public_path('uploadImg/') .  $large_path))
		{
			$file = file_get_contents(public_path('uploadImg/') .  $large_path);
		}else{
			$file = Storage::get($large_path);
		}
		$path ='tmp' . $large_path ;
		if(!empty($file)){

			$r = Storage::disk('local')->put($path, $file);
//			$from = substr($large_path,9);
			$from = substr($path,12);
			$ext = explode('.', $from);
			$to =  'small_' . $name . $size . '.' . $ext[1];

			//压缩gif
			$cmd = 'convert ' . $from . ' -resize '.$size.'! ' . $to ;
			$cmd = 'cd ' . storage_path() . '/app/tmpmaterial/;'  . $cmd;
//			$cmd = 'cd ' . public_path('uploadImg/').';'  . $cmd;
			// echo $cmd;
			Log::info('getSmallPicUrl $cmd :: '.$cmd);
			$r = exec($cmd);
			$small_flle = Storage::disk('local')->get('tmpmaterial/'. $to);
//			$small_flle = file_get_contents(public_path('uploadImg/') .  $to);
			$size = strlen($small_flle);
			if($size >10){
				$smallpath = 'material/' . $to;
				Log::info('getSmallPicUrl $smallpath :: '.$smallpath);
				$res = Storage::disk('local')->put($smallpath, $small_flle);
				Storage::put($smallpath, $small_flle);
				$cmd = 'cd ' . storage_path() . '/app/tmpmaterial/; rm -rf ./'. $from . ' ./' .$to;
				exec($cmd);

				if($res){

					if(env('APP_ENV') != 'online')
					{
						copy(storage_path('app/material/' . $to), public_path('uploadImg/material/'.  $to));
					}
//					else{
//						if(Storage::disk('local')->exists($smallpath))
//						{
//							Storage::disk('local')->delete($smallpath);
//						}
//					}
					return $smallpath;
				}
				return '';
			}
		}

	}

    public static function updateMaterialVip($name,$field)
    {
        $list = Material::where('sub_type',$name)->where('device',0)->get();
        foreach ($list as $key => &$value) 
        {
            $model = Material::find($value->id);
            $model->is_vip = $field;
            $model->save();
            unset($model);
        }
    }

	public static function getWhatsNewIds($subname=null)
	{
		if (is_null($subname))
		{
			return 0;
		}
		$typeid = Type::where('parent_id','!=',0)->where('weight','>=','0')->where('device','!=',1)->where('name', $subname)->first();
		if (is_null($typeid))
		{
			return 0;
		}
		if (Redis::exists('whatsnewIds'))
		{
			$arr = json_decode(Redis::get('whatsnewIds'),true);
			$k = array_search($typeid->id,$arr);
			if(isset($arr[$k])){
				unset($arr[$k]);
			}
			array_unshift($arr,$typeid->id);
			Redis::set('whatsnewIds',json_encode($arr));
			return 0;
		}
		$subIds = Type::where('parent_id','!=',0)->where('weight','>=','0')->where('device','!=',1)->orderBy('is_new', 'desc')->lists('id')->toArray();
		if (!is_null($typeid) && in_array($typeid->id,$subIds))
		{
			$k = array_search($typeid->id,$subIds);
			if(isset($subIds[$k])){
				unset($subIds[$k]);
			}
			array_unshift($subIds,$typeid->id);
		}

		Redis::set('whatsnewIds',json_encode($subIds));

	}

}
