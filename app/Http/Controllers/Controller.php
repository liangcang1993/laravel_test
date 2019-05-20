<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Auth;
use Session;
use DB;
use PDO;
use Redis;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $proj_ids = [];
    protected $dept_ids = [];

    public function __construct()
    {
        if (Auth::check()) {
            $is_super = Auth::user()->is_super;
            $user_id = Auth::user()->id;

        }
    }

    protected function render($view = null, $data = [])
    {
        
        return view($view, $data);
    }

    protected function returnJsonResult($res)
    {
        return json_encode($data);;
    }

    protected function getApp(){

        $res = Session::get('app');
        if(empty($res)){
            $res = 'photable';
            Session::set('app', $res);
        }
        return $res;
    }


    protected function getSystem(){

        $res = Session::get('system');
        if(empty($res)){
            $res = 'manly';
            Session::set('system', $res);
        }
        return $res;
    }

    
}
