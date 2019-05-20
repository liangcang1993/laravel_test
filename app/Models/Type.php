<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Redis;
use FileTool;
use Storage;

class Type extends BaseModel
{
    use SoftDeletes;
    protected $table = 'manly_type';
 
    public static function getPageQuery($filter = array())
    {
        $query = Type::onWriteConnection()->selectRaw('*');
//		$query->where('weight','>=','0');
        if (isset($filter['name']) && trim($filter['name']) != '') {
            $query->where('name', 'like', '%'.$filter['name'].'%');
        }
        if (isset($filter['parent_id']) && trim($filter['parent_id']) != '') {
            $query->where('parent_id', '=', $filter['parent_id']);
        }
		if(isset($filter['dev'])) {
			if ($filter['dev'] == 0){
				$query->where('device', '!=', 1);
			}elseif ($filter['dev'] == 1){
				$query->where('device', '!=', 0);
			}
		}
        if (isset($filter['sort']) && trim($filter['sort']) != '') {
            $d = explode(' ', $filter['sort']);
            $query->orderBy($d[0], $d[1]);
        }else{
            $query->orderBy('id', 'desc');
        }
        return $query->paginate(10);

    }
    public function picUrl(){
        return Type::getRealUrl($this->pic);
    }
    public function iconUrl(){
        return Type::getRealUrl($this->icon);
    }
    public function picUrlIos(){
        return Type::getRealUrl($this->ios_pic_frame);
    }
    public function picUrlAsia(){
        return Type::getRealUrl($this->pic_asia);
    }
    public function picUrlAndroid(){
        return Type::getRealUrl($this->android_pic);
    }


	public static function getTypesIosNew($params)
	{
		$query = self::onWriteConnection()->selectRaw('*')->where('parent_id','!=',0)->where('device','!=',1);
		if ($params['version'] < 90909)
		{
			$query->where('weight','>=','0')->where('version','<=',$params['version']);

		}
		$query->orderBy('weight', 'desc')->get();
		$list = $query->paginate($params['page_size']);
		$d = [];

		foreach ($list as $subvalue)
			{
				$subItem['uniqueID'] = $subvalue->id;
				$subItem['order'] = $subvalue->is_new;
				$name = 'name_'.$params['language'];
				if ($params['language'] == 'en' || empty($subvalue->$name))
				{
					$name = 'name';
				}
				$desc = 'desc_'.$params['language'];
				$params['language'] == 'en' && $desc = 'desc';
				if (empty($subvalue->$desc))
				{
					$desc = 'desc';
				}
				$subItem['name']            = $subvalue->$name;
				$subItem['searchName']      = $subvalue->name;
				$subItem['typeSearchName']  = '';
				$p = Type::getTypeBySubType($subvalue->parent_id);
				if (!empty($p)){$subItem['typeSearchName']  = $p->name;}
				$subItem['type_desc']       = $subvalue->$desc;
//				$subItem['type_color']      = $subvalue->color1;
//				$subItem['type_color2']     = $subvalue->color2;
				$subItem['type_bg_color']   = $subvalue->bg_color;
				$subItem['packagepay']      = $subvalue->packagepay;
				$subItem['is_vip']          = $subvalue->is_vip;
				$subItem['is_new']          = 0;
				$newKey = 'api/jigsawNew/subtype';
				$newKey .= $params['version'];
				$newKey .= $subvalue->name;
				if(!Redis::exists($newKey))
				{
					Redis::set($newKey, 0);
					$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>0])->first();
					if (!is_null($newMaterial))
					{
						Redis::set($newKey,1);
					}else{
						$hotMaterial = Material::where(['is_new'=>2,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>0])->first();
						if (!is_null($hotMaterial)){Redis::set($newKey,2);}
					}
				}
				$subItem['is_new'] = Redis::get($newKey);
				$subItem['is_new_color']    = $subvalue->is_new_color;
				$subItem['font']            = $subvalue->font;
				$subItem['type_created']    = $subvalue->created_at->format('Y-m-d H:i:s');
				$subItem['ios_pic_frame']   = empty($subvalue->ios_pic_frame)?'':FileTool::getImageUrl($subvalue->ios_pic_frame, $params['china']);
				$subItem['type_img']        = empty($subvalue->pic)?'':FileTool::getImageUrl($subvalue->pic, $params['china']);
//				$subItem['type_img_new']   = empty($subvalue->small_pic_new)?$subItem['type_img']:FileTool::getImageUrl($subvalue->small_pic_new, $params['china']);
				$subItem['type_img_new']   = $subItem['type_img'];
//				$subItem['type_android_img']        = empty($subvalue->android_pic)?$subItem['type_img']:FileTool::getImageUrl($subvalue->android_pic, $params['china']);
				$subItem['material_pic']    = [];
				$subItem['midMaterials']            = [];
				if ($params['version'] < 90909)
				{
					$subItem['material_num']    = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>0])->count();
					$subItem['mids']            = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->lists('id');
					$materialPics = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
					if ($materialPics)
					{
						foreach ($materialPics as $mv)
						{
							$subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic, $params['china']);
							$params['id'] = $mv->id;
							$subItem['midMaterials'][]  = Material::getMaterials($params);
							unset($params['id']);
						}
					}
				}else{
					$subItem['material_num']    = Material::where(['sub_type'=>$subvalue->name,'device'=>0])->count();
					$subItem['mids']            = Material::where(['sub_type'=>$subvalue->name,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->lists('id');
					$materialPics = Material::where(['sub_type'=>$subvalue->name,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
					if ($materialPics)
					{
						foreach ($materialPics as $mv)
						{
							$subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic, $params['china']);
							$params['id'] = $mv->id;
							$subItem['midMaterials'][]  = Material::getMaterials($params);
							unset($params['id']);
						}
					}
				}

				$d['list'][] = $subItem;
			}

		$d['lastPage'] = $list->lastPage() == $params['page'] ? 1 : 0;
		return $d;
	}
    //接口调用获取type列表及对应子列表
    public static function getTypesIos($params)
    {
		$query = self::onWriteConnection()->selectRaw('*')->where('parent_id',0)->where('device','!=',1);
        if ($params['version'] < 90909)
        {
			$query->where('weight','>=','0')->where('version','<=',$params['version']);

        }
        $list = $query->orderBy('weight', 'desc')->get();
        $datas = [];
        foreach ($list as $key => $l) 
        {
            $type['uniqueID'] = $l->id;
            $name = 'name_'.$params['language'];
            if ($params['language'] == 'en' || empty($l->$name)) 
            {
                $name = 'name';
            }
            $desc = 'desc_'.$params['language'];
            $params['language'] == 'en' && $desc = 'desc';
            if (empty($l->$desc)) 
            {
                $desc = 'desc';
            }
            $type['name']           = $l->$name;
            $type['searchName']     = $l->name;
            $type['type_desc']      = $l->$desc;
            $type['type_color']     = $l->color1;
            $type['type_color2']    = $l->color2;
            $type['is_new'] = 0;
            $newKey = 'api/jigsawNew/type';
            $newKey .= $params['version'];
            $newKey .= $l->name;
			if(!Redis::exists($newKey))
			{
				Redis::set($newKey, 0);
				$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$type->name,'device'=>0])->first();
				if (!is_null($newMaterial))
				{
					Redis::set($newKey,1);
				}else{
					$hotMaterial = Material::where(['is_new'=>2,'is_pass'=>1,'sub_type'=>$type->name,'device'=>0])->first();
					if (!is_null($hotMaterial)){Redis::set($newKey,2);}
				}
			}
			$type['is_new'] = Redis::get($newKey);
            $type['is_new_color']   = $l->is_new_color;
            $type['font']           = $l->font;
            $type['type_created']   = $l->created_at->format('Y-m-d H:i:s');
            $type['type_img']       = empty($l->pic)?'':FileTool::getImageUrl($l->pic, $params['china']);
            $type['type_img_new']   = $type['type_img'];
//            $type['type_img_new']   = empty($l->small_pic_new)?$type['type_img']:FileTool::getImageUrl($l->small_pic_new, $params['china']);
            $type['type_android_img']       = empty($l->android_pic)?$type['type_img']:FileTool::getImageUrl($l->android_pic, $params['china']);
            $type['ios_pic_frame']  = empty($l->ios_pic_frame)?'':FileTool::getImageUrl($l->ios_pic_frame, $params['china']);
            $type['subtypes']       = [];
			$subquery = self::onWriteConnection()->selectRaw('*')->where('parent_id',$l->id)->where('device','!=',1);;
			if ($params['version'] < 90909)
			{
				$subquery->where('weight','>=','0')->where('version','<=',$params['version']);

			}
			$subs = $subquery->orderBy('weight', 'desc')->get();
            foreach ($subs as $subvalue)
            {
                $subItem['uniqueID'] = $subvalue->id;
                $subItem['order'] = $subvalue->is_new;
                $name = 'name_'.$params['language'];
                if ($params['language'] == 'en' || empty($subvalue->$name)) 
                {
                    $name = 'name';
                }
                $desc = 'desc_'.$params['language'];
                $params['language'] == 'en' && $desc = 'desc';
                if (empty($subvalue->$desc)) 
                {
                    $desc = 'desc';
                }
                $subItem['name']            = $subvalue->$name;
                $subItem['searchName']      = $subvalue->name;
                $subItem['type_desc']       = $subvalue->$desc;
                $subItem['type_color']      = $subvalue->color1;
                $subItem['type_color2']     = $subvalue->color2;
                $subItem['type_bg_color']   = $subvalue->bg_color;
                $subItem['packagepay']      = $subvalue->packagepay;
                $subItem['is_vip']          = $subvalue->is_vip;
                $subItem['is_new']          = 0;
                $newKey = 'api/jigsawNew/subtype';
                $newKey .= $params['version'];
                $newKey .= $subvalue->name;
				if(!Redis::exists($newKey))
				{
					Redis::set($newKey, 0);
					$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>0])->first();
					if (!is_null($newMaterial))
					{
						Redis::set($newKey,1);
					}else{
						$hotMaterial = Material::where(['is_new'=>2,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>0])->first();
						if (!is_null($hotMaterial)){Redis::set($newKey,2);}
					}
				}
				$subItem['is_new'] = Redis::get($newKey);
                $subItem['is_new_color']    = $subvalue->is_new_color;
                $subItem['font']            = $subvalue->font;
                $subItem['type_created']    = $subvalue->created_at->format('Y-m-d H:i:s');
                $subItem['ios_pic_frame']   = empty($subvalue->ios_pic_frame)?'':FileTool::getImageUrl($subvalue->ios_pic_frame, $params['china']);
                $subItem['type_img']        = empty($subvalue->pic)?'':FileTool::getImageUrl($subvalue->pic, $params['china']);
				$subItem['type_img_new']   = $subItem['type_img'];
//				$subItem['type_img_new']   = empty($subvalue->small_pic_new)?$subItem['type_img']:FileTool::getImageUrl($subvalue->small_pic_new, $params['china']);
                $subItem['type_android_img']        = empty($subvalue->android_pic)?$subItem['type_img']:FileTool::getImageUrl($subvalue->android_pic, $params['china']);
                $subItem['material_pic']    = [];
                if ($params['version'] < 90909) 
                {
                    $subItem['material_num']    = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>0])->count();
                    $subItem['mids']            = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->lists('id');
                    $materialPics = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
                    if ($materialPics) 
                    {
                        foreach ($materialPics as $mv) 
                        {
                            $subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic, $params['china']);
                        }
                    }
                }else{
                    $subItem['material_num']    = Material::where(['sub_type'=>$subvalue->name,'device'=>0])->count();
                    $subItem['mids']            = Material::where(['sub_type'=>$subvalue->name,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->lists('id');
                    $materialPics = Material::where(['sub_type'=>$subvalue->name,'device'=>0])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
                    if ($materialPics) 
                    {
                        foreach ($materialPics as $mv) 
                        {
                            $subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic, $params['china']);
                        }
                    }
                }
                   
                $type['subtypes'][]         = $subItem;
            }
            $datas[] = $type;
        }
		$whatsNew = [
			"uniqueID" => '',
			"name" => "WHAT'S NEW",
			"searchName" => "",
			"type_desc" => "",
			"type_color" => "",
			"type_color2" => "",
			"is_new" => 0,
			"is_new_color" => "",
			"font" => "",
			"type_created" => "",
			"type_img" => "",
			"type_android_img" => "",
			"ios_pic_frame" => ""
		];
		$whatsNew['subtypes'] = [];
		if (Redis::exists('whatsnewIds'))
		{
			$subArr = [];
			$arr = json_decode(Redis::get('whatsnewIds'),true);
			foreach ($arr as $item)
			{
				$subvalue = self::onWriteConnection()->find($item);
				if (is_null($subvalue)){
					continue;
				}
				$subItem['uniqueID'] = $subvalue->id;
				$subItem['order']    = $subvalue->is_new;
				$name                = 'name_' . $params['language'];
				if ($params['language'] == 'en' || empty($subvalue->$name)) {
					$name = 'name';
				}
				$desc = 'desc_' . $params['language'];
				$params['language'] == 'en' && $desc = 'desc';
				if (empty($subvalue->$desc)) {
					$desc = 'desc';
				}
				$subItem['name']          = $subvalue->$name;
				$subItem['searchName']    = $subvalue->name;
				$subItem['type_desc']     = $subvalue->$desc;
				$subItem['type_color']    = $subvalue->color1;
				$subItem['type_color2']   = $subvalue->color2;
				$subItem['type_bg_color'] = $subvalue->bg_color;
				$subItem['packagepay']    = $subvalue->packagepay;
				$subItem['is_vip']        = $subvalue->is_vip;
				$subItem['is_new']        = 0;
				$newKey                   = 'api/jigsawNew/subtype';
				$newKey                   .= $params['version'];
				$newKey                   .= $subvalue->name;
				if(!Redis::exists($newKey))
				{
					Redis::set($newKey, 0);
					$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>0])->first();
					if (!is_null($newMaterial))
					{
						Redis::set($newKey,1);
					}else{
						$hotMaterial = Material::where(['is_new'=>2,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>0])->first();
						if (!is_null($hotMaterial)){Redis::set($newKey,2);}
					}
				}
				$subItem['is_new'] = Redis::get($newKey);
				$subItem['is_new_color']     = $subvalue->is_new_color;
				$subItem['font']             = $subvalue->font;
				$subItem['type_created']     = $subvalue->created_at->format('Y-m-d H:i:s');
				$subItem['ios_pic_frame']    = empty($subvalue->ios_pic_frame) ? '' : FileTool::getImageUrl($subvalue->ios_pic_frame, $params['china']);
				$subItem['type_img']         = empty($subvalue->pic) ? '' : FileTool::getImageUrl($subvalue->pic, $params['china']);
				$subItem['type_img_new']     = $subItem['type_img'];
//				$subItem['type_img_new']     = empty($subvalue->small_pic_new)?$subItem['type_img']:FileTool::getImageUrl($subvalue->small_pic_new, $params['china']);
				$subItem['type_android_img'] = empty($subvalue->android_pic) ? $subItem['type_img'] : FileTool::getImageUrl($subvalue->android_pic, $params['china']);
				$subItem['material_pic']     = [];
				if ($params['version'] < 90909) {
					$subItem['material_num'] = Material::where(['sub_type' => $subvalue->name, 'is_pass' => 1, 'device' => 0])->count();
					$subItem['mids']         = Material::where(['sub_type' => $subvalue->name, 'is_pass' => 1, 'device' => 0])->orderby('score','desc')->orderby('weight','desc')->lists('id');
					$materialPics            = Material::where(['sub_type' => $subvalue->name, 'is_pass' => 1, 'device' => 0])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
					if ($materialPics) {
						foreach ($materialPics as $mv) {
							$subItem['material_pic'][] = empty($mv->small_pic) ? '' : FileTool::getImageUrl($mv->small_pic, $params['china']);
						}
					}
				} else {
					$subItem['material_num'] = Material::where(['sub_type' => $subvalue->name, 'device' => 0])->count();
					$subItem['mids']         = Material::where(['sub_type' => $subvalue->name, 'device' => 0])->orderby('score','desc')->orderby('weight','desc')->lists('id');
					$materialPics            = Material::where(['sub_type' => $subvalue->name, 'device' => 0])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
					if ($materialPics) {
						foreach ($materialPics as $mv) {
							$subItem['material_pic'][] = empty($mv->small_pic) ? '' : FileTool::getImageUrl($mv->small_pic, $params['china']);
						}
					}
				}
				$params['page_size'] = 6;
				$params['sub_type'] = $subItem['searchName'];
				$materials = Material::getMaterials($params);
				$subItem['materials'] = $materials['list'];
				$subArr[] = $subItem;
			}
			$whatsNew['subtypes']   = $subArr;

		}else{

			if (!empty($datas))
			{
				$subTypeArr = [];
				foreach ($datas as $subkey => $subvalue)
				{
					if (empty($subvalue['subtypes']))
					{
						continue;
					}
					foreach ($subvalue['subtypes'] as &$stvalue)
					{
						$params['type'] = $subvalue['searchName'];
						$params['page_size'] = 6;
						$params['sub_type'] = $stvalue['searchName'];
						$materials = Material::getMaterials($params);
						$stvalue['materials'] = $materials['list'];
						$subTypeArr[] = $stvalue;
						unset($params['type']);
						unset($params['sub_type']);
					}
				}
				if (!empty($subTypeArr))
				{
					foreach ($subTypeArr as $k => $v)
					{
						$orderId[$k] = $v['uniqueID'];
						$orderIsNew[$k] = $v['order'];
					}
					array_multisort($orderIsNew,SORT_DESC,$orderId,SORT_DESC,$subTypeArr);
					$whatsNew['subtypes']   = $subTypeArr;
				}
			}

		}
		$datas[] = $whatsNew;
		array_unshift($datas, array_pop($datas));
		return $datas;
    }

	public static function getTypesAndroid($params)
	{
		$query = self::onWriteConnection()->selectRaw('*')->where('parent_id',0)->where('device','!=',0);
		if ($params['version'] < 20000000)
		{
			$query->where('weight','>=','0')->where('version','<=',$params['version']);

		}
		$list = $query->orderBy('weight', 'desc')->get();
		$datas = [];
		foreach ($list as $key => $l)
		{
			$type['uniqueID'] = $l->id;
			$name = 'name_'.$params['language'];
			if ($params['language'] == 'en' || empty($l->$name))
			{
				$name = 'name';
			}
			$desc = 'desc_'.$params['language'];
			$params['language'] == 'en' && $desc = 'desc';
			if (empty($l->$desc))
			{
				$desc = 'desc';
			}
			$type['name']           = $l->$name;
			$type['searchName']     = $l->name;
			$type['type_desc']      = $l->$desc;
			$type['type_color']     = $l->color1;
			$type['type_color2']    = $l->color2;
			$type['is_new'] = 0;
			$newKey = 'api/jigsawNew/type';
			$newKey .= $params['version'];
			$newKey .= $l->name;
			if(!Redis::exists($newKey))
			{
				Redis::set($newKey, 0);
				$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'type'=>$l->name,'device'=>1])->first();
				if (!is_null($newMaterial))
				{
					Redis::set($newKey, 1);
				}

			}

			if (Redis::get($newKey) == 1)
			{
				$type['is_new'] = 1;
			}
			$type['is_new_color']   = $l->is_new_color;
			$type['font']           = $l->font;
			$type['type_created']   = $l->created_at->format('Y-m-d H:i:s');
			$type['type_img']       = empty($l->pic)?'':FileTool::getImageUrl($l->pic, $params['china']);
			$type['type_android_img']       = empty($l->android_pic)?$type['type_img']:FileTool::getImageUrl($l->android_pic, $params['china']);
			$type['ios_pic_frame']  = empty($l->ios_pic_frame)?'':FileTool::getImageUrl($l->ios_pic_frame, $params['china']);
			$type['subtypes']       = [];
			$subquery = self::onWriteConnection()->selectRaw('*')->where('parent_id',$l->id)->where('device','!=',0);
			if ($params['version'] < 20000000)
			{
				$subquery->where('weight','>=','0')->where('version','<=',$params['version']);

			}
			$subs = $subquery->orderBy('weight', 'desc')->get();
			foreach ($subs as $subvalue)
			{
				$subItem['uniqueID'] = $subvalue->id;
				$subItem['order'] = $subvalue->is_new;
				$name = 'name_'.$params['language'];
				if ($params['language'] == 'en' || empty($subvalue->$name))
				{
					$name = 'name';
				}
				$desc = 'desc_'.$params['language'];
				$params['language'] == 'en' && $desc = 'desc';
				if (empty($subvalue->$desc))
				{
					$desc = 'desc';
				}
				$subItem['name']            = $subvalue->$name;
				$subItem['searchName']      = $subvalue->name;
				$subItem['type_desc']       = $subvalue->$desc;
				$subItem['type_color']      = $subvalue->color1;
				$subItem['type_color2']     = $subvalue->color2;
				$subItem['type_bg_color']   = $subvalue->bg_color;
				$subItem['packagepay']      = $subvalue->packagepay;
				$subItem['is_vip']          = $subvalue->is_vip;
				$subItem['is_new']          = 0;
				$newKey = 'api/jigsawNew/subtype';
				$newKey .= $params['version'];
				$newKey .= $subvalue->name;
				if(!Redis::exists($newKey))
				{
					Redis::set($newKey, 0);
					$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>1])->first();
					if (!is_null($newMaterial))
					{
						Redis::set($newKey,1);
					}
				}
				if (Redis::get($newKey) == 1)
				{
					$subItem['is_new'] = 1;
				}
				$subItem['is_new_color']    = $subvalue->is_new_color;
				$subItem['font']            = $subvalue->font;
				$subItem['type_created']    = $subvalue->created_at->format('Y-m-d H:i:s');
				$subItem['ios_pic_frame']   = empty($subvalue->ios_pic_frame)?'':FileTool::getImageUrl($subvalue->ios_pic_frame, $params['china']);
				$subItem['type_img']        = empty($subvalue->pic)?'':FileTool::getImageUrl($subvalue->pic, $params['china']);
				$subItem['type_android_img']        = empty($subvalue->android_pic)?$subItem['type_img']:FileTool::getImageUrl($subvalue->android_pic, $params['china']);
				$subItem['material_pic']    = [];
				if ($params['version'] < 20000000)
				{
					$subItem['material_num']    = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>1])->count();
					$subItem['mids']            = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>1])->lists('id');
					$materialPics = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
					if ($materialPics)
					{
						foreach ($materialPics as $mv)
						{
							$subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic, $params['china']);
						}
					}
				}else{
					$subItem['material_num']    = Material::where(['sub_type'=>$subvalue->name,'device'=>1])->count();
					$subItem['mids']            = Material::where(['sub_type'=>$subvalue->name,'device'=>1])->lists('id');
					$materialPics = Material::where(['sub_type'=>$subvalue->name])->orderby('score','desc')->orderby('weight','desc')->limit(3)->get();
					if ($materialPics)
					{
						foreach ($materialPics as $mv)
						{
							$subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic, $params['china']);
						}
					}
				}

				$type['subtypes'][]         = $subItem;
			}
			$datas[] = $type;
		}
		return $datas;
	}


    public static function getSmallSizePic($material,$tag,$size)
    {
        if (empty($material) || empty($tag) || empty($size)) 
        {
            return 0;
        }
        $name = 'pic';
        $large_path = $material->$name;
        $ext = explode('.', $large_path);
        $earr = ['jpg','png','jpeg'];
        if (isset($ext[1]) && !in_array($ext[1], $earr)) 
        {
            return 0;
        }
                // echo ',' .$gif->large_path;
        $file = Storage::get($large_path);
        // echo $file;
        $path ='tmp' . $large_path ;
        if(!empty($file)){

            $r = Storage::disk('local')->put($path, $file);
            $from = substr($path,12);
            $ext = explode('.', $from);
            $to =  'small_' . time()  .'_' . str_random(4) . '.' . $ext[1];

            //压缩gif
            $cmd = 'convert ' . $from . ' -resize '. $size .'! ' . $to ;
            $cmd = 'cd ' . storage_path() . '/app/tmpmaterial/;'  . $cmd;
            // echo $cmd;
            $r = exec($cmd);
            // Log::info($r);

            $small_flle = Storage::disk('local')->get('tmpmaterial/'. $to);
            $size = strlen($small_flle);
            if($size >10){
                $smallpath = 'material/' . $to;
                $res = Storage::put($smallpath, $small_flle);

                if($res){
                    if(Storage::disk('local')->exists($smallpath))
                    {
                        Storage::disk('local')->delete($smallpath);
                    }
                    $material->$tag = $smallpath;
                    $material->save();
                }
             }

            $cmd = 'cd ' . storage_path() . '/app/tmpmaterial/; rm -rf ./'. $from . ' ./' .$to;
            exec($cmd);

        }

    }


	public static function updateWhatsNewIds($subname=null,$update=null,$del=null)
	{
		if (!is_null($update))
		{
			Redis::del('whatsnewIds');
			return 0;
		}
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
			if (!is_null($del)){
				Redis::set('whatsnewIds',json_encode($arr));
				return 0;
			}
			array_unshift($arr,$typeid->id);
			Redis::set('whatsnewIds',json_encode($arr));
			return 0;
		}
		$subIds = Type::where('parent_id','!=',0)->where('weight','>=','0')->where('device','!=',1)->orderBy('is_new', 'desc')->lists('id')->toArray();
		if (!is_null($typeid) && in_array($typeid->id,$subIds))
		{
			$k = array_search($typeid->id,$subIds);
			if($k != false){
				unset($subIds[$k]);
			}
			array_unshift($subIds,$typeid->id);
		}

		Redis::set('whatsnewIds',json_encode($subIds));

	}

	public static function delMaterials($subname=null)
	{

		if (is_null($subname))
		{
			return 0;
		}
		$typeinfo = Type::where('name', $subname)->first();
		if (is_null($typeinfo))
		{
			return 0;
		}
		if ($typeinfo->parent_id != 0){
			$list = Material::where('sub_type',$subname)->get();
			foreach ($list as $l){
				$l->delete();

			}
		}


	}

	public static function getTypeBySubType($pid)
	{
		$l = self::onWriteConnection()->find($pid);
		if (is_null($l)){
			return [];
		}
		return $l;
	}
	public static function getTypeById($id)
	{
		$l = self::onWriteConnection()->find($id);
		if (is_null($l)){
			return [];
		}
		if ($l->parent_id != 0)
		{
			$subItem['uniqueID'] = $l->id;
			$subItem['order'] = $l->is_new;
			$subItem['name']            = $l->name;
			$subItem['searchName']      = $l->name;
			$subItem['type_desc']       = $l->desc;
			$subItem['type_color']      = $l->color1;
			$subItem['type_color2']     = $l->color2;
			$subItem['type_bg_color']   = $l->bg_color;
			$subItem['packagepay']      = $l->packagepay;
			$subItem['is_vip']          = $l->is_vip;
			$subItem['is_new']          = 0;
			if ($l->device == 2){
				$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$l->name])->first();
			}else{
				$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$l->name,'device'=>$l->device])->first();
			}
			if (!is_null($newMaterial))
			{
				$subItem['is_new'] = 1;
			}
			$subItem['is_new_color']    = $l->is_new_color;
			$subItem['font']            = $l->font;
			$subItem['type_created']    = $l->created_at->format('Y-m-d H:i:s');
			$subItem['ios_pic_frame']   = empty($l->ios_pic_frame)?'':FileTool::getImageUrl($l->ios_pic_frame);
			$subItem['type_img']        = empty($l->pic)?'':FileTool::getImageUrl($l->pic);
			$subItem['type_img_new']    = empty($l->small_pic_new)?$subItem['type_img']:FileTool::getImageUrl($l->small_pic_new);
			$subItem['type_android_img']        = empty($l->android_pic)?$subItem['type_img']:FileTool::getImageUrl($l->android_pic);
			$subItem['material_pic']    = [];

			$subItem['material_num']    = Material::where(['sub_type'=>$l->name,'is_pass'=>1,'device'=>$l->device])->count();
			$subItem['mids']            = Material::where(['sub_type'=>$l->name,'is_pass'=>1,'device'=>$l->device])->lists('id');
			$materialPics = Material::where(['sub_type'=>$l->name,'is_pass'=>1,'device'=>$l->device])->orderby('score','desc')->limit(3)->get();
			if ($materialPics)
			{
				foreach ($materialPics as $mv)
				{
					$subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic);
				}
			}
			return $subItem;
		}
		$type['uniqueID'] = $l->id;
		$type['name']           = $l->name;
		$type['searchName']     = $l->name;
		$type['type_desc']      = $l->desc;
		$type['type_color']     = $l->color1;
		$type['type_color2']    = $l->color2;
		$type['is_new'] = 0;
		if ($l->device == 2){
			$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'type'=>$l->name])->first();
		}else{
			$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'type'=>$l->name,'device'=>$l->device])->first();
		}

		if (!is_null($newMaterial))
		{
			$type['is_new'] = 1;
		}
		$type['is_new_color']   = $l->is_new_color;
		$type['font']           = $l->font;
		$type['type_created']   = $l->created_at->format('Y-m-d H:i:s');
		$type['type_img']       = empty($l->pic)?'':FileTool::getImageUrl($l->pic);
		$type['type_img_new']   = empty($l->small_pic_new)?$type['type_img']:FileTool::getImageUrl($l->small_pic_new);
		$type['type_android_img']       = empty($l->android_pic)?$type['type_img']:FileTool::getImageUrl($l->android_pic);
		$type['ios_pic_frame']  = empty($l->ios_pic_frame)?'':FileTool::getImageUrl($l->ios_pic_frame);
		$type['subtypes']       = [];
		$subquery = self::onWriteConnection()->selectRaw('*')->where('parent_id',$l->id)->where('weight','>=','0');
		if ($l->device == 1){
			$subquery ->where('device','!=',0)->where('version','<',20000000);
		}elseif($l->device == 0){
			$subquery ->where('device','!=',1)->where('version','<',90909);
		}
		$subs = $subquery ->orderBy('weight', 'desc')->get();
		foreach ($subs as $subvalue)
		{
			$subItem['uniqueID'] = $subvalue->id;
			$subItem['order'] = $subvalue->is_new;
			$subItem['name']            = $subvalue->name;
			$subItem['searchName']      = $subvalue->name;
			$subItem['type_desc']       = $subvalue->desc;
			$subItem['type_color']      = $subvalue->color1;
			$subItem['type_color2']     = $subvalue->color2;
			$subItem['type_bg_color']   = $subvalue->bg_color;
			$subItem['packagepay']      = $subvalue->packagepay;
			$subItem['is_vip']          = $subvalue->is_vip;
			$subItem['is_new']          = 0;
			if ($l->device == 2){
				$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$subvalue->name])->first();
			}else{
				$newMaterial = Material::where(['is_new'=>1,'is_pass'=>1,'sub_type'=>$subvalue->name,'device'=>$subvalue->device])->first();
			}
			if (!is_null($newMaterial))
			{
				$subItem['is_new'] = 1;
			}
			$subItem['is_new_color']    = $subvalue->is_new_color;
			$subItem['font']            = $subvalue->font;
			$subItem['type_created']    = $subvalue->created_at->format('Y-m-d H:i:s');
			$subItem['ios_pic_frame']   = empty($subvalue->ios_pic_frame)?'':FileTool::getImageUrl($subvalue->ios_pic_frame);
			$subItem['type_img']        = empty($subvalue->pic)?'':FileTool::getImageUrl($subvalue->pic);
			$subItem['type_img_new']    = empty($subvalue->small_pic_new)?$subItem['type_img']:FileTool::getImageUrl($subvalue->small_pic_new);
			$subItem['type_android_img']        = empty($subvalue->android_pic)?$subItem['type_img']:FileTool::getImageUrl($subvalue->android_pic);
			$subItem['material_pic']    = [];

			$subItem['material_num']    = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>$subvalue->device])->count();
			$subItem['mids']            = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>$subvalue->device])->lists('id');
			$materialPics = Material::where(['sub_type'=>$subvalue->name,'is_pass'=>1,'device'=>$subvalue->device])->orderby('score','desc')->limit(3)->get();
			if ($materialPics)
			{
				foreach ($materialPics as $mv)
				{
					$subItem['material_pic'][]  = empty($mv->small_pic)?'':FileTool::getImageUrl($mv->small_pic);
				}
			}
			$type['subtypes'][]         = $subItem;
		}
		return $type;
	}

	public static function getTypeByName($name)
	{
		$list = self::onWriteConnection()->where('name',$name)->get();
		if (is_null($list)){
			return [];
		}
		foreach ($list as $l)
		{
			$type['uniqueID'] = $l->id;
			$type['name']           = $l->name;
			$type['searchName']     = $l->name;
			$type['type_desc']      = $l->desc;
			$type['type_color']     = $l->color1;
			$type['type_color2']    = $l->color2;
			$type['is_new_color']   = $l->is_new_color;
			$type['font']           = $l->font;
			$type['type_created']   = $l->created_at->format('Y-m-d H:i:s');
			$type['type_img']       = empty($l->pic)?'':FileTool::getImageUrl($l->pic);
			$type['type_img_new']   = empty($l->small_pic_new)?$type['type_img']:FileTool::getImageUrl($l->small_pic_new);
			$type['type_android_img']       = empty($l->android_pic)?$type['type_img']:FileTool::getImageUrl($l->android_pic);
			$type['ios_pic_frame']  = empty($l->ios_pic_frame)?'':FileTool::getImageUrl($l->ios_pic_frame);
			return $type;
		}

	}

	public static function updateMaterialType($model,$old_name)
	{
		switch ($model->device)
		{
			case 1:
				if ($model->parent_id == 0)
				{
					$list = Material::where('device',1)->where('type',$old_name)->get();
				}else{
					$list = Material::where('device',1)->where('sub_type',$old_name)->get();
				}
				break;
			case 0:
				if ($model->parent_id == 0)
				{
					$list = Material::where('device',0)->where('type',$old_name)->get();
				}else{
					$list = Material::where('device',0)->where('sub_type',$old_name)->get();
				}
				break;
			default:
				if ($model->parent_id == 0)
				{
					$list = Material::where('type',$old_name)->get();
				}else{
					$list = Material::where('sub_type',$old_name)->get();
				}
		}
		foreach ($list as $l)
		{
			if ($model->parent_id == 0)
			{
				$l->type = $model->name;
			}else{
				$l->sub_type = $model->name;
			}
			$l->save();
		}

	}
    

}
