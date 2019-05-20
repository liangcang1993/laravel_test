<?php

namespace App\Models;

use DB;
use Redis;

class JigsawProgress
{
    protected $table = 'jigsaw_progress';
 
    public static function getPageQuery($filter = array())
    {
        $query = Report::selectRaw('*');

        if (isset($filter['name']) && trim($filter['name']) != '') {
            $query->where('name', 'like', '%'.$filter['name'].'%');
        }
        if (isset($filter['sort']) && trim($filter['sort']) != '') {
            $d = explode(' ', $filter['sort']);
            $query->orderBy($d[0], $d[1]);
        }else{
            $query->orderBy('id', 'desc');
        }
        return $query->paginate(15);

    }

    public static function add($dt)
    {
        $fieldL = ['mid','level','uid','content','isFinish','update_by'];

        foreach ($fieldL as $field) {

            if (isset($dt[$field])) {
                $addDt[$field] = $dt[$field];
            }
        }

        $addDt['created_at'] = $addDt['updated_at'] = date('Y-m-d H:i:s');

        $res = DB::table('jigsaw_progress')->insert($addDt);

        return $res;
    }

    /*
      param dt['map'] 作where条件，其余字段作update的字段
    */ 
    public static function update($dt)
    {
        $fieldL = ['mid','uid','content','isFinish','update_by'];

        foreach ($fieldL as $field) {

            if (isset($dt[$field])) {
                $updateDt[$field] = $dt[$field];
            }
        }

        $updateDt['updated_at'] = date('Y-m-d H:i:s');

        $res = DB::table('jigsaw_progress')
        ->where($dt['map'])
        ->update($updateDt);

        return $res;
    }

    /*
      @param field [array]
      @param where [array]
    */
    public static function find($field=['*'],$where)
    {   
        $field = implode(',', $field);//here

        $info = DB::table('jigsaw_progress')
        ->select($field)
        ->where($where)
        ->get();

        $info = obj_to_arr($info);

        return $info;
    }

    public static function addToRedis($dt)
    {
        $fieldL = ['mid','level','uid','content','isFinish','update_by'];

        foreach ($fieldL as $field) {

            if (isset($dt[$field])) {
                $addDt[$field] = $dt[$field];
            }
        }

        $muid = $dt['mid'].'_'.$dt['level'];

        $key = "jigsaw_progress:${addDt['uid']}:$muid";
        $res = Redis::hMset($key, $addDt);

        return $res;
    }

    public static function addListToRedis($dt)
    {   
        $fieldL = ['mid','uid','content','isFinish','update_by'];

        foreach ($fieldL as $field) {

            if (isset($dt[$field])) {
                $addDt[$field] = $dt[$field];
            }
        }

        $muid = $dt['mid'].'_'.$dt['level'];

        $key = "jigsaw_progress:list:${addDt['uid']}";
        $res = Redis::zadd($key, time(), $muid);

        return $res;
    }

    public static function getInfoFromRedis($dt)
    {
        $key = "jigsaw_progress:${dt['uid']}:${dt['muid']}";
        $res = Redis::hgetall($key);

        return $res;
    }

    public static function getListFromRedis($dt)
    {
        $key = "jigsaw_progress:list:${dt['uid']}";

        $muidL = Redis::zrevrange($key,0,-1);

        if (empty($muidL)) {//redis is wrong
            return false;
        }

        $midInfo = [];

        foreach ($muidL as $muid) {
            //add the info to it
            $info = SELF::getInfoFromRedis(['muid'=>$muid,'uid'=>$dt['uid']]);

            // filter isFinish field
            if (isset($dt['isFinish']) && $info['isFinish'] != $dt['isFinish']) {
                continue;
            }

            // filter the user's update_by
            if (isset($dt['update_by']) && @$info['update_by'] == $dt['update_by']) {
                continue;
            }

            $midInfo[] = $info;
        }

        // get all the count
        $c = count($midInfo);
        $page_c = ceil($c/$dt['limit']);

        $midInfo = array_slice($midInfo,($dt['page']-1)*$dt['limit'],$dt['limit']);

        return ['list'=>$midInfo,'page_c'=>$page_c];
    }

    public static function getList($field=['*'], $where, $dt)
    {
        $field = implode(',', $field);

        $update_by_neq = @$where['update_by_neq'];
        unset($where['update_by_neq']);

        $info = DB::table('jigsaw_progress')
        ->select($field)
        ->orderBy('updated_at','desc')
        ->where($where);

        if ($update_by_neq) {
            $info = $info->where('update_by', '<>', $update_by_neq);
        }

        $info = $info->get();

        if (empty($info)) {
            return ['list'=>[],'page_c'=>0];;
        }

        $info = obj_to_arr($info);

        // get all the count
        $c = DB::table('jigsaw_progress')
        ->where($where);

        if ($update_by_neq) {
            $c = $c->where('update_by', '<>', $update_by_neq);
        }

        $c = $c->count();

        $page_c = ceil($c/$dt['limit']);

        return ['list'=>$info,'page_c'=>$page_c];

    }


}
