<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------

/**
 * Content-type设置
 */
header("Content-type: text/html; charset=utf-8");

/**
 * PHP版本检查
 */
if (version_compare(PHP_VERSION,'5.3.0','<')) {
    die('require PHP > 5.3.0 !');
}

/**
 * PHP报错设置
 */
error_reporting(E_ALL^E_NOTICE^E_WARNING);

/**
 * 开发模式环境变量前缀
 */
define('ENV_PRE', 'OC_');

/**
 * 定义前台标记
 */
define('MODULE_MARK', 'Home');

/**
 * 包含开发模式数据库连接配置
 */
if (@$_SERVER[ENV_PRE.'DEV_MODE'] !== 'true') {
    @include __DIR__ . '/../data/dev.php';
}


// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
