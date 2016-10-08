<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +---------------------------------------------------------------------- 

$_config =  [
    /**
     * 产品配置
     * 根据OpenCMF用户协议：
     * 任何情况下使用OpenCMF均需获取官方授权，违者追究法律责任，授权联系：admin@opencmf.cn
     */
    'PRODUCT_NAME'    => 'CoreThink',                      // 产品名称
    'PRODUCT_LOGO'    => '<b><span class="open" style="color: #a5aeb4;">Core</span><span class="Think" style="color: #2699ed;">Think2</span></b>',  // 产品Logo
    'CURRENT_VERSION' => '2.0.0',                        // 当前版本号
    'DEVELOP_VERSION' => 'beta1',                        // 开发版本号
    'BUILD_VERSION'   => '201607181550',                 // 编译标记
    'PRODUCT_MODEL'   => 'corethink',                     // 产品型号
    'PRODUCT_TITLE'   => '开源版',                        // 产品标题
    'WEBSITE_DOMAIN'  => 'http://www.corethink.cn',      // 官方网址
    'UPDATE_URL'      => '/appstore/home/core/update',   // 官方更新网址
    'COMPANY_NAME'    => '南京科斯克网络科技有限公司',     // 公司名称
    'DEVELOP_TEAM'    => '南京科斯克网络科技有限公司',     // 当前项目开发团队名称

    // 产品简介
    'PRODUCT_INFO'    => 'OpenCMF是一套基于统一核心的通用互联网+信息化服务解决方案，追求简单、高效、卓越。可轻松实现支持多终端的WEB产品快速搭建、部署、上线。系统功能采用模块化、组件化、插件化等开放化低耦合设计，应用商城拥有丰富的功能模块、插件、主题，便于用户灵活扩展和二次开发。',

    // 公司简介
    'COMPANY_INFO'    => '南京科斯克网络科技有限公司是一家新兴的互联网+项目技术解决方案提供商。我们用敏锐的视角洞察IT市场的每一次变革,我们顶着时代变迁的浪潮站在了前沿,以开拓互联网行业新渠道为己任。',

    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    'DATA_CRYPT_TYPE'       => 'Think',
    // 允许访问模块
    'module_allow_list'       => ['home', 'admin', 'install'],

    // 系统功能模板
    'USER_CENTER_SIDE'    => APP_FOLDER.'user/view/index/side.html',
    'USER_CENTER_INFO'    => APP_FOLDER.'user/view/index/info.html',
    'USER_CENTER_FORM'    => APP_FOLDER.'user/view/builder/form.html',
    'USER_CENTER_LIST'    => APP_FOLDER.'user/view/builder/list.html',
    'HOME_PUBLIC_LAYOUT'  => APP_FOLDER.'home/view/public/layout.html',
    'ADMIN_PUBLIC_LAYOUT' => APP_FOLDER.'admin/view/public/layout.html',
    'HOME_PUBLIC_MODAL'   => APP_FOLDER.'home/view/public/modal.html',
    'LISTBUILDER_LAYOUT'  => APP_FOLDER.'common/builder/listbuilder.html',
    'FORMBUILDER_LAYOUT'  => APP_FOLDER.'common/builder/formbuilder.html',

    // 应用命名空间
    'app_namespace'          => 'app',
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => false,
    // 应用模式状态
    'app_status'             => '',
    // 是否支持多模块
    'app_multi_module'       => true,
    // 注册的根命名空间
    'root_namespace'         => [],
    // 扩展配置文件
    'extra_config_list'      => ['route', 'validate'],
    // 扩展函数文件
    'extra_file_list'        => [THINK_PATH . 'helper' . EXT, APP_FOLDER. 'helper' . EXT],
    // 默认输出类型
    'default_return_type'    => 'html',
    // 默认AJAX 数据返回格式,可选json xml ...
    'default_ajax_return'    => 'json',
    // 默认JSONP格式返回的处理方法
    'default_jsonp_handler'  => 'jsonpReturn',
    // 默认JSONP处理方法
    'var_jsonp_handler'      => 'callback',
    // 默认时区
    'default_timezone'       => 'PRC',
    // 是否开启多语言
    'lang_switch_on'         => false,
    // 默认全局过滤方法 用逗号分隔多个
    'default_filter'         => '',
    // 默认语言
    'default_lang'           => 'zh-cn',
    // 是否启用控制器类后缀
    'controller_suffix'      => false,

    // +----------------------------------------------------------------------
    // | 模块设置
    // +----------------------------------------------------------------------

    // 默认模块名
    'default_module'         => 'home',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Index',
    // 默认操作名
    'default_action'         => 'index',
    // 默认验证器
    'default_validate'       => '',
    // 默认的空控制器名
    'empty_controller'       => 'Error',
    // 操作方法后缀
    'action_suffix'          => '',
    // 自动搜索控制器
    'controller_auto_search' => false,

    // +----------------------------------------------------------------------
    // | URL设置
    // +----------------------------------------------------------------------

    // PATHINFO变量名 用于兼容模式
    'var_pathinfo'           => 's',
    // 兼容PATH_INFO获取
    'pathinfo_fetch'         => ['ORIG_PATH_INFO', 'REDIRECT_PATH_INFO', 'REDIRECT_URL'],
    // pathinfo分隔符
    'pathinfo_depr'          => '/',
    // URL伪静态后缀
    'url_html_suffix'        => 'html',
    // URL普通方式参数 用于自动生成
    'url_common_param'       => false,
    // URL参数方式 0 按名称成对解析 1 按顺序解析
    'url_param_type'         => 0,
    // 是否开启路由
    'url_route_on'           => true,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 域名部署
    'url_domain_deploy'      => false,
    // 域名根，如.thinkphp.cn
    'url_domain_root'        => '',
    // 是否自动转换URL中的控制器和操作名
    'url_convert'            => true,
    // 默认的访问控制器层
    'url_controller_layer'   => 'controller',
    // 表单请求类型伪装变量
    'var_method'             => '_method',

    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------

    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 模板路径
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '<',
        // 标签库标签结束标记
        'taglib_end'   => '>',
        // 预先加载的标签库
        'taglib_pre_load' => '\\app\\home\\taglib\\Lingyun',
    ],

    // 视图输出字符串内容替换
    'view_replace_str'       => array(
        '__ROOT__'       => __ROOT__,
        '__PUBLIC__'     => __ROOT__.'/Public',
        '__LYUI__'       => __ROOT__.'/Public/libs/lyui/dist',
        '__CUI__'        => __ROOT__.'/Public/libs/lyui/dist',
        '__ADMIN_IMG__'  => __ROOT__.'/'.APP_FOLDER.'admin/view/public/img',
        '__ADMIN_CSS__'  => __ROOT__.'/'.APP_FOLDER.'admin/view/public/css',
        '__ADMIN_JS__'   => __ROOT__.'/'.APP_FOLDER.'admin/view/public/js',
        '__ADMIN_LIBS__' => __ROOT__.'/'.APP_FOLDER.'admin/view/public/libs',
        '__HOME_IMG__'   => __ROOT__.'/'.APP_FOLDER.'home/view/public/img',
        '__HOME_CSS__'   => __ROOT__.'/'.APP_FOLDER.'home/view/public/css',
        '__HOME_JS__'    => __ROOT__.'/'.APP_FOLDER.'home/view/public/js',
        '__HOME_LIBS__'  => __ROOT__.'/'.APP_FOLDER.'home/view/public/libs',
    ),
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => THINK_PATH . 'tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'          => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'         => false,
    // 异常处理handle类 留空使用 \think\exception\Handle
    'exception_handle'       => '',

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，支持 file socket
        'type' => 'File',
        // 日志保存目录
        'path' => LOG_PATH,
    ],

    // +----------------------------------------------------------------------
    // | Trace设置
    // +----------------------------------------------------------------------

    'trace'                  => [
        //支持Html Console
        'type' => 'Html',
    ],

    // +----------------------------------------------------------------------
    // | 缓存设置
    // +----------------------------------------------------------------------

    'cache'                  => [
        // 驱动方式
        'type'   => 'File',
        // 缓存保存目录
        'path'   => CACHE_PATH,
        // 缓存前缀
        'prefix' => '',
        // 缓存有效期 0表示永久缓存
        'expire' => 0,
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session'                => [
        'id'             => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix'         => 'think',
        // 驱动方式 支持redis memcache memcached
        'type'           => '',
        // 是否自动开启 SESSION
        'auto_start'     => true,
    ],

    // +----------------------------------------------------------------------
    // | Cookie设置
    // +----------------------------------------------------------------------
    'cookie'                 => [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '',
        // 是否使用 setcookie
        'setcookie' => true,
    ],

    //分页配置
    'paginate'               => [
        'type'      => 'bootstrap',
        'var_page'  => 'page',
        'list_rows' => 15,
    ],
];

// 获取数据库配置信息，手动修改数据库配置请修改./Data/db.php，这里无需改动
if (is_file('./data/db.php')) {
    $db_config = include './data/db.php';  // 包含数据库连接配置
} else {
    // 开启开发部署模式
    if (@$_SERVER[ENV_PRE.'DEV_MODE'] === 'true') {
        // 数据库配置
        $db_config = array(
            'database'  => [
                // 数据库类型
                'type'           => @$_SERVER[ENV_PRE.'DB_TYPE'] ? : 'mysql',
                // 数据库连接DSN配置
                'dsn'            => '',
                // 服务器地址
                'hostname'       => @$_SERVER[ENV_PRE.'DB_HOST'] ? : '127.0.0.1',
                // 数据库名
                'database'       => @$_SERVER[ENV_PRE.'DB_NAME'] ? : 'opencmf',
                // 数据库用户名
                'username'       => @$_SERVER[ENV_PRE.'DB_USER'] ? : 'root',
                // 数据库密码
                'password'       => @$_SERVER[ENV_PRE.'DB_PWD']  ? : '',
                // 数据库连接端口
                'hostport'       => @$_SERVER[ENV_PRE.'DB_PORT'] ? : '3306',
                // 数据库连接参数
                'params'         => [],
                // 数据库编码默认采用utf8
                'charset'        => 'utf8',
                // 数据库表前缀
                'prefix'         => @$_SERVER[ENV_PRE.'DB_PREFIX'] ? : 'oc_',
                // 数据库调试模式
                'debug'          => false,
                // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                'deploy'         => 0,
                // 数据库读写是否分离 主从式有效
                'rw_separate'    => false,
                // 读写分离后 主服务器数量
                'master_num'     => 1,
                // 指定从服务器序号
                'slave_no'       => '',
                // 是否严格检查字段是否存在
                'fields_strict'  => true,
                // 自动写入时间戳字段
                'auto_timestamp' => false,
            ],
        );
    } else {
        // 数据库配置
        $db_config = array(
            'database'  => [
                // 数据库类型
                'type'           => 'mysql',
                // 数据库连接DSN配置
                'dsn'            => '',
                // 服务器地址
                'hostname'       => '127.0.0.1',
                // 数据库名
                'database'       => 'opencmf',
                // 数据库用户名
                'username'       => 'root',
                // 数据库密码
                'password'       => '',
                // 数据库连接端口
                'hostport'       => '',
                // 数据库连接参数
                'params'         => [],
                // 数据库编码默认采用utf8
                'charset'        => 'utf8',
                // 数据库表前缀
                'prefix'         => 'oc_',
                // 数据库调试模式
                'debug'          => false,
                // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                'deploy'         => 0,
                // 数据库读写是否分离 主从式有效
                'rw_separate'    => false,
                // 读写分离后 主服务器数量
                'master_num'     => 1,
                // 指定从服务器序号
                'slave_no'       => '',
                // 是否严格检查字段是否存在
                'fields_strict'  => true,
                // 自动写入时间戳字段
                'auto_timestamp' => false,
            ],
        );
    }
}

// 返回合并的配置
return array_merge(
    $_config,                                      // 系统全局默认配置
    $db_config,                                    // 数据库配置数组
    include APP_FOLDER.'common/builder/config.php'   // 包含Builder配置
);
