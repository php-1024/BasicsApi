<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Requests\Merchant\Estimate;
use App\Library\SearchData;
use App\Models\AccountApplyWithdraw;
use App\Models\AccountApplyWithdrawLog;
use App\Models\BankInfo;
use App\Models\OperationLog;
use App\Models\AccountWallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EstimateController extends Controller
{
    // 结算列表
    public function list(Request $request)
    {
        $info     = $request->get('info');
        $limit    = $request->get('limit');
        $page     = $request->get('page');
        $str_time = $request->get('str_time');
        $end_time = $request->get('end_time');
        if (!empty($str_time) && !empty($end_time)) {
            $time_data = SearchData::search_time($str_time, $end_time);
            $list      = AccountApplyWithdraw::where(['account_id' => $info['id']])
                ->whereBetween('created_at', [$time_data['str_time'], $time_data['end_time'] - 1])
                ->orderBy('created_at', 'DESC')
                ->paginate($limit);
        } else {
            $list = AccountApplyWithdraw::getPaginate(['account_id' => $info['id']], [], ['limit' => $limit, 'page' => $page], 'created_at');
        }
        $settlemented  = AccountApplyWithdraw::getSum(['account_id' => $info['id'], 'status' => 1], 'price');
        $settlementing = AccountApplyWithdraw::getSum(['account_id' => $info['id'], 'status' => 0], 'price');
        $amount        = AccountWallet::getValue(['account_id' => $info['id']], 'amount');
        return ['code' => 20000, 'data' => ['list' => $list, 'settlemented' => $settlemented / 100, 'settlementing' => $settlementing / 100, 'amount' => $amount / 100]];
    }

    // 结算信息
    public function settlement_info(Request $request)
    {
        $info            = $request->get('info');
        $settlement_info = BankInfo::where(['bank_info.account_id' => $info['id']])
            ->leftJoin(
                'account_wallet',
                function ($join) {
                    $join->on('bank_info.account_id', '=', 'account_wallet.account_id');
                }
            )
            ->select(['bank_info.*', 'account_wallet.amount', 'account_wallet.fee'])
            ->first();

        if ($settlement_info) {
            return ['code' => 20000, 'data' => $settlement_info];
        } else {
            return ['code' => 50000, 'message' => '请先设置银行卡信息'];
        }
    }

    /**
     * 商户提现申请
     * @param Estimate $request
     * @return array
     * @throws \Exception
     * @author: iszmxw <mail@54zm.com>
     * @Date：2019/12/5 16:48
     */
    public function apply_withdraw(Estimate $request)
    {
        $price      = $request->get('price');
        $info       = $request->get('info');
        $account_id = $info['id'];
        // 查询余额
        $amount = AccountWallet::getValue(['account_id' => $account_id], 'amount');
        // 查询提现手续费费率
        $fee_num = AccountWallet::getValue(['account_id' => $account_id], 'fee');
        if ($price * 100 > $amount) {
            return ['code' => 50000, 'message' => '您的余额不足，提现金额超出'];
        }
        $bank_info = BankInfo::getOne(['account_id' => $account_id]);
        if (empty($bank_info)) {
            return ['code' => 50000, 'message' => '请先设置您的提现信息'];
        }
        if (empty($bank_info['name'])) {
            return ['code' => 50000, 'message' => '请补全您的银行卡信息'];
        }
        if (empty($bank_info['number'])) {
            return ['code' => 50000, 'message' => '请补全您的银行卡信息'];
        }
        if (empty($bank_info['bankname'])) {
            return ['code' => 50000, 'message' => '请补全您的银行卡信息'];
        }
        if (empty($bank_info['remarks'])) {
            return ['code' => 50000, 'message' => '请补全您的银行卡信息'];
        }
        // 计算本次提现的手续费 （提现手续费 = 提现金额 * 提现费率）
        $fee_price = ceil(($price * 100) * ($fee_num / 100));
        // 提现后的钱包余额
        $new_amount    = $amount - ($price * 100) - $fee_price;
        $reality_price = ($price * 100) - $fee_price;
        DB::beginTransaction();
        try {
            AccountWallet::EditData(['account_id' => $account_id], ['amount' => $new_amount]);
            AccountApplyWithdraw::AddData([
                'account_id'    => $account_id,
                'price'         => $price * 100,
                'fee'           => $fee_price,
                'reality_price' => $reality_price,
                'name'          => $bank_info['name'],
                'number'        => $bank_info['number'],
                'bankname'      => $bank_info['bankname'],
                'remarks'       => $bank_info['remarks'],
                'type'          => $bank_info['type'],
                'status'        => 0,
            ]);
            $tips_fee_price = $fee_price / 100;
            OperationLog::AddData(['type' => 2, 'account_id' => $account_id, 'content' => "您申请了提现，当前提现的金额为{$price}元，手续费为{$tips_fee_price}元"]);
            DB::commit();
        } catch (\Exception $e) {
            \Log::error('申请提现失败');
            \Log::error($e);
            DB::rollBack();
            return ['code' => 50000, 'message' => '操作失败，请稍后再试'];
        }
        return ['code' => 20000, 'message' => '申请成功，请等待系统审核'];
    }


    /**
     * 月结算概况
     * @param Request $request
     * @return array
     */
    public function month_estimate(Request $request)
    {
        $info = $request->get('info');
        // 获取本月的第一天
        $start = new Carbon('first day of this month');
        $start = $start->startOfMonth();
        //上个月第一天
        $firstOfMonth = new Carbon('first day of last month');
        $firstOfMonth = $firstOfMonth->startOfMonth();
        //上个月最后一天
        $lastOfMonth = new Carbon('last day of last month');
        $lastOfMonth = $lastOfMonth->endOfMonth();
        // 查询上个月的结算金额
        $previous_month_price = AccountApplyWithdrawLog::where(['account_id' => $info['id'], 'status' => 1])->whereBetween('created_at', [$firstOfMonth, $lastOfMonth])->sum('price');
        $this_month_price     = AccountApplyWithdrawLog::getSum([
            ['account_id', $info['id']],
            ['status', 1],
            ['created_at', '>=', $start]
        ], 'price');
        return ['code' => 20000, 'data' => ['previous_month_price' => $previous_month_price / 100, 'this_month_price' => $this_month_price / 100], 'message' => 'ok'];
    }
}
