<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Publics extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'public';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];
    // 设置时间戳字段
    public $timestamps = false;
}
