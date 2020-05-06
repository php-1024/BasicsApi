<?php

namespace App\Http\Middleware\Admin;

use App\Models\Role;
use App\Models\RoleRoute;
use Closure;
use Illuminate\Support\Facades\Cache;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        self::cors();
        $route = $request->path();
        switch ($route) {
            case 'api/admin/user/login';
            case 'api/admin/user/flush';
            case 'api/admin/hifans/upload_images';
                return $next($request);
                break;
            default;
                $res = self::LoginAndRoleCheck($request);
                return self::Response($res, $next);
                break;
        }
    }


    // 登录状态监测
    public static function LoginCheck($request)
    {
        // 从头部获取token
        $Xtoken = $request->header('Admin-Token');
        // 接收第一次传过来的token
        $token = $request->get('token');
        // token最终结果
        $token = empty($token) ? $Xtoken : $token;
        $info  = Cache::get($token);
        if (empty($info)) {
            return self::ReArray(0, ['code' => 50008, 'message' => '登录过期，无法获取用户详细信息。请您退出后重新登录']);
        } else {
            $expire = time() - $info['refresh_time'];
            // 半个小时后用户还在操作，延长用户的登录时间
            if ($expire > 1800) {
                $info['refresh_time'] = time();
                Cache::put($token, $info, 60);
            }
            // 将登录后的用户信息添加到request中
            $request->attributes->add(['info' => $info]);
            return self::ReArray(1, $request);
        }
    }

    // 检测用户的登录和角色权限
    public static function LoginAndRoleCheck($request)
    {
        $res = self::LoginCheck($request);
        if ($res['status'] == 1) {
            // 如果登录成功检测用户的权限
            return self::RoleCheck($request);
        } else {
            return $res;
        }
    }


    // 用户角色权限检测
    public static function RoleCheck($request)
    {
        $route = $request->path();
        // 从头部获取token
        $Xtoken = $request->header('Admin-Token');
        // 接收第一次传过来的token
        $token = $request->get('token');
        // token最终结果
        $token = empty($token) ? $Xtoken : $token;
        $info  = Cache::get($token);
        // 查找用户可访问路由
        if (empty($info['allow_routes'])) {
            $allow_routes = [];
            $role_id      = $info['role_id'];
            $routes       = Role::getValue(['id' => $role_id], 'routes');
            $routes       = explode(',', $routes);
            $routes       = RoleRoute::where('route', '<>', null)->whereIn('id', $routes)->get(['route'])->toArray();
            foreach ($routes as $key => $val) {
                $allow_routes[] = $val['route'];
            }
            $public_route = config('route.admin');
            // 计算出用户所有允许访问的路由
            $allow_routes = array_merge($allow_routes, $public_route);
            // 缓存角色可访问路由
            $info['allow_routes'] = $allow_routes;
            // 更新登录信息，同时缓存从新开始，1小时后失效
            Cache::put($token, $info, 60);
        } else {
            $allow_routes = $info['allow_routes'];
        }
        // 判断角色是否具备当前请求的路由
        if ($info['id'] != 1 && !in_array($route, $allow_routes)) {
            // 默认所有用户具备权限
//            return self::ReArray(1, $request);
            return self::ReArray(0, ['code' => 50000, 'message' => '抱歉！您不具备权限！']);
        } else {
            return self::ReArray(1, $request);
        }
    }

    // 登录状态监测
    public static function Response($res, Closure $next)
    {
        if ($res['status'] == 1) {
            return $next($res['response']);
        } else {
            return response()->json($res['response']);
        }
    }


    //解决跨域问题
    public static function cors()
    {
        // 允许来自任何来源
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
            // 决定$_SERVER['HTTP_ORIGIN']是否为一个
            // 您希望允许，如果允许：
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // 一天缓存
        }
        // 在选项请求期间接收访问控制头
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        }
    }

    // 中间件返回数据专用
    public static function ReArray($status, $response)
    {
        $arr = [
            'status'   => $status,
            'response' => $response
        ];
        return $arr;
    }
}
