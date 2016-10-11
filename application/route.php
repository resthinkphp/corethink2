<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
    '__pattern__' => [
        'name' => '\w+',
    ],

    // home模块路由
    'page/:id'   => ['home/nav/page', ['method' => 'get'], ['id' => '\d+']],
    'lists/:cid'   => ['home/nav/lists', ['method' => 'get'], ['cid' => '\d+']],
    'post/:id'   => ['home/nav/post', ['method' => 'get'], ['id' => '\d+']],

];
