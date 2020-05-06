<?php

/*
|--------------------------------------------------------------------------
| Open Routes
|--------------------------------------------------------------------------
|
| 合作商平台相关的所有路由
| Open开放平台，合作商对接
|
*/

Route::group(['prefix' => 'merchant', 'namespace' => 'Merchant', 'middleware' => 'merchant'], function () {
    Route::group(['prefix' => 'user'], function () {
        // 登录
        Route::any('login', 'LoginController@login');
        // 退出
        Route::any('logout', 'LoginController@logout');
        // 清除服务器上的所有Cache缓存
        Route::any('flush', 'LoginController@flush');
        // 获取用户信息
        Route::any('info', 'LoginController@info');
        // 获取登录日志
        Route::any('login_log', 'DashboardController@login_log');
        // 获取操作日志
        Route::any('operation_log', 'DashboardController@operation_log');
    });


    // 开发权限
    Route::group(['prefix' => 'commercial'], function () {
        // 获取公众号二维码
        Route::any('get_qrcode', 'DevelopController@get_qrcode');
        // 获取场景
        Route::any('get_scene', 'DevelopController@get_scene');
        // 获取所有城市
        Route::any('get_city', 'DevelopController@get_city');
    });


    Route::group(['prefix' => 'merchant'], function () {
        // 角色列表
        Route::any('info', 'MerchantController@info');
        // 获取账户信息
        Route::any('bank_info', 'MerchantController@bank_info');
        // 设置提现信息
        Route::any('set_bank_info', 'MerchantController@set_bank_info');
        // 修改手机号码
        Route::any('edit_mobile', 'MerchantController@edit_mobile');
        // 合作商户重置登录密码
        Route::any('re_password', 'MerchantController@re_password');
    });

    Route::group(['prefix' => 'device'], function () {
        // 获取设备列表
        Route::any('get_device', 'DeviceController@get_device');
        // 获取分类
        Route::any('get_category', 'DeviceController@get_category');
        // 获取地址
        Route::any('get_address', 'DeviceController@get_address');
        // 获取场景
        Route::any('get_scene', 'DeviceController@get_scene');
        // 添加场景
        Route::any('add_device', 'DeviceController@add_device');
        // 编辑设备
        Route::any('edit_device', 'DeviceController@edit_device');
        // 模板详情
        Route::any('template_detail', 'DeviceController@template_detail');
        // 编辑模板
        Route::any('edit_template', 'DeviceController@edit_template');
    });

    Route::group(['prefix' => 'statistics'], function () {
        // 首页数据统计
        Route::any('dashboard', 'StatisticsController@dashboard');
        // 粉丝日报表
        Route::any('fans_day', 'StatisticsController@fans_day');
        // 粉丝月报表
        Route::any('fans_month', 'StatisticsController@fans_month');
        // 粉丝记录
        Route::any('fans_record', 'StatisticsController@fans_record');

        // commands命令生成脚本方法
        Route::any('command_account_day_chart', 'StatisticsController@command_account_day_chart');

        Route::any('command_account_month_chart', 'StatisticsController@command_account_month_chart');
    });


    // 商户结算
    Route::group(['prefix' => 'estimate'], function () {
        // 结算列表
        Route::any('list', 'EstimateController@list');
        // 结算信息
        Route::any('settlement_info', 'EstimateController@settlement_info');
        // 商户提现申请
        Route::any('apply_withdraw', 'EstimateController@apply_withdraw');
        // 月结算概况
        Route::any('month_estimate', 'EstimateController@month_estimate');
    });


    // 短信验证码服务
    Route::group(['prefix' => 'sms'], function () {
        // 通过登录用户的手机号码获取验证码
        Route::any('get_code', 'SmsController@get_code');
        // 通过传递过来的手机号码获取验证码
        Route::any('get_mobile_code', 'SmsController@get_mobile_code');
        Route::any('ali_sms', 'SmsController@ali_sms');
    });

});