<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\BaseModel;
use PDO;
use DB;
use Auth;
use Session;
use Input;

class IndexController extends AdminBaseController
{
    /**
     * Index.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->render('admin/index');
 
	}

	public function changeApp()
	{
        return redirect('admin/');
    }

    public function cleanCache(){
        BaseModel::cleanCache();
        return redirect()->back()->with('status', '清除成功!');
    }
}
