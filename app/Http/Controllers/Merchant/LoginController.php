<?php

namespace App\Http\Controllers\Merchant;

use App\Models\Account;
use App\Models\LoginLog;
use App\Models\OperationLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Iszmxw\IpAddress\Address;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     * 登录
     */
    public function login(Request $request)
    {
        // 用户ip
        $ip      = $request->getClientIp();
        $address = Address::address($ip);
        // 接收登录账号和密码
        $username = $request->get('username');
        $password = $request->get('password');
        // 密码加密处理
        $passwd = md5(md5($password . 'jbh') . '2019');
        // 查询账号信息
        $account = Account::getOne(['username' => $username]);
        if ($account['status'] == -1)
            return ['code' => 50000, 'message' => '对不起您的账户已经被冻结，如有疑问请联系相关工作人员！'];
        // 密码输入正确，登录成功 $account['level']==2为合作商户用户，仅合作商可以登录后台
        if ($account['level'] == 2 && $passwd == $account['password']) {
            $token = Uuid::uuid1()->getHex();
            // 生成登录用户的信息
            $info = [
                'id'           => $account['id'],
                'token'        => $token,
                'username'     => $account['username'],
                'password'     => $account['password'],
                'level'        => $account['level'],
                'role_id'      => $account['role_id'],
                'mobile'       => $account['mobile'],
                'login_time'   => time(),
                'refresh_time' => time()
            ];
            if ($account['level'] == 1) {
                $info['roles'] = 'admin';
            } else if ($account['level'] == 2) {
                $info['roles'] = 'company';
            }
            DB::beginTransaction();
            try {
                Cache::add($token, $info, 60);
                LoginLog::AddData([
                    'type'       => 2,//合作商登录
                    'account_id' => $info['id'],
                    'username'   => $info['username'],
                    'role'       => '合作商户',
                    'ip'         => $address['origip'],
                    'address'    => $address['location'],
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                OperationLog::AddData(['type' => 2, 'account_id' => $account['id'], 'content' => '登陆失败，账号和密码输入不正确']);
                return ['code' => 60204, 'data' => [], 'message' => '登录失败请刷新后再试！' . $e->getMessage()];
            }
            return ['code' => 20000, 'data' => ['token' => $token]];
        } else {
            return ['code' => 60204, 'data' => [], 'message' => '账号密码不正确！'];
        }
    }

    // 获取登录用户信息
    public function info(Request $request)
    {
        $info = $request->get('info');
        return [
            'code' => 20000,
            'data' => [
                'roles'        => [$info['roles']],
                'name'         => $info['username'],
                'introduction' => $info['username'],
                'avatar'       => config('app.url') . '/images/user.gif'
            ]
        ];
    }

    // 退出登录
    public function logout(Request $request)
    {
        $token = $request->get('token');
        Cache::forget($token);
        return ['code' => 20000, 'data' => 'success'];
    }


    // 清除服务器的所有缓存
    public function flush()
    {
        Cache::flush();
    }
}
