<?php

namespace App\Models;

class Province extends Base
{
    //表名
    protected $table = 'province';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];
    // 设置时间戳字段
    public $timestamps = false;
}
