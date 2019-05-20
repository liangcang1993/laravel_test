<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;

use App\Models\RedisKey;
use App\Models\Order;
use App\Models\JigsawProgress;
use App\Models\User;

use App\Logics\OrderLogics;
use App\Logics\RestoreLogics;

use Input;
use DB;
use Redis;
use Illuminate\Http\Request;

class RestoreController extends ApiBaseController
{

    public function getUserRestoreInfo(Request $request)
    {   

        $dt = input_dt();

        $dt['uid'] = $this->getUid();

        // test data
        //$dt['receiptDict'] = '{"expires_date":"2019-03-01 02:47:32 Etc/GMT","expires_date_ms":1551408452000,"expires_date_pst":"2019-02-28 18:47:32 America/Los_Angeles","transaction_id":1000000506725012,"original_transaction_id":1000000486237137,"product_id":"Jigsaws_1Q"}';//here have a test
        
        if (empty($dt['uid'])) {
            ret_dt(9001,'can not get the user info');
        }

        if (empty($dt['receiptDict'])) {
            ret_dt(1001,'receiptDict is less');
        }

        $dt = SELF::dealInput($dt);

        if (empty($dt['original_transaction_id']) || empty($dt['transactionIdentifier'])) {
            ret_dt(2004, 'transaction_id info less');
        }
        
        // get old info
        $oldInfo = OrderLogics::getSameReceiptInfo($dt);

        if (empty($oldInfo['uid'])) {
            ret_dt(2001,'has no receipt info');
        }

        $uInfo = User::userInfo($oldInfo['uid']);
        
        if (empty($uInfo)) {
            ret_dt(3001, 'get user info failed');
        }

        if ($oldInfo['uid'] != $dt['uid']) {
            //put before vip info to now vip info
            RestoreLogics::coverVipInfoByBefore($dt['uid'], $oldInfo['uid']);
        }
        
        ret_dt(0,'success',$uInfo);
    }

    static private function dealInput($dt)
    {   

        $dt['receiptDict'] = User::headerDecrypt($dt['receiptDict']);

        $receiptInfo = json_decode($dt['receiptDict'], true);

        $fieldL = ['original_transaction_id','transaction_id','expires_date_ms'];

        foreach ($fieldL as $field) {

            $dt[$field] = @$receiptInfo[$field];
        }

        $dt['transactionIdentifier'] = @$dt['transaction_id'];
        
        return $dt;
    }






}
