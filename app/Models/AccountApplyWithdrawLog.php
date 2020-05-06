<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class AccountApplyWithdrawLog extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'account_apply_withdraw_log';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];
}
