<?php


namespace App\Library;


use Carbon\Carbon;

class SearchData
{
    /**
     * 搜索时间处理
     * @param null $str_time
     * @param null $end_time
     * @return array
     */
    public static function search_time($str_time = null, $end_time = null)
    {
        if (!empty($str_time)) {
            // 开始时间
            $str_time = new Carbon($str_time);
            $str_time = $str_time->timestamp;
        }
        if (!empty($end_time)) {
            // 结束时间
            $end_time = new Carbon($end_time);
            $end_time = $end_time->timestamp;
        }
        if ($str_time == $end_time && !is_null($str_time) && !is_null($end_time)) {
            $end_time = $end_time + 86400;
        }
        return ['str_time' => intval($str_time), 'end_time' => intval($end_time)];
    }
}