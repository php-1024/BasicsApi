<?php

return [
    'use_laravel_cache' => true,
    // 阿里大鱼信息
    'DaYuAccessKey' => env('DA_YU_ACCESS_KEY', ''),
    'DaYuAccessSecret' => env('DA_YU_ACCESS_SECRET', ''),
    'TemplateCode' => env('TEMPLATE_CODE', ''),
    // 微信开放平台信息
    'WeChatOpen' => [
        'AppId' => env('APP_ID', ''),
        'AppSecret' => env('APP_SECRET', ''),
        'Token' => env('TOKEN', ''),
        'Aes_Key' => env('AES_KEY', ''),
    ],
    // 微信公众号消息通知
    'OfficialAccount' => [
        'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID', ''),         // AppID
        'secret' => env('WECHAT_OFFICIAL_ACCOUNT_SECRET', ''),    // AppSecret
        'token' => env('WECHAT_OFFICIAL_ACCOUNT_TOKEN', ''),           // Token
        'aes_key' => env('WECHAT_OFFICIAL_ACCOUNT_AES_KEY', ''),                 // EncodingAESKey
        'user_template_id' => env('USER_NOTICE_TEMPLATE_ID', ''),   // 客户模板消息id
        'admin_template_id' => env('ADMIN_NOTICE_TEMPLATE_ID', '')  // 管理员模板消息id
    ],
    // 微信支付相关信息
    'Pay' => [
        'AppId' => env('PAY_APP_ID', ''),
        'MchId' => env('MCH_ID', ''),
        'Key' => env('Pay_Key', ''),
    ]
];