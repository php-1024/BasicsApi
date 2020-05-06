<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class UserApplyWithdrawLog extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'user_apply_withdraw_log';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];
}
