<?php
return [
    'backend_name' => '后台管理系统',

    // 路由前缀
    'route_prefix' => 'admin',

    // 如果要修改首页内容，请自行创建路由，并在这里修改路由地址！
    'admin_index_route' => 'admin_index',

    'captcha' => [
        'type' => 'normal', // operation是简答的计算
        // 公用配置
        'width' => 100,
        'height' => 35,
        'points' => 100, // 杂点数量
        'angle' => 10,
        'fontSize' => 15,
        'backgroundColor' => hexdec('2f') . ',' . hexdec('40') . ',' . hexdec('50'),

        // 文字类型的配置
        'captchaOptions' => [
            // letter|blend|zh_cm
            'type' => 'number',
            // 字符列表
//            'showCodes' => '',
//             显示数量
            'size' => 4,

            // 极端类型
            'type' => '+',
            'digit' => 3
        ],
    ],

    // safe | normal
    'auth_mode' => 'safe',

    'except_check_login' => [],

    // 登录用户名或密码错误的最大试错次数
    'max_login_error_count' => 5,

    'except_permission' => [
        'admin_index'
    ],
];