<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Requests\Merchant\EditMobile;
use App\Library\Logs;
use App\Models\Account;
use App\Models\AccountInfo;
use App\Models\AccountWallet;
use App\Models\BankInfo;
use App\Models\BankInfoLog;
use App\Models\OperationLog;
use App\Models\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class MerchantController extends Controller
{
    // 商户基本信息
    public function info(Request $request)
    {
        $info    = $request->get('info');
        $account = Account::where(['account.id' => $info['id']])
            ->leftjoin('account_info', function ($join) {
                $join->on('account.id', '=', 'account_info.account_id');
            })
            ->select(['account.username', 'account.mobile', 'account_info.*'])
            ->first()
            ->toArray();
        return ['code' => 20000, 'data' => $account];
    }


    /**
     * 结算信息
     * @param Request $request
     * @return array
     * @author: iszmxw <mail@54zm.com>
     * @Date：2019/12/5 16:41
     */
    public function bank_info(Request $request)
    {
        $info      = $request->get('info');
        $bank_info = BankInfo::where(['bank_info.account_id' => $info['id']])
            ->leftJoin('account_info', function ($join) {
                $join->on('bank_info.account_id', '=', 'account_info.account_id');
            })
            ->select(['account_info.company', 'bank_info.*'])
            ->first();
        $fee       = AccountWallet::getValue(['account_id' => $info['id']], 'fee');
        return ['code' => 20000, 'data' => ['bank_info' => $bank_info, 'fee' => $fee]];
    }


    /**
     * 编辑银行卡信息
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author: iszmxw <mail@54zm.com>
     * @Date：2019/12/5 16:41
     */
    public function set_bank_info(Request $request)
    {
        $name     = $request->get('name');
        $number   = $request->get('number');
        $bankname = $request->get('bankname');
        $remarks  = $request->get('remarks');
        $type     = 1;
        $code     = $request->get('code');
        $info     = $request->get('info');
        $company  = AccountInfo::getValue(['account_id' => $info['id']], 'company');
        $mobile   = Account::getValue(['id' => $info['id']], 'mobile');
        $sms      = Sms::getOne(['mobile' => $mobile, 'code' => $code, 'status' => 0]);
        if (empty($sms)) {
            return ['code' => 50000, 'message' => '验证码不正确'];
        }
        // 开启事务回滚
        DB::beginTransaction();
        try {
            // 判断是否存在用户的银行卡信息
            if (BankInfo::checkRowExists(['account_id' => $info['id']])) {
                BankInfo::EditData([
                    'account_id' => $info['id']
                ], [
                    'type'     => $type,
                    'name'     => $name,
                    'number'   => $number,
                    'bankname' => $bankname,
                    'remarks'  => $remarks
                ]);
            } else {
                // 首次设置用户的银行卡信息
                BankInfo::AddData([
                    'account_id' => $info['id'],
                    'type'       => $type,
                    'name'       => $name,
                    'number'     => $number,
                    'bankname'   => $bankname,
                    'remarks'    => $remarks
                ]);
            }
            // 添加银行卡历史日志
            BankInfoLog::AddData([
                'account_id' => $info['id'],
                'company'    => $company,
                'type'       => $type,
                'name'       => $name,
                'number'     => $number,
                'bankname'   => $bankname,
                'remarks'    => $remarks
            ]);
            // 消费验证码
            Sms::EditData(['id' => $sms['id']], ['status' => 1]);
            OperationLog::AddData(['type' => 2, 'account_id' => $info['id'], 'content' => '修改了结算信息!']);
            DB::commit();
        } catch (\Exception $e) {
            dump($e);
            DB::rollBack();
            return ['code' => 50000, 'message' => '操作失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '修改成功'];
    }


    /**
     * @param EditMobile $request
     * @return array
     * @throws \Exception
     * 合作商修改手机号码
     */
    public function edit_mobile(EditMobile $request)
    {
        $info       = $request->get('info');
        $old_code   = $request->get('old_code');
        $new_code   = $request->get('new_code');
        $old_mobile = $request->get('old_mobile');
        $new_mobile = $request->get('new_mobile');

        $old = ['mobile' => $old_mobile, 'code' => $old_code, 'status' => 0];

        $new = ['mobile' => $new_mobile, 'code' => $new_code, 'status' => 0];


        if ($old_mobile == $new_mobile) {
            return ['code' => 50000, 'message' => '新手机号码不能与旧手机号码一样！'];
        }

        if (Account::checkRowExists(['mobile' => $new_mobile])) {
            return ['code' => 50000, 'message' => '新手机号码已经有账号在使用，请您换个新号码！'];
        }

        if (!Sms::checkRowExists($old)) {
            return ['code' => 50000, 'message' => '原手机验证码不正确！'];
        }
        if (!Sms::checkRowExists($new)) {
            return ['code' => 50000, 'message' => '新手机验证码不正确！'];
        }
        // 开启数据库操作==事务回滚
        DB::beginTransaction();
        try {
            // 消费验证码
            Sms::EditData($old, ['status' => 1]);
            Sms::EditData($new, ['status' => 1]);
            Account::EditData(['id' => $info['id']], ['mobile' => $new_mobile]);
            OperationLog::AddData(['type' => 2, 'account_id' => $info['id'], 'content' => '修改了手机号码！']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ['code' => 50000, 'message' => '操作失败，请稍后再试！'];
        }

        return ['code' => 20000, 'message' => '手机号码修改成功！,为了安全，请您退出后重新登录'];

    }


    /**
     * @param EditMobile $request
     * @return array
     * @throws \Exception
     * 合作商修改手机号码
     */
    public function re_password(Request $request)
    {
        $info     = $request->get('info');
        $code     = $request->get('code');
        $password = $request->get('password');
        $mobile   = Account::getValue(['id' => $info['id']], 'mobile');

        $code_where = ['mobile' => $mobile, 'code' => $code, 'status' => 0];

        if (mb_strlen($password) < 6) {
            return ['code' => 50000, 'message' => '登录密码不能小于6位！'];
        }

        if (!Sms::checkRowExists($code_where)) {
            return ['code' => 50000, 'message' => '手机验证码不正确！'];
        }
        // 密码加密处理
        $passwd = md5(md5($password . 'jbh') . '2019');

        // 开启数据库操作==事务回滚
        DB::beginTransaction();
        try {
            // 消费验证码
            Sms::EditData($code_where, ['status' => 1]);
            Account::EditData(['id' => $info['id']], ['password' => $passwd]);
            OperationLog::AddData(['type' => 2, 'account_id' => $info['id'], 'content' => '重置了登录密码！']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::error('重置了登录密码', $e);
            return ['code' => 50000, 'message' => '操作失败，请稍后再试！'];
        }

        return ['code' => 20000, 'message' => '登录密码修改成功！'];

    }
}
