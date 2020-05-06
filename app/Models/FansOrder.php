<?php

namespace App\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class FansOrder extends Base
{
    use SoftDeletes;
    //表名
    protected $table = 'fans_order';
    //主键
    protected $primaryKey = 'id';
    //过滤黑名单字段
    public $guarded = [];


    // admin
    // 订单列表
    public static function admin_order_list($where, $limit, $page)
    {
        $res = self::where($where)
            ->leftJoin('account_info', function ($join) {
                $join->on('fans_order.develop_appid', '=', 'account_info.develop_appid');
            })
            ->leftJoin('user', function ($join) {
                $join->on('fans_order.user_id', '=', 'user.id');
            })
            ->leftJoin('city', function ($join) {
                $join->on('fans_order.city_code', '=', 'city.id');
            })
            ->orderBy('fans_order.created_at', 'DESC')
            ->select(['account_info.company', 'user.username', 'city.name as city_name', 'fans_order.*'])
            ->paginate($limit, $page);
        return $res;
    }
}
