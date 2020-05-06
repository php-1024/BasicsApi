<?php

namespace App\Http\Controllers\Merchant;

use App\Models\LoginLog;
use App\Models\OperationLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    // 登录日志获取
    public function login_log(Request $request)
    {
        $info = $request->get('info');
        $page = $request->get('page');
        $limit = $request->get('limit');
        $where[] = ['type', 2];
        //超级管理员查看系统所有用户的登录记录
        if ($info['id'] != 1) {
            $where[] = ['account_id', $info['id']];
        }
        $list = LoginLog::getPaginate($where, [], ['limit' => $limit, 'page' => $page], 'created_at');
        return ['code' => 20000, 'data' => $list];
    }


    // 操作日志
    public function operation_log(Request $request)
    {
        $info = $request->get('info');
        $page = $request->get('page');
        $limit = $request->get('limit');
        $where[] = ['operation_log.type', 2];
        //超级管理员查看系统所有用户的登录记录
        if ($info['id'] != 1) {
            $where[] = ['operation_log.account_id', $info['id']];
        }
        $list = OperationLog::where($where)
            ->leftJoin('account', function ($join) {
                $join->on('operation_log.account_id', '=', 'account.id');
            })
            ->leftJoin('account_info', function ($join) {
                $join->on('operation_log.account_id', '=', 'account_info.account_id');
            })
            ->select(['account.username', 'account_info.company', 'operation_log.*'])
            ->orderBy('operation_log.created_at', 'DESC')
            ->paginate($limit, $page);
        return ['code' => 20000, 'data' => $list];
    }

}
