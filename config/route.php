<?php

return [
    // 公共路由，所有角色可以请求的路由
    'admin' => [
        // 登录
        'api/admin/user/login',
        // 登录后身份获取
        'api/admin/user/info',
        // 退出
        'api/admin/user/logout',
    ]

];
