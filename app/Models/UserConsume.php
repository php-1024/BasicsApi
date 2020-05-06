<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class UserConsume extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'user_consume';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];
}
