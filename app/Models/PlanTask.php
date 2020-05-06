<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class PlanTask extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'plan_task';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];

    /**
     * 处理返回的图片url地址，完整地址
     * @param $data
     * @return string
     */
    public static function getQrcodePathAttribute($data)
    {
        if (!empty($data)) {
            $data = str_replace('./', '', $data);
            return asset($data);
        }
    }
}
