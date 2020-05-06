<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountConsumeMonthChart extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'account_consume_month_chart';
    //主键
    protected $primaryKey = 'id';
    // 设置时间保存为时间戳
    //过滤黑名单字段
    public $guarded = [];

    /**
     * 格式化返回时间
     * @param $timestamp
     * @return string
     * @throws \Exception
     */
    public static function getCreatedAtAttribute($timestamp)
    {
        $timestamp = Carbon::createFromTimestamp($timestamp);
        return Carbon::parse($timestamp)->format('Y-m-d H:i:s');
    }

    /**
     * 格式化返回时间
     * @param $timestamp
     * @return string
     * @throws \Exception
     */
    public static function getUpdatedAtAttribute($timestamp)
    {
        $timestamp = Carbon::createFromTimestamp($timestamp);
        return Carbon::parse($timestamp)->format('Y-m-d H:i:s');
    }
}
