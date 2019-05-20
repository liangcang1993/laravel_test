<?php

namespace App\Models;

use DB;
use Redis;

class Order
{
    protected $table = 'order';

    protected static $fieldL = ['uid','transactionIdentifier','productId','coin','type','reportInfo','receipt','original_transaction_id','expires_date_ms'];

    public static function add($dt)
    {
        $fieldL = SELF::$fieldL;

        foreach ($fieldL as $field) {

            if (!empty($dt[$field])) {
                $addDt[$field] = $dt[$field];
            }
        }

        $addDt['created_at'] = date('Y-m-d H:i:s');

        $res = DB::table('order')->insert($addDt);

        return $res;
    }

    /*
      @param field [array]
      @param where [array]
    */
    public static function find($field=['*'],$where)
    {   
        $field = implode(',', $field);

        $info = DB::table('order')
        ->select(DB::raw($field))
        ->where($where)
        ->get();

        $info = obj_to_arr($info);

        return $info;
    }

    

    



}
