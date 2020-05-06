<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Models\LoginLog;
use App\Models\OperationLog;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Iszmxw\IpAddress\Address;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{
    /**
     * 登录
     * @param Request $request
     * @return array
     * @throws \Exception
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:52
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
        // 密码输入正确，登录成功 $account['level']==1为管理员用户，仅管理员以登录后台
        if ($account['level'] == 1 && $passwd == $account['password']) {
            $token     = Uuid::uuid1()->getHex();
            $role_name = Role::getValue(['id' => $account['role_id']], 'name');
            // 生成登录用户的信息
            $info = [
                'id'           => $account['id'],
                'token'        => $token,
                'username'     => $account['username'],
                'password'     => $account['password'],
                'level'        => $account['level'],
                'roles'        => 'admin',
                'role_id'      => $account['role_id'],
                'mobile'       => $account['mobile'],
                'login_time'   => time(),
                'refresh_time' => time()
            ];
            DB::beginTransaction();
            try {
                Cache::add($token, $info, 60);
                LoginLog::AddData([
                    'type'       => 1,
                    'account_id' => $info['id'],
                    'username'   => $info['username'],
                    'role'       => $role_name,
                    'ip'         => $address['origip'],
                    'address'    => $address['location'],
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                OperationLog::AddData(['type' => 1, 'account_id' => $account['id'], 'content' => '登陆失败，账号和密码输入不正确']);
                return ['code' => 60204, 'data' => [], 'message' => '登录失败请刷新后再试！' . $e->getMessage()];
            }
            return ['code' => 20000, 'data' => ['token' => $token]];
        } else {
            return ['code' => 60204, 'data' => [], 'message' => '账号密码不正确！'];
        }
    }

    /**
     * 获取登录用户信息
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:52
     */
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

    /**
     * 退出登录
     * @param Request $request
     * @return array
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:52
     */
    public function logout(Request $request)
    {
        $token = $request->get('token');
        Cache::forget($token);
        return ['code' => 20000, 'data' => 'success'];
    }


    /**
     * 清除服务器的所有缓存
     * @author：iszmxw <mail@54zm.com>
     * @Date 2019/10/15 0015
     * @Time：17:53
     */
    public function flush()
    {
        Cache::flush();
    }
}
