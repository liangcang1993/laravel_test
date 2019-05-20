<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\AdminUser; 
use App\Models\AdminRole;
use App\Models\AppAuth;
use App\Models\AppMod; 
use App\Http\Models\Utility;

use Validator;
use Input;
use Session;
use DB;
use Config;
use Auth;
use PDO;
use Redis;

class AdminRoleController extends AdminBaseController
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = AdminRole::getPageQuery();

		return $this->render(
			'admin.admin_role_index', 
			[
				'users' => $users
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
		return $this->render(
			'admin.admin_role_create',
			[

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
		$model = new AdminRole;
		$model->role_name = Input::get('role_name','');
		$model->authids = Input::get('authids','');
		$model->created_at = date('Y-m-d H:i:s',time());
		$res = $model->save();
		if ($res)
		{
			return redirect('admin/admin_role')->with('status', '添加成功!');
		}
		return redirect('admin/admin_role/')->withErrors("添加失败！")->withInput();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$role = AdminRole::find($id);
		return $this->render(
			'admin.admin_role_edit',
			[
				'user'      => $role
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
//		dd($request->all());
		$role = AdminRole::find($id);

		$role->role_name 	=  trim(Input::get('role_name',$role->role_name));
		$role->authids 		=  trim(Input::get('authids',$role->authids));
		$res = $role->save();
		if ($res) {
			Redis::hdel('RoleAuthArr',$id);
			Redis::hdel('SideBar',$id);
			return redirect('admin/admin_role')->with('status', '更新成功!');
		} else {
			return redirect('admin/admin_role/' . $id . '/edit')->withErrors("更新失败!")->withInput();
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
		$res = AdminRole::where('id',$id)->delete();
		if ($res) {
			Redis::hdel('RoleAuthArr',$id);
			Redis::hdel('SideBar',$id);
			return redirect('admin/admin_role')->with('status', '删除成功!');
		} else {
			return redirect('admin/admin_role/')->withErrors("删除失败!");
		}
	}

	public function cleanRole()
	{
		Redis::del('RoleAuthArr');
		Redis::del('SideBar');
		return redirect('admin/admin_role')->with('status', '清除成功!');
	}

	
}
