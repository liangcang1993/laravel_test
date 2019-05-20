<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class AdminRole extends Model
{
    protected $table = 'admin_role';
    protected $fillable = ['id', 'role_name','is_delete'];

    public static function getPageQuery()
    {
        
        $query = self::selectRaw('*');
        $query->where('is_delete',0);
        return $query->distinct()->paginate(20);

    }
 
    /**
     * 修改密码
     */
    public static function updatePwd($uid, $pwd)
    {
        $pwd = password_hash($pwd, PASSWORD_BCRYPT);
        return DB::update("update admin_user set password = '{$pwd}' where id = ?", array($uid));
    }

    public static function checkAccount($account)
    {
        $sts = ['code'=>0, 'message'=>''];
        $resulut = 0;
        $admin_user = DB::table("admin_user")->where('phone',$account)->first();

        if (empty($admin_user)) {
            $sts['code'] = 3;
            $sts['message'] = '用户不存在';
        } else {
            /*if ($user->last_login_time == NULL) {
                $sts['code'] = 4;
                $sts['message'] = '需要验证码';
            }*/
        }

        return $sts;
    }

    public static function isAccountExist($account)
    {
        //数据库校验用户名密码是否正确
        $query = DB::table("admin_user");

        $id = 0;
        if (is_numeric($account)) $id = DB::table("admin_user")->where('phone',$account)->value('id');
        else $id = DB::table("admin_user")->where('email',$account)->value('id');

        return $id > 0;
    }

    public static function checkPwd($uid, $pwd)
    {
        if (empty($pwd))
            return false;

        $admin_user = DB::table("admin_user")->where('id', $uid)->first();
        if (empty($admin_user))
            return false;

        if (!password_verify($pwd, $admin_user->password))
            return false;

        return true;
    }
 }
