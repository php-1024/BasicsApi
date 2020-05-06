<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| 这里是您可以为应用程序注册Admin路由的地方。这些
| 路由由RouteServiceProvider在分配了“Admin”中间件组的组中加载。享受构建Admin的乐趣！
|
*/
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {
    Route::group(['prefix' => 'user'], function () {
        // 登录
        Route::any('login', 'LoginController@login');
        // 获取用户信息
        Route::any('info', 'LoginController@info');
        // 退出
        Route::any('logout', 'LoginController@logout');
        // 清除服务器上的所有Cache缓存
        Route::any('flush', 'LoginController@flush');
    });


    Route::group(['prefix' => 'dashboard'], function () {
        // 获取登录日志
        Route::any('login_log', 'DashboardController@login_log');
        // 获取操作日志
        Route::any('operation_log', 'DashboardController@operation_log');
        Route::any('statistics', 'DashboardController@statistics');
        // 修改密码
        Route::any('reset_password', 'DashboardController@reset_password');
    });


    // 商户管理
    Route::group(['prefix' => 'merchant'], function () {
        Route::any('add', 'MerchantController@add');
        Route::any('list', 'MerchantController@list');
        Route::any('edit', 'MerchantController@edit');
        Route::any('status', 'MerchantController@status');
    });


    // 消息管理
    Route::group(['prefix' => 'message'], function () {
        // 合作消息列表
        Route::any('cooperation_list', 'MessageController@cooperation_list');
        // 客户反馈问题列表
        Route::any('hifans_question_list', 'MessageController@hifans_question_list');
        // 客户反馈问题处理
        Route::any('hifans_question_status', 'MessageController@hifans_question_status');
        // 获取问题详情
        Route::any('question_detail', 'MessageController@question_detail');
        // 平台反馈列表
        Route::any('suggest_list', 'MessageController@suggest_list');
        // 平台反馈消息处理
        Route::any('suggest_status', 'MessageController@suggest_status');
        // 合作留言消息处理
        Route::any('cooperation_status', 'MessageController@cooperation_status');
    });

    // 角色管理
    Route::group(['prefix' => 'roles'], function () {
        // 角色列表
        Route::any('list', 'RolesController@list');
        // 角色路由和所有路由
        Route::any('routes', 'RolesController@routes');
        // 编辑角色
        Route::any('edit', 'RolesController@edit');
    });

    // 设备管理
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
        // 编辑设备单价
        Route::any('edit_price', 'DeviceController@edit_price');
    });


    // 商户结算
    Route::group(['prefix' => 'settlement'], function () {
        // 合作商户申请结算列表
        Route::any('company_apply_list', 'EstimateController@company_apply_list');
        // 合作商户提现信息详情获取
        Route::any('company_withdraw_info', 'EstimateController@company_withdraw_info');
        // 审核合作商户结算信息
        Route::any('company_check_withdraw', 'EstimateController@company_check_withdraw');
        // 合作商户结算记录列表
        Route::any('company_apply_list_log', 'EstimateController@company_apply_list_log');
        // 合作商转账确认
        Route::any('company_apply_check', 'EstimateController@company_apply_check');
    });

});
