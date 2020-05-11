<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Merchant;
use App\Library\Logs;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\AccountWallet;
use App\Models\OperationLog;
use App\Models\Scene;
use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class StoreController extends Controller
{
    /**
     * 获取门店类型（场景）
     * @param Request $request
     * @return array
     * @author: iszmxw <mail@54zm.com>
     * @Date：2020/5/11 16:58
     */
    public function get_scene(Request $request)
    {
        $list = Scene::getList();
        return ['code' => 20000, 'message' => 'ok', 'data' => $list];
    }


    /**
     * 获取单条门店信息
     * @param Request $request
     * @return array
     * @author: iszmxw <mail@54zm.com>
     * @Date：2020/5/11 17:53
     */
    public function get_store_info(Request $request)
    {
        $id = $request->get('id');
        if (!empty($id)) {
            $data = Store::getOne(['id' => $id]);
            return ['code' => 20000, 'message' => 'ok', 'data' => $data];
        }
    }


    // 保存门店信息
    public function save_store_info(Request $request)
    {

    }

    /**
     * 添加合作商户
     * @param Merchant $request
     * @return array
     * @throws \Exception
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:52
     */
    public function add(Merchant $request)
    {
        $uuid1    = Uuid::uuid4()->getNodeHex();
        $info     = $request->get('info');
        $company  = $request->get('company');
        $username = $request->get('username');
        $password = $request->get('password');
        $mobile   = $request->get('mobile');
        $fee      = $request->get('fee');
        // 密码加密处理
        $passwd = md5(md5($password . 'jbh') . '2019');
        //验证开始
        if (empty($fee)) {
            $fee = 0;
        } else {
            if (!is_numeric($fee)) {
                return ['code' => 50000, 'message' => '提现手续费格式不正确'];
            } else {
                if ($fee < 0) {
                    return ['code' => 50000, 'message' => '提现手续费格式不正确'];
                }
            }
        }
        if (Account::checkRowExists(['username' => $username])) {
            return ['code' => 50000, 'message' => '该商户账号已经被注册，请您换一个商户账号作为用户名'];
        }
        //验证结束
        DB::beginTransaction();
        try {
            $data       = Account::AddData([
                'username' => $username,
                'password' => $passwd,
                'mobile'   => $mobile,
                'level'    => 2, //商户
                'role_id'  => 0,
                'status'   => 1,
            ]);
            $account_id = $data['id'];
            // 拼接开发者的appid
            $develop_appid = 'jbh' . $account_id . $uuid1;
            // 添加用户信息
            AccountInfo::AddData([
                'account_id'    => $account_id,
                'company'       => $company,
                'develop_appid' => $develop_appid
            ]);
            // 创建用户钱包
            AccountWallet::AddData([
                'account_id' => $account_id,
                'amount'     => 0,
                'fee'        => $fee
            ]);
            // 添加操作日志
            OperationLog::AddData([
                'type'       => 1,
                'account_id' => $info['id'],
                'content'    => '创建了一个合作商户，商户的ID为【' . $account_id . '】--' . '商户的账号为【' . $username . '】'
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            OperationLog::AddData([
                'type'       => 1,
                'account_id' => $info['id'],
                'content'    => '创建合作商户失败，商户名称为【' . $company . '】--' . '商户的账号为【' . $username . '】'
            ]);
            Logs::error('创建合作商户失败', $e);
            return ['code' => 50000, 'message' => '创建合作商户失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '恭喜您！创建成功'];
    }

    /**
     * 合作商户列表
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:52
     */
    public function list(Request $request)
    {
        $page  = $request->get('page');
        $limit = $request->get('limit');
        $list  = Account::where([['account.level', '<>', 1]])
            ->leftJoin('account_info as info', function ($join) {
                $join->on('account.id', '=', 'info.account_id');
            })
            ->leftJoin('account_wallet as wallet', function ($join) {
                $join->on('account.id', '=', 'wallet.account_id');
            })
            ->select(['account.*', 'info.company', 'wallet.amount', 'wallet.fee'])
            ->orderBy('account.created_at', 'DESC')
            ->paginate($limit, $page);
        return ['code' => 20000, 'data' => $list];
    }


    /**
     * 编辑合作商户相关信息
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:52
     */
    public function edit(Request $request)
    {
        $info     = $request->get('info');
        $id       = $request->get('id');
        $name     = $request->get('name');
        $mobile   = $request->get('mobile');
        $password = $request->get('password');
        $fee      = $request->get('fee');
        if ($info['id'] == 1) {
            if (!is_numeric($fee)) {
                return ['code' => 50000, 'message' => '提现手续费格式不正确'];
            } else {
                if ($fee < 0) {
                    return ['code' => 50000, 'message' => '提现手续费格式不正确'];
                }
            }
        }
        DB::beginTransaction();
        try {
            // 修改商户密码，并记录修改的操作记录
            if ($password) {
                // 密码加密处理
                $passwd = md5(md5($password . 'jbh') . '2019');
                Account::EditData(['id' => $id], ['password' => $passwd]);
                OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => "修改了商户ID为【{$id}】的合作商户密码！"]);
            }

            // 修改商户名称，并记录修改的操作记录
            $company = AccountInfo::getValue(['account_id' => $id], 'company');
            if ($company != $name) {
                AccountInfo::EditData(['account_id' => $id], ['company' => $name]);
                OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => "修改了商户ID为【{$id}】的合作商户名称，新名称为【{$name}】"]);
            }

            // 修改手续费率，并记录修改的操作记录
            $old_fee = AccountWallet::getValue(['account_id' => $id], 'fee');
            if ($fee && $info['id'] == 1 || $fee == 0 && $info['id'] == 1) {
                if ($fee != $old_fee) {
                    AccountWallet::EditData(['account_id' => $id], ['fee' => $fee]);
                    OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => "修改了商户ID为【{$id}】的合作商户提现费率，新的费率为【{$fee}%】！"]);
                }
            }

            // 修改商户手机号码，并记录修改的操作记录
            $old_mobile = Account::getValue(['id' => $id], 'mobile');
            if ($old_mobile != $mobile) {
                Account::EditData(['id' => $id], ['mobile' => $mobile]);
                OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => "修改了商户ID为【{$id}】的合作商户手机号码，新的手机号码为【{$mobile}】！"]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::error('编辑合作商相关信息失败', $e);
            return ['code' => 50000, 'message' => '修改失败，请稍后再试'];
        }
        return ['code' => 20000, 'message' => '操作成功'];
    }

}
