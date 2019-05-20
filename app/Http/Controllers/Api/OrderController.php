<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;

use App\Models\RedisKey;
use App\Models\JigsawProgress;

use App\Logics\OrderLogics;

use Input;
use DB;
use Redis;
use Illuminate\Http\Request;

class OrderController extends ApiBaseController
{
    // 暂时不用了
    /*public function postUserOrder(Request $request)
    {
        $dt = input_dt();

        // test data
        $dt['type'] = 'autoRenewable';
        $dt['uid'] = '123';
        $dt['uid'] = $this->getUid();
        $dt['productId'] = '230000';
        $dt['coin'] = '0';
        $dt['transactionIdentifier'] = '111';
        
        if (empty($dt['uid'])) {
            ret_dt(9001,'can not get the user info');
        }

        if (empty($dt['transactionIdentifier'])) {
            ret_dt(1001,'transactionIdentifier is less');
        }

        if (empty($dt['productId']) || !isset($dt['coin']) || empty($dt['type'])) {
            ret_dt(1002,'param is less');
        }
        
        $res = OrderLogics::dealOrder($dt);

        if ($res !== true) {
            ret_dt(3001, $res);
        }

        ret_dt(0, 'success');
    }
    */

    






}
