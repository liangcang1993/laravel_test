<?php

namespace App\Models;

class Config extends BaseModel
{
    protected $table = 'config';

    public static function getPageQuery($filter = array())
    {
        $query = self::selectRaw('*');
        if (isset($filter['key']) && trim($filter['key']) != '') {
            $query->where('key', 'like', '%'.$filter['key'].'%');
        }
		if(isset($filter['dev'])) {
			$query->where('device', '=', $filter['dev']);
		}
        if (isset($filter['sort']) && trim($filter['sort']) != '') {
            $d = explode(' ', $filter['sort']);
            $query->orderBy($d[0], $d[1]);
        }else{
            $query->orderBy('id', 'desc');
        }

        if (isset($filter['cover']) && $filter['cover'] == 'all')
        {
            return $query->paginate(50);
        }
        
        return $query->paginate(50);
    }

    public function isJson(){
        return starts_with(trim($this->value), '{');
    }
    public static function getMaterial(){
        $res = self::where('key','firstLoginGift')->first();
        $data = json_decode($res->value);
        return $data;
    }
}
