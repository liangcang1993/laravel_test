<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Config;
use FileTool;
use Redis;
use DB;
use Storage;
use Session;

class MaterialProduct extends BaseModel
{
	use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'material_product';

    public static function getPageQuery($filter = array())
    {
        $query = self::onWriteConnection()->selectRaw('*')->where('is_postcard', 0)->where('type', '!=', 'dailyQuote');

        if(isset($filter['name']) && trim($filter['name']) != '') {
            $query->where('name', 'like', '%'.$filter['name'].'%');
        }
        if(isset($filter['type']) && trim($filter['type']) != '') {
            $query->where('type', '=', $filter['type']);
        }
        if(isset($filter['id']) && trim($filter['id']) != '') {
            $query->where('id', '=', $filter['id']);
        }
        if(isset($filter['material_type']) && trim($filter['material_type']) != '') {
            $query->where('material_type', '=', $filter['material_type']);
        }
        if(isset($filter['sex']) && trim($filter['sex']) != '') {
            $query->where('sex', '=', $filter['sex']);
        }
        if(isset($filter['skin_color']) && trim($filter['skin_color']) != '') {
            $query->where('skin_color', '=', $filter['skin_color']);
        }
        $apps = self::getApps();
        $app = $filter['app'];
        if (!in_array($app, $apps)) 
        {
            $app = 'photable';
            Session::set('app', $app);
        }
        $query->where($app . '_use', '=', '1');

        if(isset($filter['is_vip']) && trim($filter['is_vip']) != '') {
         $query->where($app . '_is_vip', '=', $filter['is_vip']);
     }

     if(isset($filter['sort']) && trim($filter['sort']) != '') {
        if(starts_with($filter['sort'], 'used') || starts_with($filter['sort'], 'download')
           || starts_with($filter['sort'], 'weight')|| starts_with($filter['sort'], 'score')
           || starts_with($filter['sort'], 'use_rate')){
            $filter['sort'] = $app . '_' . $filter['sort'];
    }
    $d = explode(' ', $filter['sort']);
    $query->orderBy($d[0], $d[1]);
}else{
    $query->orderBy('id', 'desc');
}
return $query->paginate(10);

}

public function getJson($version = 0, $app = 'photable', $china = 0, $clt = null, $language = 'en'){
    $data['previewBackgroundColor'] = $this->previewBackgroundColor;

    $data['IAPPriceFloat'] = $this->IAPPriceFloat;
    $data['bundleId'] = $this->bundleId;
    $data['IAPItemId'] = $this->IAPItemId;
    $data['id'] = $this->id;
    $data['type'] = $this->type;
    $data['payitems'] = $this->payitems;
    $data['displayName'] = $this->name;

    $key = 'name_' . $language;
    if(!empty($l->$key)){
        $data['displayName'] = $l->$key;
    } 

        //审核新iap
        // dd($version);
        // if($version == '20406' && ($data['payitems'] == '4#8' || $data['payitems'] == '4')){
        //     $data['payitems'] = '9#4#8';
        // }
    $data['icon'] = self::getRealUrl($this->icon, $china);
    empty($this->binary_data) && $this->binary_data = $this->binary_data_new;
    $data['binaryData'] = self::getRealUrl($this->binary_data, $china);
    if (10700>$version){
        // if($app == 'photable' && $version >= 10003 && !empty($this->binary_data_new)){
        //     $data['binaryData'] = self::getRealUrl($this->binary_data_new, $china);
        // }else{
        //     $data['binaryData'] = self::getRealUrl($this->binary_data, $china);

        // }
        $data['binaryData'] = self::getRealUrl($this->binary_data, $china);
    }else{
        $data['binaryData'] = self::getRealUrl($this->en_material, $china);
        if (empty($data['binaryData'])) 
        {
            $data['binaryData'] = self::getRealUrl($this->binary_data, $china);
        }
        if ($clt =='android') 
        {
            $data['binaryData'] = self::getRealUrl($this->binary_data, $china);
        }
    }

    $data['mainCover']['url'] = self::getRealUrl($this->main_cover, $china);
    $data['mainCover']['width'] = $this->main_cover_width;
    $data['mainCover']['height'] = $this->main_cover_height;
    $data['squareMainCover']['url'] = self::getRealUrl($this->android_main_cover, $china);
    $data['squareMainCover']['width'] = $this->android_mcover_width;
    $data['squareMainCover']['height'] = $this->android_mcover_height;
    $data['longMainCover']['url'] = self::getRealUrl($this->main_cover, $china);
    $data['longMainCover']['width'] = $this->main_cover_width;
    $data['longMainCover']['height'] = $this->main_cover_height;
    $data['iconValue'] = $this->icon;
    $data['detailmages'] = $this->getDetailMages($china);
    $data['detailDescription'] = $this->detailDescription;
    $data['isNew'] = $this->is_new;
    $name = $app . '_is_vip';
    $data['isVip'] = $this->$name;
    $data['colorful'] = $this->colorful;
    $data['unlockType'] = $this->unlock_type;
    $data['videoUrl'] = $this->video_url;
    $data['ver'] = $this->ver;
    $data['blendMode'] = $this->blendMode;
    $data['sloganBgColor'] = $this->sloganBgColor;

    $data['marketType'] = $this->market_type;
    $data['materialType'] = $this->material_type;
    if ($version < 10400 && $data['materialType'] == 3){
        $data['materialType'] = 1;
    }
    $data['marketUrl'] = $this->market_url;
    $data['isLimitedFree'] = $this->is_limited_free;

    $data['payInfo'] = $this->getPayinfo($version);
    return $data;
}

public function getDetailMages($china = 0){
    $res = [];
    $list = MaterialPicitem::where('material_id', '=', $this->id)->where('type', 0)->orderBy('weight', 'desc')->get();
    foreach ($list as $key => $value) {
        $pic['url'] = $this->getRealUrl($value->pic, $china);
        $pic['height'] = $value->height;
        $pic['width'] = $value->width;
        $res[] = $pic;
    }
    return $res;
}

public function getPayinfo($version = 0){
    $payinfo = [];
    $list = MaterialPayitem::where('material_id', $this->id)->get();
    foreach ($list as $l) {

        $payitem = PayItem::find($l->payitem_id);
            // if(!empty($version) && ($payitem->version > $version)){
            //     continue;
            // }
        if($payitem){
            $pay['name'] = $payitem->name;
            $pay['icon'] = FileTool::getUrl($payitem->icon);
            $pay['type'] = $payitem->type;
            $pay['IAPjson'] = json_decode($payitem->IAPjson, 1);
            $pay['iconColor'] = $payitem->icon_color;
            $pay['payitemId'] = $payitem->id;
                // $pay['IAPjson'] = $payitem->IAPjson;
            $payinfo[] = $pay;
        }
    }

        //  //审核新iap
        // if($version == '20406' && ($this->payitems == '4#8' || $this->payitems == '4')){
        //     $payitem = PayItem::find(9);
        //     // if(!empty($version) && ($payitem->version > $version)){
        //     //     continue;
        //     // }
        //     $pay['name'] = $payitem->name;
        //     $pay['icon'] = FileTool::getUrl($payitem->icon);
        //     $pay['type'] = $payitem->type;
        //     $pay['IAPjson'] = json_decode($payitem->IAPjson, 1);
        //     $pay['iconColor'] = $payitem->icon_color;
        //     $pay['payitemId'] = $payitem->id;
        //     // $pay['IAPjson'] = $payitem->IAPjson;
        //     $payinfo[] = $pay;
        // }
    return $payinfo;
}



public static function getResourceList($params){
    if($params['clt'] == 'ios'){
        $admin_app = 'poto';
        $params['app'] == 'pcp' && $admin_app = 'photable';
    }else{
        $admin_app = 'android';
    }

    if('featured' === $params['type']){
        $list = self::where($admin_app . '_is_featured', '1');
    }else{
        $list = self::where('type', $params['type']);    
    }

    if('filter' === $params['type']){
        if($params['clt'] == 'ios' && ($params['version'] >= 10500 || $params['test'] == '1')){

        }else{
            $list->where('material_type', '0');
        }
    } 

    $list->where('is_postcard', '0')->where($admin_app . '_use', '=', 1);


    // if (10700>$params['version']){
    //     $list->where('need_encrypt',0);
    // }

    if(!array_key_exists('test', $params) ||  $params['test'] == '0'){
        if ($params['version'] == 999999) 
        {
            $list->where('is_released', 1);
        }else{
            $list->where('status', '1')->where('is_released', 1);
        }
    }

        // if($params['app'] == 'pcp'){
        //     $list->where($admin_app . '_weight', '>=', 0);
        // }else{
        //     $list->where($admin_app . '_weight', '>=', 0)->where('version', '<=', $params['version']);
        // }
    $list->where($admin_app . '_weight', '>=', 0)->where('version', '<=', $params['version']);
    if(array_key_exists('order', $params) && $params['order'] == 'score'){
        $list->orderBy($admin_app. '_score', 'desc')->orderBy('id', 'desc');
    }else{
        $list->orderBy($admin_app. '_weight', 'desc')->orderBy('id', 'desc');
    }

    $list = $list->paginate($params['page_size']);

    $datas = [];
    foreach ($list as $l) {
      $data = $l->getJson($params['version'], $params['app'], $params['china'],$params['clt']);
      if($params['clt'] == 'android'){
        if($params['type'] === 'filter'){
            $data['icon'] = self::getRealUrl($l->android_icon, $params['china']);
        }
        $data['mainCover']['url'] = self::getRealUrl($l->android_main_cover, $params['china']);
        $data['mainCover']['width'] = $l->android_mcover_width;
        $data['mainCover']['height'] = $l->android_mcover_height;
    }

    $name  = $admin_app . '_is_vip'; 

    $data['isVip'] = $l->$name;
    $datas[] = $data;
}
$d['list'] = $datas;
$d['lastPage'] = $list->lastPage() == $params['page'] ? 1 : 0;
return $d;
}
public static function getResourceDetail($params){
   $l = self::where('id', $params['rid'])->first();
   if(empty($l)){
    return [];
}
$data = $l->getJson($params['version'], $params['app']);
if($params['clt'] == 'android'){
    $data['mainCover']['url'] = self::getRealUrl($l->android_main_cover, $params['china']);
    $data['mainCover']['width'] = $l->android_mcover_width;
    $data['mainCover']['height'] = $l->android_mcover_height;
    $data['isVip'] = $l->android_is_vip;
    $data['binaryData'] = self::getRealUrl($l->binary_data, $params['china']);
}
return $data;
}
// public static function getRealUrl($url, $china = null){
//     if(starts_with($url, './')){
//         return Config::get('base.url') .'storeFile/' . substr($url, 2);
//     }else{
//         return FileTool::getUrl($url, $china);
//     }
// }
public function mainCoverUrl($china = null){
    return self::getRealUrl($this->main_cover, $china);
}
public function iconUrl($china = null){
    return self::getRealUrl($this->icon, $china);
}
public function androidCoverUrl($china = null){
    return self::getRealUrl($this->android_main_cover, $china);
}
public function androidIconUrl($china = null){
    return self::getRealUrl($this->android_icon, $china);
}
public function binaryDataUrl($china = null){
    return self::getRealUrl($this->binary_data, $china);
}
public function binaryDataNewUrl($china = null){
    return self::getRealUrl($this->binary_data_new, $china);
}
public function fontFileUrl($china = null){
    return self::getRealUrl($this->font_file, $china);
}
public function en_material($china = null){
    return self::getRealUrl($this->en_material, $china);
}

public static function preImageZip($params){
    $params['page_size'] = 100;
    $params['page'] = 1;
    $data['payitem'] = PayItem::getList($params);

        // $data['postcard'] = Postcard::getList($params);
    $params['type'] = 'sticker';
    $data['sticker'] = MaterialProduct::getResourceList($params);
    $params['type'] = 'label';
    $data['label'] = MaterialProduct::getResourceList($params);
    $params['type'] = 'filter';
    $data['filter'] = MaterialProduct::getResourceList($params);
    $params['type'] = 'featured';
    $data['featured'] = MaterialProduct::getResourceList($params);
    foreach ($data['payitem']['list'] as $l) {
            // self::loadPic($l['picValue']);
        self::loadPic($l['iconValue']);
    }
        // foreach ($data['postcard']['list'] as $l) {
        //     self::loadPic($l['iconValue']);
        // }
    foreach ($data['sticker']['list'] as $l) {
        self::loadPic($l['iconValue']);
    }
    foreach ($data['label']['list'] as $l) {
        self::loadPic($l['iconValue']);
    }
    foreach ($data['filter']['list'] as $l) {
        self::loadPic($l['iconValue']);
    }
    foreach ($data['featured']['list'] as $l) {
        self::loadPic($l['iconValue']);
    }

    Redis::incr(RedisKey::PRE_PIC_VER);
    $ver = Redis::get(RedisKey::PRE_PIC_VER);

    $name = $ver . 'prePics.zip';
    $cmd = 'zip -r ' . $name . ' prePics';
    $cmd = 'cd ' . storage_path() . '/app/;'  . $cmd;
    $r = exec($cmd);
    Storage::put($name, Storage::disk('local')->get($name));

    $cmd = 'cd ' . storage_path() . '/app/; rm -rf prePics/* prePics.zip' ;
    exec($cmd);
}
private static function loadPic($file){
        // $name = substr($file, strpos($file, '/'), strlen($file));
    Storage::disk('local')->put('prePics/' . $file, Storage::get($file));

}

public static function cleanCache(){
    $keys = Redis::keys('api/*');
    foreach ($keys as  $value) {
     Redis::del($value);
 }
        Redis::del('faceymaterialsinfo');//更新banner中的redis
        Redis::del('Everlookmaterialsinfo');//更新banner中的redis
    }

    public function updatePicitem(){
        $items = MaterialPicitem::where('material_id', '=', $this->id)->get();
        foreach ($items as $model) {
            $model->pic = $this->main_cover;
            $model->width = $this->main_cover_width;
            $model->height = $this->main_cover_height;
            $model->material_id = $this->id;
            $model->save();
        }
        if(count($items) == 0){
            if($this->main_cover){
                $model = new MaterialPicitem;
                $model->pic = $this->main_cover;
                $model->width = $this->main_cover_width;
                $model->height = $this->main_cover_height;
                $model->material_id = $this->id;
                $model->save();
            }
        }
    }

    public function getShowedStr(){
        switch ($this->showed) {
            case 0:
            return '未展示';
            break;
            case 1:
            return '展示中';
            break;
            case 2:
            return '已展示';
            break;
            
            default:
                # code...
            break;
        }
    }
    public function getReleaseStr(){
        switch ($this->is_released) {
            case 0:
            return '未发布';
            break;
            case 1:
            return '已发布';
            break;
            
            default:
            return '';
            break;
        }
    }
    public function getShowTime(){
        if(!empty($this->show_time)){
            return $this->show_time;
        }else{
            return '';
        }
    }



    public static function sort(){
        // DB::update('update material_product set score=download_num_weak where is_free=1 and is_postcard=0');
        // DB::update('update material_product set score=' . $materialVipRate . '*download_num_weak where is_free=0 and is_postcard=0');
        $list = MaterialProduct::where('is_postcard', 0)->get();
        foreach ($list as $key => $l) {
            $apps = ['poto', 'photable','android'];
            foreach ($apps as $key => $app) {
                $materialVipRate = Redis::hget(RedisKey::LOCK_CONFIG, 'materialVipRate_' . $app); 
                $name = $app . '_download_num_weak';
                $download_num_weak = $l->$name;
                $name = $app . '_used_num_weak';
                $used_num_weak = $l->$name;
                $name = $app . '_is_vip';
                $is_vip = $l->$name;
                if($is_vip){
                    $name = $app . '_score';
                    $l->$name = ($download_num_weak + $used_num_weak) * $materialVipRate ;
                }else{
                    $name = $app . '_score';
                    $l->$name = $download_num_weak + $used_num_weak;
                }
                $l->save();
            }
        }

        $list = HomeList::where('position', 'bottom')->where('type','resource')->get();
        foreach ($list as $key => $l) {
            if($l->app == 'photable'){
                continue;
            }
            $model = MaterialProduct::find($l->out_id);
            if($model){
                $materialVipRate = Redis::hget(RedisKey::LOCK_CONFIG, 'materialVipRate_' . $l->app); 
                $name = $l->app . '_download_num_3days';
                $download_num_3days = $model->$name;
                
                $name = $l->app . '_is_vip';
                $is_vip = $model->$name;
                if($is_vip){
                    $l->weight = $download_num_3days * $materialVipRate;
                }else{
                    $l->weight = $download_num_3days;
                }
                $l->save();
            }
        }
        $list = HomeList::where('position', 'top')->where('type','resource')->get();
        foreach ($list as $key => $l) {
            if($l->app == 'photable'){
                continue;
            }
            $model = MaterialProduct::find($l->out_id);
            if($model){
                $materialVipRate = Redis::hget(RedisKey::LOCK_CONFIG, 'materialVipRate_' . $l->app); 
                $name = $l->app . '_download_num_day';
                $download_num_day = $model->$name;
                
                $name = $l->app . '_is_vip';
                $is_vip = $model->$name;
                if($is_vip){
                    $l->weight = $download_num_day * $materialVipRate;
                }else{
                    $l->weight = $download_num_day;
                }
                $l->save();
            }
        }
    }
    public static function limitedFree(){
        DB::update('update material_product set is_limited_free = 0 where is_postcard=0 and type!=\'sticker\'');
        $list = MaterialProduct::orderBy('photable_weight', 'desc')->get();
        $filter_num = 0;
        $font_num = 0;
        foreach ($list as $key => $model) {
            if($filter_num < 2 && $model->type == 'filter' && $model->photable_is_vip == 1){
                $model->is_limited_free = 1;
                $model->save();
                $filter_num ++ ;
            }
            if($font_num < 2 && $model->type == 'label' && $model->photable_is_vip == 1){
                $model->is_limited_free = 1;
                $model->save();
                $font_num ++ ;
            }
        }
    }
    public function getWeight($app){
        $name = $app . '_weight';
        return $this->$name;
    }
    public function getIsVip($app){
        $name = $app . '_is_vip';
        return $this->$name;
    }
    public function getIsFeatured($app){
        $name = $app . '_is_featured';
        return $this->$name;
    }
    public function getSort($app){
        $name = $app . '_sort';
        return $this->$name;
    }
    public function getUsedNum($app){
        $name = $app . '_used_num';
        return $this->$name;
    }
    public function getUsedNumDay($app){
        $name = $app . '_used_num_day';
        return $this->$name;
    }
    public function getUsedNumWeak($app){
        $name = $app . '_used_num_weak';
        return $this->$name;
    }
    public function getDownloadNum($app){
        $name = $app . '_download_num';
        return $this->$name;
    }
    public function getDownloadNumDay($app){
        $name = $app . '_download_num_day';
        return $this->$name;
    }
    public function getDownloadNumWeak($app){
        $name = $app . '_download_num_weak';
        return $this->$name;
    }
    public function getScore($app){
        $name = $app . '_score';
        return $this->$name;
    }

    public function getUseRate($app){
        $name = $app . '_use_rate';
        return $this->$name;
    }

    public function getPostUsedScore($app){
        $posterVipRate = Redis::hget(RedisKey::LOCK_CONFIG, 'posterVipRate_' . $app); 
        $used_num_day_var = $app . '_used_num_day';
        $is_vip_var = $app . '_is_vip';
        $score = $this->$is_vip_var? $this->$used_num_day_var * $posterVipRate : $this->$used_num_day_var;
        return $score;
    }

    public function getPostTimeScore($app){
        return $this->getScore($app) - $this->getPostUsedScore($app);
    }

    public static function isDeleted($params){
        $ids = $params['ids'];
        $ids = json_decode($ids, 1);
        $ids = $ids['ids'];
        $datas = [];
        if (!empty($ids)){
            foreach ($ids as $key => $id) {
                $r = Redis::get(RedisKey::POSTER_DEL . $params['admin_app'] . $id);
                if(!isset($r)){
                    $has = Postcard::where('id', $id)->where($params['admin_app']. '_use', 1)->first();
                    $r = $has? 0 : 1;
                    Redis::set(RedisKey::POSTER_DEL . $params['admin_app'] . $id, $r);
                }
                $data['id'] = $id;
                $data['deleted'] = $r;
                if($id == 200){
                    $data['deleted'] = 1;
                }
                $datas[] = $data;
            }
        }
        return $datas;
    }

    
    public function uploadFile($file){
        $path = storage_path('app/material');
        $name = $this->id . '.zip';
        $file->move($path , $name);
        $cmd = "cd $path ;unzip $name -d " . $this->id;
        $path .= '/' . $this->id;
        exec($cmd);
        $config['images'] = [];
        
        $this->need_encrypt = 1;
        /*
            label 每个.otf文件一个文件夹，包括otf文件和对应的缩略图，其余图片在最外层
            background和stroke的缩略图都在512中，1.png是素材，1c.png是缩略图
            其余类别素材都放在512文件夹，512中还包含封面图cover.jpg，缩略图都放在200文件夹，若没有200 文件夹，要把512中图片的素材裁剪就是缩略图，
            sticker比其他类别多ios_bottom，top.jpg图片文件
        */
        
        if(!file_exists($path . '/mainCover.jpg')){
            return 'main_cover 图片不存在';
        } 
        $main_cover = FileTool::uploadByFile($path . '/mainCover.jpg', 'material', 'jpg', 1); 
        $this->main_cover = $main_cover['path'];
        $this->main_cover_width = $main_cover['width'];
        $this->main_cover_height = $main_cover['height'];

        if(!file_exists($path . '/android_bottom.jpg')){
            return 'android_bottom 图片不存在';
        } 
        $android_main_cover = FileTool::uploadByFile($path . '/android_bottom.jpg', 'material', 'jpg', 1); 
        $this->android_main_cover = $android_main_cover['path'];
        $this->android_mcover_width = $android_main_cover['width'];
        $this->android_mcover_height = $android_main_cover['height'];

        $config['type']         = $this->type;
        $config['identity']     = "" . $this->id;
        $config['name']         = $this->name;
        $config['colorful']     = $this->colorful == '1'?  true : false;
        $config['cover']        = 'cover.jpg';
        $config['images']       = [];

        if ($this->type == 'sticker') 
        {
            if(!file_exists($path . '/ios_bottom.jpg')){
                return 'ios_bottom null';
            } 
            $ios_bottom = FileTool::uploadByFile($path . '/ios_bottom.jpg', 'material', 'jpg', 1); 
            $model = new HomeList;
            $model->pic = $ios_bottom['path'];
            $model->height = $ios_bottom['height'];
            $model->width = $ios_bottom['width'];
            $model->app = 'photable';
            $model->weight = -1;
            $model->type = 'resource';
            $model->out_id = $this->id;
            $model->position = 'bottom';
            $model->argument = '';
            $model->version = '0';
            $model->save();

            $model = $model->cloneTo('poto', 1);

            $model = $model->cloneTo('android', 1);
            $model->pic = $this->android_main_cover;
            $model->height = $this->android_mcover_height;
            $model->width = $this->android_mcover_width;
            $model->save();

            if(!file_exists($path . '/top.jpg')){
                return 'top null';
            } 
            $top = FileTool::uploadByFile($path . '/top.jpg', 'material', 'jpg', 1); 
            $model = $model->cloneTo('poto', 1);
            $model->position = 'top';
            $model->pic = $top['path'];
            $model->height = $top['height'];
            $model->width = $top['width'];

            $model->save();

            $model->cloneTo('android', 1);
            $model->cloneTo('photable', 1);

            $apps = self::getApps();
            foreach ($apps as $key => $app) {
                $name = $app . '_use';
                if(!$this->$name){
                    $list =HomeList::where('out_id', $this->id)->where('app', $app)->get();
                    foreach ($list as $key => $l) {
                        $l->delete();
                    }
                }
            }
        }
        $this->bundleId = $this->id;

        if ($this->type != 'label') 
        {
            if (!is_dir($path."/512/")) 
            {
                return '512文件夹不存在！';
            }
            if(!file_exists($path . '/512/cover.jpg'))
            {
                return 'icon 图片不存在';
            } 
            $this->icon = FileTool::uploadByFile($path . '/512/cover.jpg', 'material');
            $this->android_icon = $this->icon;

            $dirfile=scandir($path."/512/");
            foreach ($dirfile as $k => $cryptfv)
            {
                $arr = explode('.', $cryptfv);
                $arr[0] = trim($arr[0]);
                if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png'))
                {
                    rename($path."/512/$cryptfv",$path."/512/$arr[0].$arr[1]");
                }
                if (strpos($arr[0],' ')) 
                {
                    $arr[0] = str_replace(' ', '', $arr[0]);
                    rename($path."/512/$cryptfv",$path."/512/$arr[0].$arr[1]");
                }
            }
            $materialfile=scandir($path."/512/");
            foreach ($materialfile as $fv) 
            {
                
                $arr = explode('.', $fv);
                if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png')) 
                {
                    if (!is_dir($path."/200/") && $arr[0] != 'cover' && $this->type != 'background' && $this->type != 'stroke') 
                    {
                        $cmd = "convert $fv -interlace Plane -resize 200 small_$fv"  ;
                        $cmd = "cd $path/512;"  . $cmd;
                        $r = exec($cmd);
                        $res = FileTool::uploadByFile("$path/512/small_$fv", 'material', 'jpg', 1);
                        $model = new MaterialPicitem;
                        $model->pic = $res['path'];
                        $model->height = $res['height'];
                        $model->width = $res['width'];
                        // $model->weight = 100 - $arr[0];
                        $model->material_id = $this->id;
                        $model->save();
                        $cmd = "rm -rf $path/512/small_$fv";
                        exec($cmd);

                    }elseif($this->type == 'background' || $this->type == 'stroke'){
                        if (substr($arr[0], -1) == 'c') 
                        {
                            $cmd = "convert $fv -interlace Plane -resize 200 small_$fv"  ;
                            $cmd = "cd $path/512;"  . $cmd;
                            $r = exec($cmd);
                            $res = FileTool::uploadByFile("$path/512/small_$fv", 'material', 'jpg', 1);
                            $model = new MaterialPicitem;
                            $model->pic = $res['path'];
                            $model->height = $res['height'];
                            $model->width = $res['width'];
                            // $model->weight = 100 - $arr[0];
                            $model->material_id = $this->id;
                            $model->save();
                            $cmd = "rm -rf $path/512/small_$fv";
                            exec($cmd);
                        }
                    }
                    if ($arr[0] != 'cover') 
                    {
                        $arr[0] == 0 ? $arr[0] = 0 : $arr[0] -= 1;
                        $config['images'][$arr[0]] = $fv;
                    }
                    $filepath = storage_path("app/material/$this->id/512/$fv");
                }
            }
            ksort($config['images']);
            if ($this->type != 'background' && $this->type != 'stroke') 
            {
                Storage::disk('local')->put("material/". $this->id . "/512/config.json", json_encode($config));
            }
            // dd($config);
            $cmd = "cd $path/512;zip -r 512.zip ./";
            exec($cmd);
            if(count($config['images']) == 0){
                return 'images null';
            }
            // $this->binary_data = FileTool::uploadByFile($path . '/512/512.zip', 'material', 'zip');
            $bin = FileTool::uploadByFile($path . '/512/512.zip', 'material', 'zip',1);
            $this->binary_data = $bin['path'];
            $this->binary_data_size = $bin['size'];
            $cmd = "rm -rf $path/512/512.zip";
            exec($cmd);

            $materialfile=scandir($path."/512/");
            foreach ($materialfile as $k => $cryptfv) 
            {
                $arr = explode('.', $cryptfv);
                if ($arr[0] == 'cover') 
                {
                    continue;
                }
                if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png')) 
                {
                    $ext = $arr[1];
                    $filepath = storage_path("app/material/$this->id/512/$cryptfv");
                    system("/usr/local/encrypt/alphaencrypt 0 $filepath;rm -rf $filepath");
                    rename($path."/512/$arr[0].alecpt",$path."/512/$arr[0].".$ext."alecpt");
                }
            }
            
            $cmd = "cd $path/512;zip -r 512.zip ./";
            exec($cmd);
                
            // $this->en_material = FileTool::uploadByFile($path . '/512/512.zip', 'material', 'zip');
            $bin = FileTool::uploadByFile($path . '/512/512.zip', 'material', 'zip',1);
            $this->en_material = $bin['path'];
            $this->binary_data_size = $bin['size'];
            $cmd = "rm -rf $path/512/512.zip";
            exec($cmd);

            if (is_dir($path."/200/")) 
            {
                $dirfile=scandir($path."/200/");
                foreach ($dirfile as $k => $cryptfv)
                {
                    $arr = explode('.', $cryptfv);
                    $arr[0] = trim($arr[0]);
                    if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png'))
                    {
                        rename($path."/200/$cryptfv",$path."/200/$arr[0].$arr[1]");
                    }
                    if (strpos($arr[0],' ')) 
                    {
                        $arr[0] = str_replace(' ', '', $arr[0]);
                        rename($path."/200/$cryptfv",$path."/200/$arr[0].$arr[1]");
                    }
                }
                $thumbfile=scandir($path."/200/");
                foreach ($thumbfile as $picfv) 
                {
                    $arr = explode('.', $picfv);
                    if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png')) 
                    {
                        $res = FileTool::uploadByFile("$path/200/$picfv", 'material', $arr[1], 1);
                        $model = new MaterialPicitem;
                        $model->pic = $res['path'];
                        $model->height = $res['height'];
                        $model->width = $res['width'];
                        // $model->weight = 100 - $arr[0];
                        $model->material_id = $this->id;
                        $model->save();
                    }
                }
            }
            

        }elseif($this->type == 'label'){
            for ($i=1; $i <30 ; $i++) 
            { 
                if (is_dir($path."/$i/")) 
                {
                    $labelconf = [];
                    $labelfile=scandir($path."/$i/");
                    foreach ($labelfile as $fv) 
                    {
                        $arr = explode('.', $fv);
                        $arr[0] = trim($arr[0]);
                        if (isset($arr[1]) && $arr[1] == 'otf')
                        {
                            $labelconf['font']['size'] = '25';
                            $labelconf['font']['file'] = $fv;
                        }
                        if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png'))
                        {
                            $res = FileTool::uploadByFile("$path/$i/$fv", 'material', $arr[1], 1);
                            $model = new MaterialPicitem;
                            $model->pic = $res['path'];
                            $model->height = $res['height'];
                            $model->width = $res['width'];
                            // $model->weight = 100 - $arr[0];
                            $model->material_id = $this->id;
                            $model->save();
                        }
                        
                    }
                    Storage::disk('local')->put("material/". $this->id . "/$i/config.json", json_encode($labelconf));
                    
                }
            }
            if(!file_exists($path . '/cover.jpg')){
                return 'icon 图片不存在';
            } 
            $this->icon = FileTool::uploadByFile($path . '/cover.jpg', 'material');
            $this->android_icon = $this->icon;
            Storage::disk('local')->put("material/". $this->id . "/config.json", json_encode($config));
            $cmd = "cd $path/;zip -r 512.zip ./";
            exec($cmd);
            
            // $this->binary_data = FileTool::uploadByFile($path . '/512.zip', 'material', 'zip');
            $bin = FileTool::uploadByFile($path . '/512.zip', 'material', 'zip',1);
            $this->en_material = $bin['path'];
            $this->binary_data = $bin['path'];
            $this->binary_data_size = $bin['size'];
            $this->binary_data_size_new = $bin['size'];
            $cmd = "rm -rf $path/512.zip";
            exec($cmd);

        }
        
        
        
        $this->save();

        return 'success';
    }

    /*
        编辑binaryData/binaryDataNew时更新
    */
    public static function updateBinary($model,$file,$binarySign)
    {
        MaterialPicitem::where('material_id',$model->id)->delete();
        $path = storage_path('app/material');
        $name = 'updateBinary' . $model->id . '.zip';
        $file->move($path , $name);
        $cmd = "cd $path ;unzip $name -d updateBinary" . $model->id;
        exec($cmd);
        $cmd = "rm -rf $path/$name";
        exec($cmd);
        $path .= '/updateBinary' . $model->id;
        $config['type']         = $model->type;
        $config['identity']     = "" . $model->id;
        $config['name']         = $model->name;
        $config['colorful']     = $model->colorful == '1'?  true : false;
        $config['cover']        = 'cover.jpg';
        $config['images']       = [];
        if ($model->type != 'label') 
        {
            $materialfile=scandir($path);
            foreach ($materialfile as $fv) 
            {
                
                $arr = explode('.', $fv);
                if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png')) 
                {
                    if ($arr[0] != 'cover') 
                    {
                        $arr[0] == 0 ? $k = $arr[0]  : $k = $arr[0] - 1;
                        $config['images'][$k] = $fv;

                        $cmd = "convert $fv -interlace Plane -resize 200 small_$fv"  ;
                        $cmd = "cd $path;"  . $cmd;
                        $r = exec($cmd);
                        $res = FileTool::uploadByFile("$path/small_$fv", 'material', $arr[1], 1);
                        
                        $picmodel = new MaterialPicitem;
                        $picmodel->pic = $res['path'];
                        $picmodel->height = $res['height'];
                        $picmodel->width = $res['width'];
                        // $model->weight = 100 - $arr[0];
                        $picmodel->material_id = $model->id;
                        $picmodel->save();
                        $cmd = "rm -rf $path/small_$fv";
                        exec($cmd);

                    }
                }
            }
            ksort($config['images']);
            if ($model->type != 'background' && $model->type != 'stroke') 
            {
                Storage::disk('local')->put("material/updateBinary". $model->id . "/config.json", json_encode($config));
            }
            $cmd = "cd $path/;zip -r 512.zip ./";
            exec($cmd);
             
            $bin = FileTool::uploadByFile($path . '/512.zip', 'material', 'zip',1);
            if ($binarySign == 'new') 
            {
                $model->binary_data_new = $bin['path'];
                $model->binary_data_size_new = $bin['size'];
            }else{
                $model->binary_data = $bin['path'];
                $model->binary_data_size = $bin['size'];
            }
            $cmd = "rm -rf $path/512.zip";
            exec($cmd);

            
            

            foreach ($materialfile as $cryptfv) 
            {
                
                $arr = explode('.', $cryptfv);
                if ($arr[0] == 'cover') 
                {
                    continue;
                }
                if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png')) 
                {
                    $ext = $arr[1];
                    $filepath = storage_path("app/material/updateBinary"."$model->id/$cryptfv");
                    system("/usr/local/encrypt/alphaencrypt 0 $filepath;rm -rf $filepath");
                    rename($path."/$arr[0].alecpt",$path."/$arr[0].".$ext."alecpt");
                }

            }
            
            if ($model->type != 'background' && $model->type != 'stroke') 
            {
                Storage::disk('local')->put("material/updateBinary". $model->id . "/config.json", json_encode($config));
            }
            $cmd = "cd $path/;zip -r 512.zip ./";
            exec($cmd);
            
            $bin = FileTool::uploadByFile($path . '/512.zip', 'material', 'zip',1);
            $model->en_material = $bin['path'];
            $model->binary_data_size = $bin['size'];
            $model->save();
            $cmd = "rm -rf $path";
            exec($cmd);

        }else{

            for ($i=1; $i <30 ; $i++) 
            { 
                if (is_dir($path."/$i/")) 
                {
                    $labelconf = [];
                    $labelfile=scandir($path."/$i/");
                    foreach ($labelfile as $fv) 
                    {
                        $arr = explode('.', $fv);
                        $arr[0] = trim($arr[0]);
                        if (isset($arr[1]) && $arr[1] == 'otf')
                        {
                            $labelconf['font']['size'] = '25';
                            $labelconf['font']['file'] = $fv;
                        }
                        if (isset($arr[1]) && ($arr[1] == 'jpg' || $arr[1] == 'png'))
                        {
                            $res = FileTool::uploadByFile("$path/$i/$fv", 'material', $arr[1], 1);
                            $picmodel = new MaterialPicitem;
                            $picmodel->pic          = $res['path'];
                            $picmodel->height       = $res['height'];
                            $picmodel->width        = $res['width'];
                            // $picmodel->weight = 100 - $arr[0];
                            $picmodel->material_id  = $model->id;
                            $picmodel->save();
                        }
                        
                    }
                    Storage::disk('local')->put("material/updateBinary"."$model->id/$i/config.json", json_encode($labelconf));
                    
                }
            }
            
            $cmd = "cd $path/;zip -r 512.zip ./";
            exec($cmd);
            
            $bin = FileTool::uploadByFile($path . '/512.zip', 'material', 'zip',1);
            $model->en_material = $bin['path'];
            if ($binarySign == 'new') 
            {
                $model->binary_data_new = $bin['path'];
                $model->binary_data_size_new = $bin['size'];
            }else{
                $model->binary_data = $bin['path'];
                $model->binary_data_size = $bin['size'];
            }
            $model->binary_data_size = $bin['size'];
            $model->save();
            $cmd = "rm -rf $path";
            exec($cmd);
        }
        
    }

    public static function getApps(){
        return ['photable', 'poto', 'android'];
    }
    public static function getFirstResource($id){
//        if($params['clt'] == 'ios'){
//            $admin_app = 'poto';
//            $params['app'] == 'pcp' && $admin_app = 'photable';
//        }else{
//            $admin_app = 'android';
//        }

     $resource = self::where('id',$id)->first()->toarray();
     $data['id'] = $resource['id'];
     'postcard'==$resource['type']?$data['type'] = 'postcard':$data['type'] = 'resource';
     $data['title'] = $resource['name'];
     $data['created_at'] = strtotime($resource['created_at']);
     $data['material_type'] = $resource['type'];
     empty($resource['msg'])?$data['msg'] = '':$data['msg'] = $resource['msg'];
     return $data;
 }
 public static function getResourceOne($params){
    if($params['clt'] == 'ios'){
        $admin_app = 'poto';
        $params['app'] == 'pcp' && $admin_app = 'photable';
    }else{
        $admin_app = 'android';
    }
    $list = self::where('is_postcard', '0')->where($admin_app . '_use', '=', 1);

    if('featured' === $params['type']){
        $list = $list->where($admin_app . '_is_featured', '1');
    }else{
        $list = $list->where('type','<>','postcard');
    }

    if('filter' === $params['type']){
        if($params['clt'] == 'ios' && ($params['version'] >= 10500 || $params['test'] == '1')){

        }else{
            $list->where('material_type', '0');
        }
    }


    if(!array_key_exists('test', $params) ||  $params['test'] == '0'){
        $list->where('status', '1')->where('is_released', 1);
    }

    if($params['app'] == 'pcp'){
        $list->where($admin_app . '_weight', '>=', 0);
    }else{
        $list->where($admin_app . '_weight', '>=', 0)->where('version', '<=', $params['version']);
    }
    if(array_key_exists('order', $params) && $params['order'] == 'score'){
        $list->orderBy('id', 'desc');
    }else{
        $list->orderBy('id', 'desc');
    }

    $list = $list->first();
    $data = $list->getJson($params['version'], $params['app'], $params['china'], $params['clt']);
    return $data;
}
public static function getZipEncrypt($fileobject){
    $time = time();
    $path = storage_path('app/material/');
    $name = $time . '.zip';
    $ss = Storage::disk('local')->put("material/$name",$fileobject);
    $cmd = "cd $path ;unzip $name -d " . $time;
    exec($cmd);
//        $path .=  $time;
    $files = Storage::disk('local')->allFiles("material/$time/512");
    $config1 = [];
    for($i = 0; $i<=72; $i++){
        if(Storage::disk('local')->exists("material/". $time ."/512/" . $i .".png")){
            $filepath = storage_path("app/material/$time/512/").$i.'.png';
//                $c = system("sudo /usr/local/encrypt/alphaencrypt 0 $filepath;sudo rm -rf $filepath");
            system("/usr/local/encrypt/alphaencrypt 0 $filepath;rm -rf $filepath");
            $config1['images'][] = "$i.alecpt";
                // Storage::disk('local')->get($path . "512/$i.png");
        }
    }
    Storage::disk('local')->put("material/". $time . "/512/config.json", json_encode($config1));
//        $cmd = "cd $path/512;zip -r 512.zip ./";
//        exec($cmd);
    $cmd = "cd $path;rm -rf $name;zip -r $name ./$time/512";
    exec($cmd);
    $res = FileTool::uploadByFile($path.$name, 'potozip', 'zip');
    return $res;
}
}
