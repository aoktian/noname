<?php
return [
    'task'   => ['name' => '任务管理', 'children' => [
        '/task/index'   => '全部任务',
        '/task/ido'     => '我的任务',
        '/task/icommit' => '我发布的',
        '/task/itest'   => '我要验收',
        '/task/create'  => '发布任务',
    ]],
    'stats'  => ['name' => '数据统计', 'children' => [
        '/stats/index' => '数量统计',
        '/coder/index' => '生产力',
    ]],
    'pro'    => ['name' => '项目管理', 'children' => [
        '/pro/index'   => '项目管理',
        '/tag/index'   => '版本管理',
        '/title/index' => '分类&部门',
    ]],
    'golist' => ['name' => '全貌概览', 'children' => [
        '/golist/index' => '总览',
        '/golist/mod'   => '模块',
        '/golist/group' => '组组组',
        '/golist/task'  => '点点点',
    ]],
    'user'   => ['name' => '成员信息', 'children' => [
        '/user/index'                     => '管理',
        'javascript:ajax(\'/user/add\');' => '添加',
    ]],
    'other'  => ['name' => '其它', 'children' => [
        '/other/abcx/how-to-use' => '如何使用我',
        '/other/abcx/color'      => '给你点颜色',
    ]],
];
