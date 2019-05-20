<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\ApiBaseController;
use App\Models\Subscript;

use Illuminate\Http\Request; 

use Input;
use Log;
use Redis;

class SubscriptController extends ApiBaseController
{
    public function getSubInfo(Request $request)
    {
        $data       = json_encode(Input::all());
        $dataArr = json_decode($data,true);
        
        // Log::info($maininfo);
        
        $model = new Subscript;
        $model->app             = 'jigsaw';
        $model->sub_json        = $data;
        $model->save();
        if (isset($dataArr['latest_receipt_info']) ==  false && isset($dataArr['latest_expired_receipt_info']) ==  false) 
        {
            $res = ['code'=>200, 'message'=>'success'];
            return response()->json($res);
        }
        $maininfo = isset($dataArr['latest_receipt_info']) ? $dataArr['latest_receipt_info'] : $dataArr['latest_expired_receipt_info'];
        
        if (!Redis::exists('SUB_' . $maininfo['transaction_id'])) 
        {
            $model->transaction_id      = $maininfo['transaction_id'];
            $model->unique_identifier   = $maininfo['unique_identifier'];
            $model->product_id          = $maininfo['product_id'];
            $model->original_purchase_date_pst  = $maininfo['original_purchase_date_pst'];
            $model->purchase_date_pst           = $maininfo['purchase_date_pst'];
            $model->purchase_date               = $maininfo['purchase_date'];
            $model->original_purchase_date      = $maininfo['original_purchase_date'];
            $model->environment                 = $dataArr['environment'];
            $model->auto_renew_status           = $dataArr['auto_renew_status'];
            $model->auto_renew_product_id       = $dataArr['auto_renew_product_id'];    
            $model->notification_type           = $dataArr['notification_type'];    
            $model->is_trial_period             = $maininfo['is_trial_period'];
            $model->created_at      = date('Y-m-d H:i:s', time());
            $model->save();
            // Log::info('new saved');
            Redis::setex( 'SUB_' . $maininfo['transaction_id'] , 3600 , $maininfo['transaction_id'] );
        }
        
        $res = ['code'=>200, 'message'=>'success'];
        return response()->json($res);

    }
    
}
