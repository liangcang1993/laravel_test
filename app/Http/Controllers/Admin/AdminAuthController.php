<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\AdminUser; 
use App\Models\AdminAuth;
use App\Models\AppMod;
use App\Models\Utility;

use Validator;
use Input;
use Session;
use DB;
use Config;
use Auth;
use PDO;
use Redis;

class AdminAuthController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        return $this->render(
            'admin.admin_auth_index',
            [

            ]
        );
    }

    //首页ajax
    public function getAuth()
	{
		DB::setFetchMode(PDO::FETCH_ASSOC);
		$auths = DB::table('admin_auth')->get();
		foreach ($auths as $key => &$value)
		{
			$value['permission'] = $value['controller'].'/'.$value['action'];
		}
		return json_encode($auths,1);
	}
	//删除
	public function authDel()
	{
		$id = Input::get('id');
		return AdminAuth::where('id',$id)->orWhere('pid',$id)->delete();
	}

	//新增
	public function authAdd(Request $request)
	{
		$datas = $request->all();
		if(isset($datas['id'])){unset($datas['id']);}
		$res = AdminAuth::insert($datas);
		if ($res)
		{
			return redirect('admin/admin_auth')->with('status', '添加成功!');
		}else{
			return redirect('admin/admin_auth')->with('status', '添加失败!');
		}
	}

	//update
	public function authUpdateInfo(Request $request)
	{
		$id = Input::get('id');

		$res = AdminAuth::where('id',$id)->first();
		return json_encode($res);
	}

	public function authUpdate(Request $request)
	{
		$datas = $request->all();

		$res = AdminAuth::where('id',$datas['id'])->update($datas);
		if ($res)
		{
			Redis::del('RoleAuthArr');
			Redis::del('SideBar');
			return redirect('admin/admin_auth')->with('status', '更新成功!');
		}else{
			return redirect('admin/admin_auth')->with('status', '更新失败!');
		}
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return $this->render(
            'admin.admin_auth_create',
            [
            ]
        );
    }


//    public function getTrees($categorys,$catId=0){
//        $subs=array();
//        foreach($categorys as $item){
//            if($item['pid']==$catId){
//                $subs[]=$item;
//                $subs=array_merge($subs,$this->getTrees($categorys,$item['id']));
//
//            }
//
//        }
//        return $subs;
//    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  request  $request
     * @return Response
     */
    public function store(Request $request)
    {
		DB::setFetchMode(PDO::FETCH_ASSOC);
        $name       = trim(Input::get('name'));
        $controller = trim(Input::get('controller'));
        $action     = trim(Input::get('action'));
        $uri        = trim(Input::get('uri',''));
        $status     = Input::get('status',0);
        $weight     = Input::get('weight',0);
        $pid        = Input::get('pid',0);

        $insertid = DB::table('admin_auth')->insertGetId([
                                                            'name'          =>$name,
                                                            'controller'    =>$controller,
                                                            'action'        =>$action,
                                                            'status'        =>$status,
                                                            'created_at'    =>date('Y-m-d H:i:s',time()),
                                                            'uri'           =>$uri,
                                                            'weight'        =>$weight,
                                                            'pid'           =>$pid
                                                        ]);
        if ($insertid)
        {
            return redirect('admin/admin_auth')->with('status', '添加成功!');
        }else{
            return redirect('admin/admin_auth')->with('status', '添加失败!');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
//    public function edit($id)
//    {
//        $app    = Session::get('app');
//        $app_id = AppMod::where('name',$app)->first();
//        $auth   = AdminAuth::find($id);
//        //获取该app是否存在这个权限
//        $is_has = DB::table('app_auth')->where(['app_id'=>$app_id->id,'auth_id'=>$id])->first();
//        if (is_null($is_has))
//        {
//            $is_has = 0;
//        }else{
//            $is_has = 1;
//        }
//
//        if (is_null($auth))
//        {
//            abort(404);
//        }
//
//        $auths = DB::table('admin_auth')->get();
//        $list = $this->getTrees($auths,0);
//        // dd($list);
//        foreach ($list as $key => &$value)
//        {
//            if ($value['level'] != 0)
//            {
//                $sign = '';
//                $space = '';
//                for ($i=0; $i <$value['level'] ; $i++)
//                {
//                    $space .= '&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp';
//                    $sign .= '------';
//                }
//                $space .= '|';
//                $value['name'] = $space.$sign.$value['name'];
//            }
//        }
//        return $this->render(
//            'admin.admin_auth_edit',
//            [
//                'user'      => $auth,
//                'is_has'    => $is_has,
//                'app_id'    => $app_id->id,
//                'auths'     => json_encode($list)
//            ]
//        );
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param  request  $request
     * @param  int      $id
     * @return Response
     */
//    public function update(Request $request, $id)
//    {
//
//        $auth = AdminAuth::find($id);
//
//        $app_id         = Input::get('app_id');
//        $name           = trim(Input::get('name'));
//        $pid            = trim(Input::get('pid'));
//        $controller     = trim(Input::get('controller'));
//        $action         = trim(Input::get('action'));
//        $uri            = trim(Input::get('uri'));
//        $weight         = trim(Input::get('weight'));
//        $status         = trim(Input::get('status'));
//        $is_has         = Input::get('is_has');
//        if ($pid != $auth->pid)
//        {
//            $level          = 0;
//            $path           = '';
//            if ($pid != 0)
//            {
//                $pauth = DB::table('admin_auth')->find($pid);
//                if ($pauth['pid'] != 0)
//                {
//                    $path = $pauth['path'];
//                    $level = ++$pauth['level'];
//                }else{
//                    $path = $pauth['id'];
//                }
//                $path .= ','.$id;
//            }
//        }else{
//            $level   = $auth->level;
//            $path    = $auth->path;
//        }
//
//        $appauth = DB::table('app_auth')->where(['app_id'=>$app_id,'auth_id'=>$id])->first();
//        if (!is_null($appauth) && $is_has==0)
//        {
//            DB::table('app_auth')->where('id',$appauth['id'])->delete();
//        }
//
//        if (is_null($appauth) && $is_has==1)
//        {
//            DB::table('app_auth')->insert(['app_id'=>$app_id,'auth_id'=>$id]);
//        }
//
//        $res = DB::table("admin_auth")->where('id',$id)->update([
//                                                                        'name'      =>$name,
//                                                                        'pid'       =>$pid,
//                                                                        'controller'=>$controller,
//                                                                        'action'    =>$action,
//                                                                        'uri'       =>$uri,
//                                                                        'level'     =>$level,
//                                                                        'path'      =>$path,
//                                                                        'status'    =>$status,
//                                                                        'weight'    =>$weight,
//                                                                        'updated_at'    =>date('Y-m-d H:i:s',time())
//                                                                    ]);
//        if ($res) {
//			Redis::del('RoleAuthArr');
//			Redis::del('SideBar');
//            return redirect('admin/admin_auth?app_id='.$app_id)->with('status', '更新成功!');
//        } else {
//            return redirect('admin/admin_auth/' . $id . '/edit')->withErrors("更新失败!")->withInput();
//        }
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
//    public function destroy($id)
//    {
//        $res = $this->delAll($id);
//        //从角色表里删除
//        $roles = DB::table("admin_role")->get();
//        foreach ($roles as $key => $value)
//        {
//            $sign = 0;
//            $roleaid = explode(',', $value['authids']);
//            foreach ($roleaid as $ke => &$val)
//            {
//                if (in_array($val, $res))
//                {
//                    unset($roleaid[$ke]);
//                    $sign = 1;
//                }
//            }
//            if ($sign)
//            {
//                $new = implode(',', $roleaid);
//                DB::table("admin_role")->where('id',$value['id'])->update(['authids'=>$new]);
//            }
//
//        }
//		Redis::del('RoleAuthArr');
//		Redis::del('SideBar');
//        return redirect('admin/admin_auth')->with('status', '删除成功!');
//
//    }

//    public function delAll($id)
//    {
//        $apauids = [];
//        $app    = Session::get('app');
//        $app_id = AppMod::where('name',$app)->first();
//        $appauth = DB::table("app_auth")->where(['auth_id'=>$id,'app_id'=>$app_id->id])->first();
//        if ($appauth)
//        {
//            $apauids[] = $appauth['id'];
//        }
//        DB::table("app_auth")->where(['auth_id'=>$id,'app_id'=>$app_id->id])->delete();
//
//        $delids = DB::table("admin_auth")->where('pid',$id)->get();
//        // dd($delids);
//        foreach ($delids as $key => $value)
//        {
//
//            //从app_auth表中删除指定权限
//            $appauth = DB::table("app_auth")->where(['auth_id'=>$value['id'],'app_id'=>$app_id->id])->first();
//            if ($appauth)
//            {
//                $apauids[] = $appauth['id'];
//            }
//
//            DB::table("app_auth")->where(['auth_id'=>$value['id'],'app_id'=>$app_id->id])->delete();
//
//            array_merge($apauids,$this->delAll($value['id']));
//
//        }
//        return $apauids;
//    }

    function objectToArray($object) {
        //先编码成json字符串，再解码成数组
        return json_decode(json_encode($object), true);
    }

    
}
