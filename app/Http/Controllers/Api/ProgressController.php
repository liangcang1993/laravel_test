<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;

use App\Models\RedisKey;
use App\Models\JigsawProgress;

use App\Logics\ProgressLogics;

use Input;
use DB;
use Redis;
use Log;
use Illuminate\Http\Request;
use Storage;

class ProgressController extends ApiBaseController
{   

    public function postMaterialProgress(Request $request)
    {
        $dt = input_dt();

        // test data
        //$dt['content']='';
        
        $dt['uid'] = $this->getUid();

        $dt = ProgressLogics::dealInputDt($dt);

        // test data
        //$dt['mid'] = 123;
        //$dt['content'] = 'dddd';
        
        if (empty($dt['uid'])) {
            ret_dt(9001,'can not get the user info');
        }

        if (empty($dt['mid']) || empty($dt['content'])) {
            ret_dt(1001,'param is less');
        }

        if (empty($dt['level'])) {//level 为空或0 的，直接丢掉
            ret_dt(0, 'success');
        }
        
        $map['uid'] = $dt['uid'];
        $map['mid'] = $dt['mid'];
        $map['level'] = $dt['level'];

        $info = JigsawProgress::find(['id'], $map);

        if ($info) {//upate
            $dt['map']['uid'] = $dt['uid'];
            $dt['map']['mid'] = $dt['mid'];
            $dt['map']['level'] = $dt['level'];

            $res = JigsawProgress::update($dt);

        } else {// add

            $res = JigsawProgress::add($dt);
            
        }

        if ($res === false) {
            ret_dt(3001,'add to db failed');
        }

        // add to redis
        JigsawProgress::addToRedis($dt);
        JigsawProgress::addListToRedis($dt);

        ret_dt(0,'success');
    }

    public function getUserMaterialList(Request $request)
    {
        $dt = $request->all();
        
        $dt['uid'] = $this->getUid();

        // test data
        //$dt['update_by_others'] = 0;
        //$dt['isFinish'] = 0;
        
        if (empty($dt['uid'])) {
            ret_dt(9001,'can not get the user info');
        }

        if (isset($dt['isFinish']) && ($dt['isFinish']!=0 && $dt['isFinish']!=1)) {
            ret_dt(1002, 'isFinish is wrong');
        }

        // deal update_by filter
        if (@$dt['update_by_others'] == 1) {
            ProgressLogics::dealUpdateBy($dt);
        }

        $dt['page'] = isset($dt['page'])?$dt['page']:1;
        $dt['limit'] = isset($dt['limit'])?$dt['limit']:30;

        $listInfo = JigsawProgress::getListFromRedis($dt);

        if ($listInfo === false) {//redis is wrong, get from db
            $listInfo = SELF::getMaterialListForDb($dt);
        }

        // deal the data construct
        ProgressLogics::dealOutputDt($listInfo);

        if ($listInfo !== false) {
            ret_dt(0, 'success', $listInfo);
        } else {
            ret_dt(3001, 'handle failed');
        }
    }

    // assistant func
    public function getMaterialListForDb($dt)
    {

        $field = ['*'];

        $where['uid'] = $dt['uid'];

        if (isset($dt['isFinish'])) {
            $where['isFinish'] = $dt['isFinish'];
        }

        if (isset($dt['update_by'])) {
            $where['update_by_neq'] = $dt['update_by'];
        }//out($dt);
        
        $listInfo = JigsawProgress::getList($field, $where, $dt);
        
        foreach ($listInfo['list'] as $info) {
            // add to redis
            JigsawProgress::addToRedis($info);
            JigsawProgress::addListToRedis($info);
        }

        return $listInfo;

    }







    // 暂时不需要，直接在list中返回详情
    /*public function getMaterialProgress(Request $request)
    {
        $dt = $request->all();
        
        // test data
        $dt['uid'] = $this->getUid();
        $dt['mid'] = 123;

        if (empty($dt['uid'])) {
            ret_dt(9001,'can not get the user info');
        }

        if (empty($dt['mid'])) {
            ret_dt(1001,'mid is less');
        }

        // get from redis
        $info = JigsawProgress::getInfoFromRedis($dt);

        if (!$info) {
            $map['uid'] = $dt['uid'];
            $map['mid'] = $dt['mid'];

            $info = JigsawProgress::find(['*'], $map)[0];

            // add to redis
            JigsawProgress::addToRedis($info);
            JigsawProgress::addListToRedis($info);
        }
    
        $res = $info;

        if ($res !== false) {
            ret_dt(0, 'success', $res);
        } else {
            ret_dt(3001, 'deal failed');
        }

    }*/



}
