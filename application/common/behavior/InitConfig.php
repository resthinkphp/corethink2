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
 * 根据不同情况读取数据库的配置信息并与本地配置合并
 * 本行为扩展很重要会影响核心系统前后台、模块功能及模版主题使用
 * 如非必要或者并不是十分了解系统架构不推荐更改
 * @author jry <598821125@qq.com>
 */
class InitConfig{
    /**
     * 行为扩展的执行入口必须是run
     * @author jry <598821125@qq.com>
     */
    public function run(&$content) {
        // 安装模式下直接返回
        if(defined('BIND_MODULE') && BIND_MODULE === 'Install') return;

        // 读取数据库中的配置
        $system_config = S('DB_CONFIG_DATA');
        if (!$system_config || APP_DEBUG === true) {
            // 获取所有系统配置
            $system_config = D('Admin/Config')->lists();

            // SESSION与COOKIE与前缀设置避免冲突
            $system_config['SESSION_PREFIX'] = strtolower(ENV_PRE.MODULE_MARK.'_');  // Session前缀
            $system_config['COOKIE_PREFIX']  = strtolower(ENV_PRE.MODULE_MARK.'_');  // Cookie前缀

            // Session数据表
            $system_config['SESSION_TABLE'] = C('DB_PREFIX').'admin_session';

            // 获取所有安装的模块配置
            $module_list = D('Admin/Module')->where(array('status' => '1'))->select();
            foreach ($module_list as $val) {
                $module_config[strtolower($val['name'].'_config')] = json_decode($val['config'], true);
                $module_config[strtolower($val['name'].'_config')]['module_name'] = $val['name'];
            }
            if ($module_config) {
                // 合并模块配置
                $system_config = array_merge($system_config, $module_config);

                // 加载模块标签库及行为扩展
                $system_config['TAGLIB_PRE_LOAD'] = explode(',', C('TAGLIB_PRE_LOAD'));  // 先取出配置文件中定义的否则会被覆盖
                foreach ($module_config as $key => $val) {
                    // 加载模块标签库
                    if ($val['taglib']) {
                        foreach ($val['taglib'] as $tag) {
                            $tag_path = APP_PATH.$val['module_name'].'/'.'TagLib'.'/'.$tag.'.class.php';
                            if (is_file($tag_path)) {
                                $system_config['TAGLIB_PRE_LOAD'][] = $val['module_name'].'\\TagLib\\'.$tag;
                            }
                        }
                    }

                    // 加载模块行为扩展
                    if ($val['behavior']) {
                        foreach ($val['behavior'] as $bhv) {
                            $bhv_path = APP_PATH.$val['module_name'].'/'.'Behavior'.'/'.$bhv.'Behavior.class.php';
                            if (is_file($bhv_path)) {
                                \Think\Hook::add('corethink_behavior', $val['module_name'].'\\Behavior\\'.$bhv.'Behavior');
                            }
                        }
                    }
                }
                $system_config['TAGLIB_PRE_LOAD'] = implode(',', $system_config['TAGLIB_PRE_LOAD']);
            }

            // 加载Formbuilder扩展类型
            $system_config['FORM_ITEM_TYPE'] = C('FORM_ITEM_TYPE');
            $formbuilder_extend = explode(',', D('Admin/Hook')->getFieldByName('FormBuilderExtend', 'addons'));
            if ($formbuilder_extend) {
                $addon_object = D('Admin/Addon');
                foreach ($formbuilder_extend as $val) {
                    $temp = json_decode($addon_object->getFieldByName($val, 'config'), true);
                    if ($temp['status']) {
                        $form_type[$temp['form_item_type_name']] = array($temp['form_item_type_title'], $temp['form_item_type_field']);
                        $system_config['FORM_ITEM_TYPE'] = array_merge($system_config['FORM_ITEM_TYPE'], $form_type);
                    }
                }
            }

            // 授权数据
            $system_config['SN_DECODE'] = \Think\Crypt::decrypt($system_config['AUTH_SN'], sha1(md5($system_config['AUTH_USERNAME'])));

            S('DB_CONFIG_DATA', $system_config, 3600);  // 缓存配置
        }

        // 移动端强制后台传统视图
        if (\app\common\util\Device::isWap()){
            $system_config['IS_WAP'] = true;
            $system_config['ADMIN_TABS'] = 0;
        }
        if ($system_config['WAP_MODE']){
            $system_config['IS_WAP'] = true;
        }

        // 特殊情况下一定要在这里取消子域名部署，因为D('Admin/Config')->lists();会覆盖在InitModuleBehavior里的配置
        if (C('IS_API') || $_SERVER['HTTP_HOST'] === 'localhost' || filter_var($_SERVER['HTTP_HOST'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $system_config['APP_SUB_DOMAIN_DEPLOY'] = 0;
        }

        // 系统主页地址配置
        if ($system_config['APP_SUB_DOMAIN_DEPLOY']) {
            $system_config['TOP_HOME_DOMAIN'] = (is_ssl()?'https://':'http://') . ('localhost' == $_SERVER['HTTP_HOST'] ? 'localhost' : 'www' . strstr($_SERVER['HTTP_HOST'], '.'));
        } else {
            $system_config['TOP_HOME_DOMAIN'] = (is_ssl()?'https://':'http://') . $_SERVER['HTTP_HOST'];
        }
        $system_config['HOME_DOMAIN']   = (is_ssl()?'https://':'http://') . $_SERVER['HTTP_HOST'];
        $system_config['HOME_PAGE']     = $system_config['HOME_DOMAIN'] . __ROOT__;
        $system_config['TOP_HOME_PAGE'] = $system_config['TOP_HOME_DOMAIN'] . __ROOT__;

        // 如果是后台并且不是Admin模块则设置默认控制器层为Admin
        if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
            $system_config['DEFAULT_C_LAYER'] = 'Admin';
            $system_config['VIEW_PATH'] = APP_PATH.MODULE_NAME.'/View/Admin/';
        }

        // 静态资源域名
        if ($system_config['STATIC_DOMAIN']) {
            $current_domain = $system_config['STATIC_DOMAIN'];
        } else {
            $current_domain = $system_config['HOME_PAGE'];
        }
        $system_config['CURRENT_DOMAIN'] = $current_domain;

        // 模版参数配置
        $system_config['TMPL_PARSE_STRING'] = C('TMPL_PARSE_STRING');  // 先取出配置文件中定义的否则会被覆盖
        $system_config['TMPL_PARSE_STRING']['__PUBLIC__'] = $system_config['HOME_DOMAIN'].$system_config['TMPL_PARSE_STRING']['__PUBLIC__'];
        $system_config['TMPL_PARSE_STRING']['__CUI__']    = $system_config['HOME_DOMAIN'].$system_config['TMPL_PARSE_STRING']['__CUI__'];
        $system_config['TMPL_PARSE_STRING']['__IMG__']    = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/img';
        $system_config['TMPL_PARSE_STRING']['__CSS__']    = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/css';
        $system_config['TMPL_PARSE_STRING']['__JS__']     = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/js';
        $system_config['TMPL_PARSE_STRING']['__LIBS__']   = $current_domain.'/'.APP_PATH.MODULE_NAME.'/View/Public/libs';

        // 获取当前主题的名称
        $current_theme = D('Admin/Theme')->where(array('current' => 1))->order('id asc')->getField('name');

        C($system_config);  // 添加配置
    }
}
