<?php

namespace App\Models;

class IszmxwLog extends Base
{
    //表名
    protected $table = 'iszmxw_log';
    //主键
    protected $primaryKey = 'id';
    // 设置时间保存为时间戳
//    public $dateFormat = 'Y-m-d H:i:s';
    //过滤黑名单字段
    public $guarded = [];


    // 添加数据
    public static function AddData($content = '', $where = [])
    {
        if (!empty($where)) {
            $res = self::where($where)->first();
            if (empty($res)) {
                $res = self::create(['content' => $content]);
            }
        } else {
            $res = self::create(['content' => $content]);
        }

        if (!empty($res)) {
            return $res->toArray();
        } else {
            return false;
        }
    }
}
