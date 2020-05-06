<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Merchant\Estimate;
use App\Models\AccountApplyWithdraw;
use App\Models\AccountApplyWithdrawLog;
use App\Models\BankInfo;
use App\Models\OperationLog;
use App\Models\AccountWallet;
use App\Models\UserApplyWithdraw;
use App\Models\UserApplyWithdrawLog;
use App\Models\UserCommission;
use App\Models\UserRechargeLog;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstimateController extends Controller
{
    /**
     * 合作商户申请结算列表
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:45
     */
    public function company_apply_list(Request $request)
    {
        $limit    = $request->get('limit');
        $page     = $request->get('page');
        $str_time = strtotime($request->get('data1'));
        $end_time = strtotime($request->get('data2'));
        $list     = AccountApplyWithdraw::where(['account_apply_withdraw.status' => 0])
            ->leftJoin('account_info', function ($join) {
                $join->on('account_apply_withdraw.account_id', '=', 'account_info.account_id');
            })
            ->select(['account_apply_withdraw.*', 'account_info.company'])
            ->orderBy('account_apply_withdraw.created_at', 'ASC')
            ->paginate($limit, $page);
        return ['code' => 20000, 'data' => ['list' => $list]];
    }


    /**
     * 获取合作商户提现详情信息
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:45
     */
    public function company_withdraw_info(Request $request)
    {
        $id = $request->get('id');
        if ($id) {
            $withdraw_info = AccountApplyWithdraw::where(['account_apply_withdraw.id' => $id])
                ->leftJoin('account_info', function ($join) {
                    $join->on('account_apply_withdraw.account_id', '=', 'account_info.account_id');
                })
                ->select(['account_apply_withdraw.*', 'account_info.company'])
                ->first();
            return ['code' => 20000, 'withdraw_info' => $withdraw_info];
        } else {
            return ['code' => 50000, 'message' => '获取审核信息失败，请稍后再试，或者联系系统开发人员帮忙查看！'];
        }
    }


    /**
     * 审核合作商户提现信息
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author: iszmxw <mail@54zm.com>
     * @Date：2019/12/5 17:18
     */
    public function company_check_withdraw(Request $request)
    {
        $info       = $request->get('info');
        $account_id = $info['id'];
        $id         = $request->get('id');
        $company    = $request->get('company');
        $status     = $request->get('status');
        $reason     = $request->get('reason');

        $withdraw = AccountApplyWithdraw::getOne(['id' => $id]);
        $price    = $withdraw['price'] / 100;

        if ($status == 0) {
            return ['code' => 50000, 'message' => '请切换审核状态后再操作！'];
        }
        DB::beginTransaction();
        try {
            // 审核提现申请
            AccountApplyWithdraw::EditData(['id' => $id], ['status' => $status, 'reason' => $reason]);
            // 添加提现审核记录
            if (!AccountApplyWithdrawLog::checkRowExists(['withdraw_id' => $id])) {
                AccountApplyWithdrawLog::AddData([
                    'withdraw_id'   => $id,
                    'account_id'    => $withdraw['account_id'],
                    'price'         => $withdraw['price'],
                    'fee'           => $withdraw['fee'],
                    'reality_price' => $withdraw['reality_price'],
                    'name'          => $withdraw['name'],
                    'number'        => $withdraw['number'],
                    'bankname'      => $withdraw['bankname'],
                    'remarks'       => $withdraw['remarks'],
                    'type'          => $withdraw['type'],
                    'status'        => $status,
                    'reason'        => $reason
                ]);
            }
            if ($status == 1) {
                OperationLog::AddData(['type' => 1, 'account_id' => $account_id, 'content' => "您审核了商户的id为{$withdraw['account_id']}--商户名称为【{$company}】的一笔提现申请，金额为{$price}元"]);
            } elseif ($status == 2) {
                //计算钱余额，返回退还给商户
                $amount = AccountWallet::getValue(['account_id' => $withdraw['account_id']], 'amount');
                $amount = $amount + $withdraw['price'];
                AccountWallet::EditData(['account_id' => $withdraw['account_id']], ['amount' => $amount]);
                OperationLog::AddData(['type' => 1, 'account_id' => $account_id, 'content' => "您驳回了商户名称为【{$company}】的一笔提现申请，金额为{$price}元"]);
            }
            DB::commit();
        } catch (\Exception $e) {
            Log::error("审核提现出问题");
            Log::error($e);
            DB::rollBack();
            return ['code' => 50000, 'message' => '操作失败，请稍后再试'];
        }

        return ['code' => 20000, 'message' => '审核成功！'];
    }

    /**
     * 合作商户审核结算记录列表
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:46
     */
    public function company_apply_list_log(Request $request)
    {
        $limit    = $request->get('limit');
        $page     = $request->get('page');
        $str_time = strtotime($request->get('data1'));
        $end_time = strtotime($request->get('data2'));
        $list     = AccountApplyWithdrawLog::where([])
            ->leftJoin('account_info', function ($join) {
                $join->on('account_apply_withdraw_log.account_id', '=', 'account_info.account_id');
            })
            ->select(['account_apply_withdraw_log.*', 'account_info.company'])
            ->orderBy('account_apply_withdraw_log.created_at', 'DESC')
            ->paginate($limit, $page);
        return ['code' => 20000, 'data' => ['list' => $list]];
    }


    /**
     * 提现申请确认操作
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author: iszmxw <mail@54zm.com>
     * @Date：2020/1/9 16:01
     */
    public function company_apply_check(Request $request)
    {
        $info       = $request->get('info');
        $account_id = $info['id'];
        $id         = $request->get('id');
        $log        = AccountApplyWithdrawLog::getOne(['id' => $id], ['id', 'withdraw_id', 'account_id', 'status']);
        if (1 == $log['status']) {
            DB::beginTransaction();
            try {
                // 标记为已完成
                AccountApplyWithdrawLog::EditData(['id' => $id], ['status' => 3]);
                AccountApplyWithdraw::EditData(['id' => $log['withdraw_id']], ['status' => 3]);
                OperationLog::AddData(['type' => 1, 'account_id' => $account_id, 'content' => "您对一笔合作商户提现申请进行了确认操作，该提现申请的审核记录id为：{$id}"]);
                DB::commit();
                return ['code' => 20000, 'message' => '确认成功！'];
            } catch (\Exception $e) {
                DB::rollBack();
                return ['code' => 50000, 'message' => '操作失败，请稍后再试！'];
            }
        }

    }

}
