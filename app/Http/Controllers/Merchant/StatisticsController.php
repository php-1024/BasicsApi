<?php

namespace App\Http\Controllers\Merchant;

use App\Library\Logs;
use App\Library\SearchData;
use App\Models\Account;
use App\Models\AccountConsume;
use App\Models\AccountConsumeDayChart;
use App\Models\AccountConsumeMonthChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StatisticsController extends Controller
{
    /**
     * 首页统计数据
     * @param Request $request
     * @return array
     */
    public function dashboard(Request $request)
    {
        $info = $request->get('info');
        // 获取今天的时间
        $today = Carbon::today()->timestamp;
        // 查询合作商户今天的吸粉数量
        $today_total = AccountConsume::getCount([['account_id', $info['id']], ['created_at', '>=', $today]]);
        // 查询合作商户今天的收入
        $price = AccountConsume::getSum([['account_id', $info['id']], ['created_at', '>=', $today]], 'price');
        $price = $price / 100;
        // 查询合作商户的总进粉数量
        $all_total = AccountConsume::getCount([['account_id', $info['id']]]);
        return ['code' => 20000, 'data' => ['price' => $price, 'today_total' => $today_total, 'all_total' => $all_total], 'message' => 'ok'];
    }


    /**
     * 合作商引入粉丝记录查询
     * @param Request $request
     * @return array
     */
    public function fans_record(Request $request)
    {
        $created_at = $request->get('created_at');
        $carbon     = new Carbon($created_at);
        $info       = $request->get('info');
        $limit      = $request->get('limit');
        $page       = $request->get('page');
        if (empty($created_at)) {
            $list = AccountConsume::where(['account_id' => $info['id']])
                ->orderBy('created_at', 'DESC')
                ->paginate($limit);
        } else {
            // 按照时间范围查找
            $str_time = $carbon->timestamp;
            $end_time = $str_time + 86399;
            $list     = AccountConsume::where(['account_id' => $info['id']])
                ->whereBetween('created_at', [$str_time, $end_time])
                ->orderBy('created_at', 'DESC')
                ->paginate($limit);
        }
        return ['code' => 20000, 'data' => $list, 'message' => 'ok'];
    }


    /**
     * 日报表获取
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function fans_day(Request $request)
    {
        $info     = $request->get('info');
        $str_time = $request->get('str_time');
        $end_time = $request->get('end_time');
        $limit    = $request->get('limit');
        $page     = $request->get('page');
        // 检测昨天的数据报表是否存在，不存在则创建
        self::day_chart($info['id']);
        if (!empty($str_time) && !empty($end_time)) {
            $time = SearchData::search_time($str_time, $end_time);
            $list = AccountConsumeDayChart::where(['account_id' => $info['id']])
                ->whereBetween('created_at', [$time["str_time"], $time["end_time"] - 1])
                ->orderBy('created_at', 'DESC')
                ->paginate($limit);
        } else {
            $list = AccountConsumeDayChart::getPaginate(['account_id' => $info['id']], [], ['limit' => $limit, 'page' => $page], 'created_at', 'DESC');
        }
        return ['code' => 20000, 'data' => $list, 'message' => 'ok'];
    }


    /**
     * 生成日报表的方法
     * @param $account_id
     * @throws \Exception
     */
    public static function day_chart($account_id)
    {
        // 获取今天的时间
        $today = Carbon::today()->timestamp;
        // 获取昨天的时间
        $yesterday = Carbon::yesterday()->timestamp;
        DB::beginTransaction();
        try {
            // 获取昨日报表，查看报表是否存在
            $row = AccountConsumeDayChart::where(['account_id' => $account_id])->whereBetween('created_at', [$yesterday, $today - 1])->first();
            if (empty($row)) {
                // 如果不存存在则创建昨天的数据报表
                $price = AccountConsume::where(['account_id' => $account_id])->whereBetween('created_at', [$yesterday, $today - 1])->sum('price');
                $total = AccountConsume::where(['account_id' => $account_id])->whereBetween('created_at', [$yesterday, $today - 1])->count();
                AccountConsumeDayChart::AddData([
                    'account_id' => $account_id,
                    'price'      => $price,
                    'total'      => $total,
                    'created_at' => $yesterday
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('生成日报表的方法' . $e);
        }
    }


    /**
     * 月报表获取
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function fans_month(Request $request)
    {
        $info     = $request->get('info');
        $str_time = $request->get('str_time');
        $limit    = $request->get('limit');
        $page     = $request->get('page');
        $where[]  = ['account_id', $info['id']];
        // 检测本月的数据报表是否存在，不存在则创建
        self::month_chart($info['id']);
        if (!empty($str_time)) {
            $str_time = new Carbon($str_time);
            $str_time = $str_time->timestamp;
            $where[]  = ['created_at', $str_time];
        }
        $list = AccountConsumeMonthChart::getPaginate($where, [], ['limit' => $limit, 'page' => $page], 'created_at', 'DESC');
        return ['code' => 20000, 'data' => $list, 'message' => 'ok'];
    }


    /**
     * 生成月报表的方法
     * @param $account_id
     * @throws \Exception
     */
    public static function month_chart($account_id)
    {
        // 获取本月的第一天
        $start = new Carbon('first day of this month');
        $start = $start->startOfMonth()->timestamp;
        //上个月第一天
        $firstOfMonth = new Carbon('first day of last month');
        $firstOfMonth = $firstOfMonth->startOfMonth()->timestamp;
        //上个月最后一天
        $lastOfMonth = new Carbon('last day of last month');
        $lastOfMonth = $lastOfMonth->endOfMonth()->timestamp;


        $where_this_month = [['account_id', $account_id], ['created_at', '>=', $start]];
        // ①生成这个月截止今天的数据统计
        // 获取本月报表，查看报表是否存在
        $this_month = AccountConsumeMonthChart::getOne($where_this_month);
        // 如果不存在则创建上个月的数据报表
        $this_month_price = AccountConsume::getSum($where_this_month, 'price');
        $this_month_total = AccountConsume::getCount($where_this_month);
        DB::beginTransaction();
        try {
            if (empty($this_month)) {
                AccountConsumeMonthChart::AddData([
                    'account_id' => $account_id,
                    'price'      => $this_month_price,
                    'total'      => $this_month_total,
                    'created_at' => $start
                ]);
            } else {
                AccountConsumeMonthChart::EditData(['id' => $this_month['id']], [
                    'account_id' => $account_id,
                    'price'      => $this_month_price,
                    'total'      => $this_month_total,
                    'created_at' => $start
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('①生成这个月截止今天的数据统计失败' . $e->getMessage());
        }

        // ②生成上个月的数据统计
        // 获取上个月报表，查看报表是否存在
        $previous_month = AccountConsumeMonthChart::where(['account_id' => $account_id])->whereBetween('created_at', [$firstOfMonth, $lastOfMonth])->first();
        // 如果不存在则创建上个月的数据报表
        $previous_month_price = AccountConsume::where(['account_id' => $account_id])->whereBetween('created_at', [$firstOfMonth, $lastOfMonth])->sum('price');
        $previous_month_total = AccountConsume::where(['account_id' => $account_id])->whereBetween('created_at', [$firstOfMonth, $lastOfMonth])->count();
        DB::beginTransaction();
        try {
            if (empty($previous_month)) {
                AccountConsumeMonthChart::AddData([
                    'account_id' => $account_id,
                    'price'      => $previous_month_price,
                    'total'      => $previous_month_total,
                    'created_at' => $firstOfMonth
                ]);
            } else {
                AccountConsumeMonthChart::EditData(['id' => $previous_month['id']], [
                    'account_id' => $account_id,
                    'price'      => $previous_month_price,
                    'total'      => $previous_month_total,
                    'created_at' => $firstOfMonth
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('②生成上个月的数据统计' . $e->getMessage());
        }
    }


    /**
     * 脚本生成日报表
     * @throws \Exception
     */
    public function command_account_day_chart()
    {
        try {
            $account_id_arr = Account::getPluck(['level' => 2, 'status' => 1], 'id');
            foreach ($account_id_arr as $key => $val) {
                self::day_chart($val);
            }
            Logs::notice('名字：', '执行脚本：生成合作商日报表ok');
        } catch (\Exception $e) {
            Log::error('执行失败：脚本生成日报表' . $e->getMessage());
        }
    }


    /**
     * 脚本生成月报表
     * @throws \Exception
     */
    public function command_account_month_chart()
    {
        try {
            $account_id_arr = Account::getPluck(['level' => 2, 'status' => 1], 'id');
            foreach ($account_id_arr as $key => $val) {
                self::month_chart($val);
            }
            Logs::notice('名字：', '执行脚本：生成合作商月报表：ok' . time());
        } catch (\Exception $e) {
            Log::error('执行失败：脚本生成月报表' . $e->getMessage());
        }
    }
}