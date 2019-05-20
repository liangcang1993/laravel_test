<?php

namespace App\Models;
use App\Services\FileService;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Recommend;

use FileTool;
use Storage;
use DB;

class Banner extends BaseModel
{
    use SoftDeletes;
    protected $table = 'manly_banner';


    public static function getPageQuery($filter = array())
    {
        $query = self::onWriteConnection()->selectRaw('*');
        if(isset($filter['name']) && trim($filter['name']) != '') {
            $query->where('name', 'like',  '%'.$filter['name'].'%');
        }
        if(isset($filter['position']) && trim($filter['position']) != '') {
            $query->where('position', '=', $filter['position']);
        }
        if(isset($filter['dev'])) {
            $query->where('device', '=', $filter['dev']);
        }
        if (isset($filter['sort']) && trim($filter['sort']) != '') {
            $d = explode(' ', $filter['sort']);
            $query->orderBy($d[0], $d[1]);
        }else{
            $query->orderBy('id', 'desc');
        }
        return $query->paginate(10);
    }
    // public static function getBanner($params){
    //     $model = self::where('app',$params['app'])->where('is_operat',0);
    //     if ($params['type']){
    //         $model->where('type', $params['type']);
    //     }
    //     if ('bodyApp'==$params['app']){
    //         $model->where('sex', $params['sex']);
    //     }
    //     $res = $model->first();
    //     $data = [];
    //     return $data;

    // }

    public static function getBannerList($params)
    {
		$dev = 0;
		$params['clt'] == 'android' && $dev = 1;
        $datas = [];
        $list = Banner::onWriteConnection()->where('device',$dev)->where('weight', '>=', 0)->where('version', '<=', $params['version'])->orderBy('weight', 'desc')->get();
        foreach ($list as $key => $l) 
        {
            $data = [];
            $data['title'] = $l->title;
            $key = 'title_' . $params['language'];
            if($params['language'] != 'en')
            {
                $data['title'] = $l->$key;
            }
            if(!empty($l->$key))
            {
                $data['title'] = $l->$key;
            }
            
            if(empty($l->large_pic))
            {
                continue;
            }
            $data['largerPic']  = FileTool::getImageUrl($l->large_pic, $params['china']);
            $data['id']         = $l->id;
            $data['color']      = $l->color;
            $data['name']       = $l->name;
            $data['date']       = $l->recommend_date;
            $data['version']    = $l->version;
            $data['android_mid']= $l->android_mid;
            $data['ios_mid']    = $l->ios_mid;
            $datas[]            = $data;
            unset($data);

        }
        return $datas;
    }
   

    public function smallPicUrl(){
        return self::getRealUrl($this->small_pic);
    }
    public function largePicUrl(){
        return self::getRealUrl($this->large_pic);
    }
    

    
}
