<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +---------------------------------------------------------------------- 

//------------------------
// CoreThink 助手函数
//-------------------------

use think\Cache;
use think\Config;
use think\Cookie;
use think\Db;
use think\Debug;
use think\Lang;
use think\Loader;
use think\Log;
use think\Request;
use think\Response;
use think\Session;
use think\Url;
use think\View;

if (!function_exists('C')) {
    /**
     * 获取和设置配置参数
     * @param string|array  $name 参数名
     * @param mixed         $value 参数值
     * @param string        $range 作用域
     * @return mixed
     */
    function C($name = '', $value = null, $range = '')
    {
        if (is_null($value) && is_string($name)) {
            return Config::get($name, $range);
        } else {
            return Config::set($name, $value, $range);
        }
    }
}

if (!function_exists('D')) {
    /**
     * 实例化Model
     * @param string    $name Model名称
     * @param string    $layer 业务层名称
     * @param bool      $appendSuffix 是否添加类名后缀
     * @return \think\Model
     */
    function D($name = '', $layer = 'model', $appendSuffix = false)
    {
        return Loader::model($name, $layer, $appendSuffix);
    }
}

if (!function_exists('U')) {
    /**
     * Url生成
     * @param string        $url 路由地址
     * @param string|array  $value 变量
     * @param bool|string   $suffix 前缀
     * @param bool|string   $domain 域名
     * @return string
     */
    function U($url = '', $vars = '', $suffix = true, $domain = false)
    {
        return Url::build($url, $vars, $suffix, $domain);
    }
}