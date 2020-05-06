<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceCategory extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'device_category';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];

    protected $casts = [
        'disabled' => 'boolean',
    ];
}
