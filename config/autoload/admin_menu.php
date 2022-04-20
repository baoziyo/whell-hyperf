<?php
/*
 * Sunny 2021/11/30 下午2:45
 * ogg sit down and start building bugs.
 * Author: Ogg <baoziyoo@gmail.com>.
 */

declare(strict_types=1);

return [
    [
        'code' => 'admin',
        'name' => '首页',
        'icon' => '',
        'route' => ['admin', 'admin/index'],
        'children' => [],
    ],
    [
        'code' => 'admin.role',
        'name' => '权限组',
        'route' => 'admin/route',
    ]
];
