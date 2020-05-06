<?php

namespace App\Models;

class City extends Base
{
    //表名
    protected $table = 'city';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];
    // 设置时间戳字段
    public $timestamps = false;
}
