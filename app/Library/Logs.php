<?php


namespace App\Library;


use Illuminate\Support\Facades\Log;

class Logs
{
    /**
     * 错误日志封装
     * @param $error_name
     * @param $error_message
     */
    public static function error($error_name, $error_message)
    {
        Log::error("当前路由：" . url()->current() . "\r\n{$error_name}：$error_message");
    }

    /**
     * 调试封装
     * @param $error_name
     * @param $error_message
     */
    public static function debug($error_name, $error_message)
    {
        Log::debug("当前路由：" . url()->current() . "\r\n{$error_name}：$error_message");
    }

    /**
     * 日志记录封装
     * @param $info_name
     * @param $info_message
     */
    public static function info($info_name, $info_message)
    {
        Log::info("当前路由：" . url()->current() . "\r\n{$info_name}：$info_message");
    }

    /**
     * 通知封装
     * @param $notice_name
     * @param $notice_message
     */
    public static function notice($notice_name, $notice_message)
    {
        Log::notice("当前路由：" . url()->current() . "\r\n{$notice_name}：$notice_message");
    }
}