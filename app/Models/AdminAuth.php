<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class AdminAuth extends Model
{
    protected $table = 'admin_auth';
    // protected $fillable = ['id', 'role_name','is_delete'];

    public static function getPageQuery()
    {
        
        $query = self::selectRaw('*');
        // $query->where('is_delete',0);
        return $query->get();

    }
 
    
 }
