<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Response;
use App\Http\Models\Sso;
use Log;
use Input;
use Session;
use Auth;
use DB;
use PDO;
use Redis;

abstract class AdminBaseController extends Controller
{
    public $sidebar;
    public $app_lists;
    public function __construct()
    {
        if (Auth::check()) {
            $is_super = Auth::user()->is_super;
            $user_id = Auth::user()->id;
        }
    }

     protected function getSideBar()
     {
         DB::setFetchMode(PDO::FETCH_ASSOC);
         $res = [];
         if (!empty(Auth::user()->role_id) && !Auth::user()->is_super)
         {
			 if (!Redis::hexists('SideBar' , Auth::user()->role_id))
			 {
				 $authid = DB::table('admin_role')->find(Auth::user()->role_id);
				 $aid = explode(',', $authid['authids']);
				 foreach ($aid as $key => $value)
				 {
					 $pri = DB::table('admin_auth')->find($value);
					 if ($pri['status'] == 1)
					 {
						 $res[] = $pri;
					 }

				 }
				 Redis::hset('SideBar' , Auth::user()->role_id,json_encode($res));
			 }
			 $res = json_decode(Redis::hget('SideBar' , Auth::user()->role_id),1);

         }elseif(Auth::user()->is_super){
             $res = DB::table('admin_auth')->where('status',1)->get();
         }
         $res = $this->getSubs($res);
         $sort = [];
         foreach($res as $item)
         {
             $sort[] = $item['weight'];
         }
         array_multisort($sort, SORT_DESC, $res);
         // dd($res);
         // dd($this->mergeUlTree($res));
         return $this->mergeUlTree($res);
     }

     public function mergeUlTree($array)
     {
         $str = '';
         if (empty($array)) {
         return $str;
         }
        
         foreach ($array as $k=>$v)
         {
            
             $str .=  "<li class=''>";
             if(empty($v['child']))
             {
                 $str .= "<a href='/admin/" . $v['uri'] . "'>";
                 $str .= "<span class='menu-text'> " . $v['name'] . "</span>";
                 $str .= "</a>";
                 $str .= "<b class='arrow'></b>";
                
             }else{

                 $str .= "<a  class='dropdown-toggle'>";
                 $str .= "<span class='menu-text'> " . $v['name'] . " </span>";
                 $str .= "<b class='arrow fa fa-angle-down'></b>";
                 $str .= "</a><b class='arrow'></b><b class='arrow'></b>";
                 $str .= "<ul class='submenu'>";
                 $str .= $this->mergeUlTree($v['child']);
                 $str .=  "</ul>";
             }
             $str .=  "</li >";
         }
         return $str;
     }


     public function getSubs($cate,$pid=0)
     {
         $arr = [];
         foreach ($cate as $key => $value)
         {
             if ($value['pid']== $pid)
             {
                 $value['child'] = $this->getSubs($cate,$value['id']);
                 $arr[] = $value;
             }
         }
         return $arr;
     }
    protected function render($view = null, $data = [])
    {
		$data['models']  = $this->getSideBar();
        return view($view, $data);
    }

    /**
     * 判断字符串是否为 Json 格式
     * 
     * @param  string     $data  Json 字符串
     * @param  bool       $assoc 是否返回关联数组。默认返回对象
     * 
     * @return bool|array 成功返回转换后的对象或数组，失败返回 false
     */
    public function isJson($data = '', $assoc = false) {
        $data = json_decode($data, $assoc);
        if ($data && (is_object($data)) || (is_array($data) && !empty(current($data)))) {
            return $data;
        }
        return false;
    }

    public  function getRealUrl($url, $china = null)
    {
        if(starts_with($url, './')){
            return Config::get('base.url') .'storeFile/' . substr($url, 2);
        }else{
            return FileTool::getUrl($url, $china);
        }
    }

}
