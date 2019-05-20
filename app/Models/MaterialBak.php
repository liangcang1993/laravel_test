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

class MaterialBak extends BaseModel
{
    use SoftDeletes;
    protected $table = 'manly_material_bak';

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
    public static function getOne($params,$id){

        $query = self::onWriteConnection()->where('id',$id)->where(['manly_use'=>1,'android_use'=>1])->first();

        if ($query){
            if ($params['app'] == 'Meepo' || $params['app'] == 'Meepo2') 
            {
                return $query->getMeepoJson($params);
            }else{
                return $query->getJson($params);
            }
            
        }else{
            return array();
        }
    }
       
    public static function getMaterials($params,$id=null)
    {

        $list = self::onWriteConnection()->selectRaw('*');
        if (is_null($id) == false) 
        {
            # code...
            !empty($params['id']) &&  $list->where('id', $params['id']);
            return $list->first()->getJson($params,1);

        }
        !empty($params['type']) &&  $list->where('type', $params['type']);
        !empty($params['sub_type']) &&  $list->where('sub_type', $params['sub_type']);
        if (90909>$params['version'])
        {
            $list->where('is_pass', '1');
        }
        !empty($params['version']) &&  $list->where('version', '<=',$params['version']);
        
        $list = $list->paginate($params['page_size']);
        $datas = [];
        foreach ($list as $l) 
        {
            $data = $l->getJson($params);
            $datas[] = $data;
        }
        $d['list'] = $datas;
        $d['lastPage'] = $list->lastPage() == $params['page'] ? 1 : 0;
        return $d;
    }

    public function getJson($params,$item=null)
    {
        $data['uniqueID']           = $this->id;
        $data['isVip']              = $this->is_vip;
        $data['thumbnailUrl']       = FileTool::getImageUrl($this->small_pic, $params['china']);      //缩略图
        $data['thumbnailSmallUrl']  = FileTool::getImageUrl($this->icon, $params['china']);      //icon
        $data['originalUrl']        = FileTool::getImageUrl($this->large_pic, $params['china']);       //素材
        $data['type']               = $this->type;
        $data['subType']            = $this->sub_type;
        $data['disPlayName']        = $this->display_name;
        $data['disPlayColor']       = $this->display_color;
        $data['isNew']              = $this->is_new;
        $data['unique_name']        = $this->unique_name;
        $data['en_material']        = empty($this->en_material)?'':FileTool::getImageUrl($this->en_material, $params['china']);
        $data['desc']               = $this->desc;
        // if ($params['app'] == 'jigsaw') 
        // {
            $data['puzzle_small_pic']   = $data['thumbnailUrl'];
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
            $data['puzzle_704_coin']     = $this->level_704_coin;
            $data['puzzle_48_coin']     = $this->level_48_coin;
            $data['puzzle_108_coin']    = $this->level_108_coin;
            $data['puzzle_252_coin']    = $this->level_252_coin;
            $data['puzzle_432_coin']    = $this->level_432_coin;
            
        // }
        


        return $data;
    }

    

    public static function getJigBannerNew($params)
    {
        
        if ($params['clt'] == 'android' && $params['android_mid']) 
        {
            $params['id'] = $params['android_mid'];
            $info = Material::getMaterials($params,$params['android_mid']);
        }else{
            $params['id'] = $params['ios_mid'];
            $info = Material::getMaterials($params,$params['ios_mid']);
        }
      
        return $info;
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
    
    public static function compress($material, $force = 0, $system){
        $name = $system . '_small_pic';
        $large_path = $material->$name;
        $ext = explode('.', $large_path);
        $earr = ['jpg','png'];
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
            $to =  'small_' . $from;

            //压缩gif
            $cmd = 'convert ' . $from . ' -resize 150 ' . $to ;
            $cmd = 'cd ' . storage_path() . '/app/tmpmaterial/;'  . $cmd;
            // echo $cmd;
            $r = exec($cmd);
            // Log::info($r);

            $small_flle = Storage::disk('local')->get('tmpmaterial/'. $to);
            $size = strlen($small_flle);
            if($size >10){
                $smallpath = 'small_' . $large_path;
                $res = Storage::put($smallpath, $small_flle);

                if($res){
                    $name = $system . '_icon';
                    $material->$name = $smallpath;
                    $material->save();
                }
             }

            $cmd = 'cd ' . storage_path() . '/app/tmpmaterial/; rm -rf ./'. $from . ' ./' .$to;
            exec($cmd);

        }

    }

    public static function getSmallPic($material,$tag,$size)
    {
        $name = 'large_pic';
        $large_path = $material->$name;
        $ext = explode('.', $large_path);
        $earr = ['jpg','png'];
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
            $cmd = 'convert ' . $from . ' -resize '.$size.'! ' . $to ;
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
            // if (file_exists(storage_path() . '/app/material/' . $from)) 
            // {
            //     Storage::disk('local')->delete('material/' . $from);
            // }

        }

    }

   
    public static function getId($unique_name){
        // $res = self::where('unique_name',$unique_name)->first();
        
        $res = self::onWriteConnection()->where('unique_name',$unique_name)->where(['manly_use'=>1,'android_use'=>1])->first();
        
        if ($res){
            return $res->id;
        }else{
            return '';
        }
    }
    
    public static function getEncrypt($img){
        $url = self::getRealUrl($img);
        $ext = explode('.',$img);
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $contents = file_get_contents($url,false, stream_context_create($arrContextOptions));
        $path = Storage::disk('local')->put('material/file.jpg', $contents);
        $path = storage_path('app/material/file.jpg');
        $image = system("/usr/local/encrypt/alphaencrypt 0 $path");
        $path = FileService::uploadByFileEncrypt($image, 'encrypt',$ext[1]);
        return $path;
    }

}
