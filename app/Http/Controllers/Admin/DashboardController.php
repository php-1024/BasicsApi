<?php

namespace App\Http\Controllers\Admin;

use App\Library\Logs;
use App\Models\Account;
use App\Models\LoginLog;
use App\Models\OperationLog;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * 登录日志获取
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:44
     */
    public function login_log(Request $request)
    {
        $info    = $request->get('info');
        $page    = $request->get('page');
        $limit   = $request->get('limit');
        $where[] = ['type', 1];
        //超级管理员查看系统所有用户的登录记录
        if ($info['id'] != 1) {
            $where[] = ['account_id', $info['id']];
        }
        $list = LoginLog::getPaginate($where, [], ['limit' => $limit, 'page' => $page], 'created_at');
        return ['code' => 20000, 'data' => $list];
    }


    /**
     * 操作日志
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:44
     */
    public function operation_log(Request $request)
    {
        $info    = $request->get('info');
        $page    = $request->get('page');
        $limit   = $request->get('limit');
        $where[] = ['operation_log.type', 1];
        //超级管理员查看系统所有用户的登录记录
        if ($info['id'] != 1) {
            $where[] = ['operation_log.account_id', $info['id']];
        }
        $list = OperationLog::where($where)
            ->leftJoin('account', function ($join) {
                $join->on('operation_log.account_id', '=', 'account.id');
            })
            ->leftJoin('role', function ($join) {
                $join->on('role.id', '=', 'account.role_id');
            })
            ->select(['account.username', 'role.name as role_name', 'operation_log.*'])
            ->orderBy('operation_log.created_at', 'DESC')
            ->paginate($limit, $page);
        return ['code' => 20000, 'data' => $list];
    }


    /**
     * 统计
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:44
     */
    public function statistics(Request $request)
    {
        // 合作商户
        $company_total = Account::getCount(['level' => 2]);
        // 吸粉客户
        $hifans_total = User::getCount();
        return ['code' => 20000, 'data' => ['company_total' => $company_total, 'hifans_total' => $hifans_total]];
    }


    /**
     * 修改登录密码
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:44
     */
    public function reset_password(Request $request)
    {
        $old_password    = $request->get('old_password');
        $new_password    = $request->get('new_password');
        $repeat_password = $request->get('repeat_password');
        $info            = $request->get('info');
        if (empty($old_password)) {
            return ['code' => 50000, 'message' => '请输入旧登录密码'];
        }
        if (empty($new_password)) {
            return ['code' => 50000, 'message' => '请输入新的登录密码'];
        }
        if (empty($repeat_password)) {
            return ['code' => 50000, 'message' => '请确认新的登录密码'];
        }
        if ($new_password != $repeat_password) {
            return ['code' => 50000, 'message' => '两次密码输入不一致，请您确认后重新输入'];
        }

        // 旧密码加密处理
        $old_passwd = md5(md5($old_password . 'jbh') . '2019');
        // 新密码加密处理
        $new_passwd = md5(md5($new_password . 'jbh') . '2019');
        // 查询账号信息
        $account = Account::getOne(['id' => $info['id']]);
        if ($old_passwd != $account['password']) {
            return ['code' => 50000, 'message' => '旧密码输入不正确，请您确认后重新输入'];
        }
        DB::beginTransaction();
        try {
            Account::EditData(['id' => $info['id']], ['password' => $new_passwd]);
            OperationLog::AddData(['type' => 1, 'account_id' => $info['id'], 'content' => "修改了登录密码！"]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Logs::error('修改登录密码', $e);
            return ['code' => 50000, 'message' => '修改失败，请稍后再试！'];
        }
        return ['code' => 20000, 'message' => '修改成功'];
    }
}
