<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\AdminUser; 
use App\Models\AdminRole; 
use App\Http\Models\Utility;

use Validator;
use Input;
use Session;
use DB;
use Config;
use Auth;
use Redis;

class AdminUserController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $filter = [
            'keywords' => Input::get('keywords'),
        ];

        $users = AdminUser::getPageQuery($filter);

        return $this->render(
            'admin.admin_user_index', 
            [
                'users' => $users,
                'filter' => $filter,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $role = AdminRole::all();
        return $this->render(
            'admin.admin_user_create',
            [
                'role'=>$role
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // 验证表单
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'password' => 'required|min:6',
            'phone' => 'required|numeric|min:11',
            'email' => 'email',
        ]);

        if ($validator->fails()) {
            return redirect('admin/admin_user/create')->withErrors($validator)->withInput();
        }

        $name       = Input::get('name');
        $password   = Input::get('password');
        $phone      = Input::get('phone');
        $email      = Input::get('email');
        $is_admin   = Input::get('is_admin');
        $is_super   = Input::get('is_super'); 
        $role       = Input::get('role',0);
		empty($role) && $role=48;
        $isExist = AdminUser::isAccountExist($phone);
        if ($isExist)
            return redirect('admin/admin_user/create')->withErrors("手机号已存在，请勿重复添加！")->withInput();

        $user = AdminUser::create([
            'name'      => $name,
            'password'  => password_hash($password, PASSWORD_BCRYPT),
            'phone'     => $phone,
            'email'     => $email,
            'role_id'   => $role,
            'is_admin'  => is_null($is_admin) ? 0 : 1,
            'is_super'  => is_null($is_super) ? 0 : 1,
        ]);

  
        return redirect('admin/admin_user')->with('status', '添加成功!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = AdminUser::find($id);
        $role = AdminRole::all();
        if (is_null($user)) {
            abort(404);
        }

        
        
        return $this->render(
            'admin.admin_user_edit',
            [
                'user'      => $user,
                'role'      => $role
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  request  $request
     * @param  int      $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required', 
            'phone' => 'required|numeric|min:11',
            'email' => 'email',
            'password' => 'min:6'
        ]);

        if ($validator->fails()) {
            return redirect('admin/admin_user/' . $id . '/edit')->withErrors($validator)->withInput();
        }

        $user = AdminUser::find($id);

        if (is_null($user)) {
            return redirect('admin/admin_user')->withErrors("纪录不存在!")->withInput();
        }


        $user->name     = Input::get('name');
        $user->phone    = Input::get('phone');
        $user->email    = Input::get('email');
        $role           = Input::get('role',0);
		empty($role) && $role=48;
        $user->role_id  = $role;
        // $user->is_admin = is_null(Input::get('is_admin')) ? 0 : 1;
        // $user->is_super = is_null(Input::get('is_super')) ? 0 : 1;
        $password = Input::get('password');

        $isExist = DB::table("admin_user")->where('id', '<>',$id)->where('phone', $user->phone)->first();
        if ($isExist)
            return redirect('admin/admin_user/' . $id . '/edit')->withErrors("手机号已存在，请勿重复添加！")->withInput();

        if (!empty($password))
            $user->password  = password_hash($password, PASSWORD_BCRYPT);
  
        if ($user->save()) {
//            Redis::del('getAuths');
            return redirect('admin/admin_user')->with('status', '更新成功!');
        } else {
            return redirect('admin/admin_user/' . $id . '/edit')->withErrors("更新失败!")->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $user = AdminUser::find($id);
        if (is_null($user)) {
            abort(404);
        }

        $user->is_delete = 1;
        $user->save();

       
        return redirect('admin/admin_user')->with('status', '删除成功!');
    }
 
     

    public function updatePwd()
    {
        return $this->render('admin/update_pwd');
    }

    public function doUpdatePwd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldpwd' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('admin/updatepassword')->withErrors($validator)->withInput();
        }

        $uid = Auth::user()->id;
        $oldpwd = Input::get('oldpwd');
        $password = Input::get('password');

        $isok = AdminUser::checkPwd($uid, $oldpwd);
        if (!$isok)
            return redirect('admin/updatepassword')->withErrors("原密码输入有误！")->withInput();

        $ispwd = Utility::isPwd($password);
        if (!$ispwd) {
            return redirect('admin/updatepassword')->withErrors("密码 必须8个字符以上，并且包含数字、大小写字母等字符")->withInput();
        }

        AdminUser::updatePwd($uid, $password);

        return redirect('admin/updatepassword')->with('status', '修改成功!');
    }
    
}
