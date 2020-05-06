<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficialDayChart extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'official_day_chart';
    //主键
    protected $primaryKey = 'id';
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
        if (Carbon::now() < Carbon::parse($timestamp)->addDays(7)) {
            return Carbon::parse($timestamp)->diffForHumans();
        }
        return Carbon::parse($timestamp)->format('Y年m月d日');
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
        if (Carbon::now() < Carbon::parse($timestamp)->addDays(10)) {
            return Carbon::parse($timestamp)->diffForHumans();
        }
        return Carbon::parse($timestamp)->format('Y年m月d日');
    }
}
