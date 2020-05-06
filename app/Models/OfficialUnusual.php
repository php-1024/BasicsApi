<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class OfficialUnusual extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'official_unusual';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];

    /**
     * 格式化返回二维码地址
     * @param $data
     * @return mixed
     */
    public static function getQrcodePathAttribute($data)
    {
        if (!empty($data)) {
            $data = str_replace('./', '', $data);
            return asset($data);
        }
    }
}
