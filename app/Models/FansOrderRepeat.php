<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class FansOrderRepeat extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'fans_order_repeat';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];
}
