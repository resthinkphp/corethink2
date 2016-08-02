<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\common\controller;
use think\Controller;
/**
 * 公共控制器
 * @author jry <598821125@qq.com>
 */
class Common extends Controller {
    /**
     * 模板显示 调用内置的模板引擎显示方法，
     * @access protected
     * @param string $templateFile 指定要调用的模板文件
     * 默认为空 由系统自动定位模板文件
     * @param string $charset 输出编码
     * @param string $contentType 输出类型
     * @param string $content 输出内容
     * @param string $prefix 模板缓存前缀
     * @return void
     */
    protected function display($template = '', $charset = '', $contentType = '', $content = '', $prefix = '') {
        if (!is_file($template)) {
            $depr = C('TMPL_FILE_DEPR');
            if ('' == $template) {
                // 如果模板文件名为空 按照默认规则定位
                $template = CONTROLLER_NAME . $depr . ACTION_NAME;
            } elseif (false === strpos($template, $depr)) {
                $template = CONTROLLER_NAME . $depr . $template;
            }
        } else {
            $file = $template;
        }

        // Wap主题
        $system_config = array();
        $current_theme = C('CURRENT_THEME');
        $current_domain = C('CURRENT_DOMAIN');
        $is_builder    = $this->view->get('is_builder');
        if ($current_theme) {
            if (C('IS_WAP')) {
                $parent_theme_path = './Theme/'.$current_theme.'/'.MODULE_NAME.'/'; //当前主题文件夹路径
                $current_theme_path = './Theme/'.$current_theme.'/'.MODULE_NAME.'/Wap/'; //当前主题文件夹路径
                $system_config['TMPL_PARSE_STRING'] = C('TMPL_PARSE_STRING');  // 先取出配置文件中定义的否则会被覆盖
                if ($is_builder) {
                    // 前台Home模块静态资源路径及模板继承基本模板(Wap版)
                    $home_public_path_wap = './Theme/'.$current_theme.'/Home/Wap/Public';
                    if (is_dir($home_public_path_wap)) {
                        $system_config['HOME_PUBLIC_LAYOUT'] = $home_public_path_wap.'/layout.html';
                        $system_config['HOME_PUBLIC_MODAL']  = $home_public_path_wap.'/modal.html';
                        $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']   = $current_domain.'/'.$home_public_path_wap.'/img';
                        $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']   = $current_domain.'/'.$home_public_path_wap.'/css';
                        $system_config['TMPL_PARSE_STRING']['__HOME_JS__']    = $current_domain.'/'.$home_public_path_wap.'/js';
                        $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__']  = $current_domain.'/'.$home_public_path_wap.'/libs';

                        // Builder配置
                        if (MODULE_MARK === 'Home') {
                            $builder_path = './Theme/'.$current_theme.'/Home/Wap/Builder';
                            if (is_dir($builder_path)) {
                                $system_config['LISTBUILDER_LAYOUT'] = $builder_path.'/listbuilder.html';
                                $system_config['FORMBUILDER_LAYOUT'] = $builder_path.'/formbuilder.html';
                            }

                            // 如果当前主题存在User模板则改变相关配置(Wap版)
                            if (is_dir('./Theme/' . $current_theme . '/User/Wap')) {
                                $system_config['USER_CENTER_SIDE'] = './Theme/'.$current_theme.'/User/Wap/Index/side.html';
                                $system_config['USER_CENTER_INFO'] = './Theme/'.$current_theme.'/User/Wap/Index/info.html';

                                if (is_dir('./Theme/'.$current_theme.'/User/Wap/Builder')) {
                                    $system_config['USER_CENTER_FORM'] = './Theme/'.$current_theme.'/User/Wap/Builder/form.html';
                                    $system_config['USER_CENTER_LIST'] = './Theme/'.$current_theme.'/User/Wap/Builder/list.html';

                                    // 重置Builder模板
                                    if ('list' === $is_builder) {
                                        $template = $system_config['USER_CENTER_LIST'];
                                    }
                                    if ('form' === $is_builder) {
                                        $template = $system_config['USER_CENTER_FORM'];
                                    }
                                }
                            }
                        }

                        // 模板文件夹
                        if (MODULE_MARK === 'Home') {
                            $system_config['VIEW_PATH'] = $current_theme_path;
                        } else if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
                            if (is_dir($current_theme_path.'Admin/')) {
                                $system_config['VIEW_PATH'] = $current_theme_path.'Admin/';
                            }
                        }

                        // 各模块自带静态资源路径
                        $module_public_path_wap = './Theme/' . $current_theme . '/' . MODULE_NAME . '/Wap/Public';
                        if (is_dir($module_public_path_wap)) {
                            $system_config['TMPL_PARSE_STRING']['__IMG__']  = $current_domain.'/'.$module_public_path_wap.'/img';
                            $system_config['TMPL_PARSE_STRING']['__CSS__']  = $current_domain.'/'.$module_public_path_wap.'/css';
                            $system_config['TMPL_PARSE_STRING']['__JS__']   = $current_domain.'/'.$module_public_path_wap.'/js';
                            $system_config['TMPL_PARSE_STRING']['__LIBS__'] = $current_domain.'/'.$module_public_path_wap.'/libs';
                        }
                    }
                } else {
                    // 特殊模板处理
                    if (!$file) {
                        $file = './Theme/' . $current_theme . '/'. MODULE_NAME . '/Wap/' . $template . '.html';
                        if (!is_file($file) && is_dir($parent_theme_path) && !defined('IS_ADDON')) {
                            // 前台Home模块静态资源路径及模板继承基本模板(Wap版)
                            $home_public_path_wap = './Theme/'.$current_theme.'/Home/Public';
                            if (is_dir($home_public_path_wap)) {
                                $system_config['HOME_PUBLIC_LAYOUT'] = $home_public_path_wap.'/layout.html';
                                $system_config['HOME_PUBLIC_MODAL']  = $home_public_path_wap.'/modal.html';
                                $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']   = $current_domain.'/'.$home_public_path_wap.'/img';
                                $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']   = $current_domain.'/'.$home_public_path_wap.'/css';
                                $system_config['TMPL_PARSE_STRING']['__HOME_JS__']    = $current_domain.'/'.$home_public_path_wap.'/js';
                                $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__']  = $current_domain.'/'.$home_public_path_wap.'/libs';

                                // Builder配置
                                if (MODULE_MARK === 'Home') {
                                    $builder_path = './Theme/'.$current_theme.'/Home/Builder';
                                    if (is_dir($builder_path)) {
                                        $system_config['LISTBUILDER_LAYOUT'] = $builder_path.'/listbuilder.html';
                                        $system_config['FORMBUILDER_LAYOUT'] = $builder_path.'/formbuilder.html';
                                    }

                                    // 如果当前主题存在User模板则改变相关配置(Wap版)
                                    if (is_dir('./Theme/' . $current_theme . '/User')) {
                                        $system_config['USER_CENTER_SIDE'] = './Theme/'.$current_theme.'/User/Index/side.html';
                                        $system_config['USER_CENTER_INFO'] = './Theme/'.$current_theme.'/User/Index/info.html';
                                    }
                                }

                                // 模板文件夹
                                if (MODULE_MARK === 'Home') {
                                    $system_config['VIEW_PATH'] = $parent_theme_path;
                                } else if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
                                    if (is_dir($current_theme_path.'Admin/')) {
                                        $system_config['VIEW_PATH'] = $parent_theme_path.'Admin/';
                                    }
                                }

                                // 各模块自带静态资源路径
                                $module_public_path_wap = './Theme/' . $current_theme . '/' . MODULE_NAME . '/Public';
                                if (is_dir($module_public_path_wap)) {
                                    // 模块支持自定义layout
                                    if (is_file($module_public_path_wap . '/layout.html') && MODULE_NAME !== 'Home') {
                                        $system_config['HOME_PUBLIC_LAYOUT'] = $module_public_path_wap . '/layout.html';
                                    }
                                    $system_config['TMPL_PARSE_STRING']['__IMG__']  = $current_domain.'/'.$module_public_path_wap.'/img';
                                    $system_config['TMPL_PARSE_STRING']['__CSS__']  = $current_domain.'/'.$module_public_path_wap.'/css';
                                    $system_config['TMPL_PARSE_STRING']['__JS__']   = $current_domain.'/'.$module_public_path_wap.'/js';
                                    $system_config['TMPL_PARSE_STRING']['__LIBS__'] = $current_domain.'/'.$module_public_path_wap.'/libs';
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if (C('IS_WAP')) {
                $parent_theme_path = APP_PATH.MODULE_NAME.'/View/'; //当前主题文件夹路径
                $current_theme_path = APP_PATH.MODULE_NAME.'/View/Wap/'; //当前主题文件夹路径
                $system_config['TMPL_PARSE_STRING'] = C('TMPL_PARSE_STRING');  // 先取出配置文件中定义的否则会被覆盖
                if ($is_builder) {
                    // 前台Home模块静态资源路径及模板继承基本模板(Wap版)
                    $home_public_path_wap = APP_PATH.'/Home/View/Wap/Public';
                    if (is_dir($home_public_path_wap)) {
                        $system_config['HOME_PUBLIC_LAYOUT'] = $home_public_path_wap.'/layout.html';
                        $system_config['HOME_PUBLIC_MODAL']  = $home_public_path_wap.'/modal.html';
                        $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']   = $current_domain.'/'.$home_public_path_wap.'/img';
                        $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']   = $current_domain.'/'.$home_public_path_wap.'/css';
                        $system_config['TMPL_PARSE_STRING']['__HOME_JS__']    = $current_domain.'/'.$home_public_path_wap.'/js';
                        $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__']  = $current_domain.'/'.$home_public_path_wap.'/libs';

                        // Builder配置
                        if (MODULE_MARK === 'Home') {
                            $builder_path = APP_PATH.'/Home/View/Wap/Builder';
                            if (is_dir($builder_path)) {
                                $system_config['LISTBUILDER_LAYOUT'] = $builder_path.'/listbuilder.html';
                                $system_config['FORMBUILDER_LAYOUT'] = $builder_path.'/formbuilder.html';
                            }

                            // 如果当前主题存在User模板则改变相关配置(Wap版)
                            if (is_dir(APP_PATH.'/User/View/Wap')) {
                                $system_config['USER_CENTER_SIDE'] = APP_PATH.'User/View/Wap/Index/side.html';
                                $system_config['USER_CENTER_INFO'] = APP_PATH.'User/View/Wap/Index/info.html';

                                if (is_dir(APP_PATH.'/User/View/Wap/Builder')) {
                                    $system_config['USER_CENTER_FORM'] = APP_PATH.'User/View/Wap/Builder/form.html';
                                    $system_config['USER_CENTER_LIST'] = APP_PATH.'User/View/Wap/Builder/list.html';

                                    // 重置Builder模板
                                    if ('list' === $is_builder) {
                                        $template = $system_config['USER_CENTER_LIST'];
                                    }
                                    if ('form' === $is_builder) {
                                        $template = $system_config['USER_CENTER_FORM'];
                                    }
                                }
                            }
                        }

                        // 模板文件夹
                        if (MODULE_MARK === 'Home') {
                            $system_config['VIEW_PATH'] = $current_theme_path;
                        } else if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
                            if (is_dir($current_theme_path.'Admin/')) {
                                $system_config['VIEW_PATH'] = $current_theme_path.'Admin/';
                            }
                        }

                        // 各模块自带静态资源路径
                        $module_public_path_wap = APP_PATH . MODULE_NAME . '/View/Wap/Public';
                        if (is_dir($module_public_path_wap)) {
                            $system_config['TMPL_PARSE_STRING']['__IMG__']  = $current_domain.'/'.$module_public_path_wap.'/img';
                            $system_config['TMPL_PARSE_STRING']['__CSS__']  = $current_domain.'/'.$module_public_path_wap.'/css';
                            $system_config['TMPL_PARSE_STRING']['__JS__']   = $current_domain.'/'.$module_public_path_wap.'/js';
                            $system_config['TMPL_PARSE_STRING']['__LIBS__'] = $current_domain.'/'.$module_public_path_wap.'/libs';
                        }
                    }
                } else {
                    // 特殊模板处理
                    if (!$file) {
                        $file = APP_PATH. MODULE_NAME . '/View/Wap/' . $template . '.html';
                        if (!is_file($file) && !defined('IS_ADDON')) {
                            // 前台Home模块静态资源路径及模板继承基本模板(Wap版)
                            $home_public_path_wap = APP_PATH.'Home/View/Public';
                            if (is_dir($home_public_path_wap)) {
                                $system_config['HOME_PUBLIC_LAYOUT'] = $home_public_path_wap.'/layout.html';
                                $system_config['HOME_PUBLIC_MODAL']  = $home_public_path_wap.'/modal.html';
                                $system_config['TMPL_PARSE_STRING']['__HOME_IMG__']   = $current_domain.'/'.$home_public_path_wap.'/img';
                                $system_config['TMPL_PARSE_STRING']['__HOME_CSS__']   = $current_domain.'/'.$home_public_path_wap.'/css';
                                $system_config['TMPL_PARSE_STRING']['__HOME_JS__']    = $current_domain.'/'.$home_public_path_wap.'/js';
                                $system_config['TMPL_PARSE_STRING']['__HOME_LIBS__']  = $current_domain.'/'.$home_public_path_wap.'/libs';

                                // Builder配置
                                if (MODULE_MARK === 'Home') {
                                    $builder_path = APP_PATH.'Common/Builder';
                                    $system_config['LISTBUILDER_LAYOUT'] = $builder_path.'/listbuilder.html';
                                    $system_config['FORMBUILDER_LAYOUT'] = $builder_path.'/formbuilder.html';
                                    $system_config['USER_CENTER_SIDE'] = APP_PATH.'User/View/Index/side.html';
                                    $system_config['USER_CENTER_INFO'] = APP_PATH.'User/View/Index/info.html';
                                }

                                // 模板文件夹
                                if (MODULE_MARK === 'Home') {
                                    $system_config['VIEW_PATH'] = $parent_theme_path;
                                } else if (MODULE_MARK === 'Admin' && MODULE_NAME !== 'Admin') {
                                    if (is_dir($current_theme_path.'Admin/')) {
                                        $system_config['VIEW_PATH'] = $parent_theme_path.'Admin/';
                                    }
                                }
                            }

                            // 模块支持自定义layout
                            if (is_file(APP_PATH.MODULE_NAME.'/View/Public/'.'layout.html') && MODULE_NAME !== 'Home') {
                                $system_config['HOME_PUBLIC_LAYOUT'] = APP_PATH.MODULE_NAME.'/View/Public/'.'layout.html';
                            }
                        }
                    }
                }
            }
        }
        C($system_config);

        // 获取登陆后用户下拉导航
        if (C('APP_SUB_DOMAIN_DEPLOY')) {
            $mod_con = array();
            $mod_con['status'] = 1;
            $mod_con['name'] = MODULE_NAME;
            $_user_nav_main = array();
            $_user_nav_list = D('Admin/Module')->where($mod_con)->getField('user_nav', true);
            foreach ($_user_nav_list as $key => $val) {
                if ($val) {
                    $val = json_decode($val, true);
                    if ($val['main']) {
                        $_user_nav_main = array_merge_recursive($_user_nav_main, $val['main']);
                    }
                    if ($val['center']) {
                        $_user_nav_main = array_merge_recursive($_user_nav_main, $val['center']);
                    }
                }
            }
            if (!$_user_nav_main) {
                $mod_con = array();
                $mod_con['status'] = 1;
                $mod_con['name'] = 'User';
                $_user_nav_main = array();
                $_user_nav_list = D('Admin/Module')->where($mod_con)->getField('user_nav', true);
                foreach ($_user_nav_list as $key => $val) {
                    if ($val) {
                        $val = json_decode($val, true);
                        if ($val['main']) {
                            $_user_nav_main = array_merge_recursive($_user_nav_main, $val['main']);
                        }
                        if ($val['center']) {
                            $_user_nav_main = array_merge_recursive($_user_nav_main, $val['center']);
                        }
                    }
                }
            }
        } else {
            // 获取所有模块配置的用户导航
            $mod_con['status'] = 1;
            $_user_nav_main = array();
            $_user_nav_list = D('Admin/Module')->where($mod_con)->getField('user_nav', true);
            foreach ($_user_nav_list as $key => $val) {
                if ($val) {
                    $val = json_decode($val, true);
                    if ($val['main']) {
                        $_user_nav_main = array_merge_recursive($_user_nav_main, $val['main']);
                    }
                }
            }
        }

        $this->assign('meta_keywords', C('WEB_SITE_KEYWORD'));
        $this->assign('meta_description', C('WEB_SITE_DESCRIPTION'));
        $this->assign('_new_message', cookie('_new_message'));           // 获取用户未读消息数量
        $this->assign('_user_auth', session('user_auth'));               // 用户登录信息
        $this->assign('_user_nav_main', $_user_nav_main);                // 用户导航信息
        $this->assign('_user_center_side', C('USER_CENTER_SIDE'));       // 用户中心侧边
        $this->assign('_user_center_info', C('USER_CENTER_INFo'));       // 用户中心信息
        $this->assign('_admin_public_layout', C('ADMIN_PUBLIC_LAYOUT')); // 页面公共继承模版
        $this->assign('_home_public_layout', C('HOME_PUBLIC_LAYOUT'));   // 页面公共继承模版
        $this->assign('_home_public_modal', C('HOME_PUBLIC_MODAL'));     // 页面公共继承模版
        $this->assign('_listbuilder_layout', C('LISTBUILDER_LAYOUT'));   // ListBuilder继承模版
        $this->assign('_formbuilder_layout', C('FORMBUILDER_LAYOUT'));   // FormBuilder继承模版
        $this->assign('_page_name', strtolower(MODULE_NAME . '_' . CONTROLLER_NAME . '_' . ACTION_NAME));
        $_current_module = D('Admin/Module')->getFieldByName(MODULE_NAME, 'title');  // 当前模块标题
        $this->assign('_current_module', $_current_module);

        if (IS_AJAX) {
            if (is_file($this->view->parseTemplate($template))) {
                $html = $this->fetch($template);
            }
            $this->success('数据获取成功', '', array('data' => $this->view->get(), 'html' => $html));
        } else {
            $this->view->display($template);
        }
    }
}
