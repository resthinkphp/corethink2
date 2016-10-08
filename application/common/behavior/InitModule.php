<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\common\behavior;
defined('THINK_PATH') or exit();
/**
 * 初始化允许访问模块信息
 * @author jry <598821125@qq.com>
 */
class InitModule {
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content) {
        // 安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        // 允许跨域
        header('Access-Control-Allow-Origin: ' . @$_SERVER['HTTP_ORIGIN']);
        header('Access-Control-Allow-Headers: X-Requested-With');
        header("Access-Control-Allow-Credentials: true");

        // 数据缓存前缀
        $config['DATA_CACHE_PREFIX'] = strtolower(ENV_PRE.MODULE_MARK.'_');

        // 获取数据库存储的配置
        $database_config = D('Admin/Config')->lists();

        // URL_MODEL必须在app_init阶段就从数据库读取出来应用
        // 不然系统就会读取config.php中的配置导致后台的配置失效
        $config['URL_MODEL'] = $database_config['URL_MODEL'];

        // 允许访问模块列表加上安装的功能模块
        $module_name_list  = D('Admin/Module')
                           ->where(array('status' => 1, 'is_system' => 0))
                           ->column('name');
        $module_allow_list = array_merge(
            C('MODULE_ALLOW_LIST'),
            $module_name_list
        );
        if (MODULE_MARK === 'Admin') {
            $module_allow_list[] = 'admin';
            $config['URL_MODEL'] = 3;
        }
        C('MODULE_ALLOW_LIST', $module_allow_list);

        // 如果是后台访问自动设置默认模块为Admin
        if (MODULE_MARK === 'Admin') {
            C('default_module', 'admin');
        }

        // API请求
        if (strstr($_SERVER['HTTP_USER_AGENT'], "OpenCMF/api") && MODULE_MARK === 'Home') {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
            C('IS_API', true);
        }

        // 子域名部署
        if (!C('IS_API') && $_SERVER['HTTP_HOST'] !== 'localhost' && $_SERVER['HTTP_HOST'] !== '127.0.0.1') {
            $config['APP_SUB_DOMAIN_DEPLOY'] = $database_config['APP_SUB_DOMAIN_DEPLOY'];
            $config['APP_SUB_DOMAIN_RULES']  = $database_config['APP_SUB_DOMAIN_RULES'];
            if ($database_config['APP_DOMAIN_SUFFIX']) {
                $config['APP_DOMAIN_SUFFIX'] = $database_config['APP_DOMAIN_SUFFIX'];
            }
        }

        // 请求接收为json时认为是ajax请求
        if ($_SERVER['HTTP_ACCEPT'] === 'application/json, text/javascript, */*; q=0.01') {
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        }

        C($config);
    }
}
