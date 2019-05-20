<?php

namespace App\Models;

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Models\Sso;
use DB;
use Config;
use Redis;
use App\Services\FileService;

use Excel;
use Storage;
use Log;


class UserStaticCount extends BaseModel
{
    protected $table = 'user_static_count';
}