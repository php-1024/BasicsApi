<?php

namespace App\Http\Middleware\Merchant;

use App\Models\AccountInfo;
use Closure;
use Illuminate\Support\Facades\Cache;

class Merchant
{
    /**
     * 商户中间件
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|mixed
     * @author: iszmxw <mail@54zm.com>
     * @Date：2020/5/6 11:27
     */
    public function handle($request, Closure $next)
    {
        self::cors();
        $route     = $request->path();
        $arr_route = explode('/', $route);
        switch ($route) {
            case 'api/merchant/user/login';
            case 'api/merchant/user/flush';
            case 'api/merchant/sms/ali_sms';
                // 合作商脚本跑月报表
            case 'api/merchant/statistics/command_account_day_chart';
                // 合作商脚本跑日报表
            case 'api/merchant/statistics/command_account_month_chart';
            case 'api/merchant/test/' . end($arr_route);
                return $next($request);
                break;
            // 检测开发者权限请求权限
            case 'api/merchant/commercial/get_qrcode';
            case 'api/merchant/commercial/get_scene';
            case 'api/merchant/commercial/get_city';
                $res = self::DevelopCheck($request);
                return self::Response($res, $next);
                break;
            default;
                $res = self::LoginCheck($request);
                return self::Response($res, $next);
                break;
        }
    }


    // 登录状态监测
    public static function LoginCheck($request)
    {
        // 从头部获取token
        $Xtoken = $request->header('Open-Token');
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

    // 开发者权限检测
    public static function DevelopCheck($request)
    {
        // 接收粉丝万岁合作商开发者的appid
        $develop_appid = $request->get('develop_appid');
        // 本次请求的时间戳
        $timestamp = $request->get('timestamp');
        // 随机字符串
        $nonce         = $request->get('nonce');
        $get_signature = $request->get('signature');
        $app_secret    = AccountInfo::getValue(['develop_appid' => $develop_appid], 'develop_appsecret');
        if (empty($app_secret)) {
            return self::ReArray(0, ['code' => 50003, 'message' => '对不起您的app_secret不正确，请您进入后台重新初始化']);
        } else {
            $array = [
                $timestamp,  //时间戳
                $nonce, //随机字符串
                $app_secret //app_secret
            ];
            sort($array, SORT_STRING);
            $str       = implode($array);
            $signature = md5($str);
            // 签名效验正确
            if ($get_signature == $signature) {
                return self::ReArray(1, $request);
            } else {
//                return self::ReArray(1, $request);
                return self::ReArray(0, ['code' => 50004, 'message' => 'signature校验错误']);
            }
        }
    }

    // 检测用户的登录和角色权限
    public static function LoginAndRoleCheck($request)
    {
        $res = self::LoginCheck($request);
        if ($res['status'] == 1) {
            // 合作商户后台不需要权限检测，默认合作商后台的合作商拥有所有权限
            return self::ReArray(1, $request);
        } else {
            return $res;
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
