<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
// 模块信息配置
return array(
    // 模块信息
    'info' => array(
        'name'        => 'User',
        'title'       => '用户',
        'icon'        => 'fa fa-users',
        'icon_color'  => '#F9B440',
        'description' => '用户中心模块',
        'developer'   => '南京科斯克网络科技有限公司',
        'website'     => 'http://www.opencmf.cn',
        'version'     => '1.4.0',
        'dependences' => array(
            'Admin'   => '1.4.0',
        )
    ),

    // 用户中心导航
    'user_nav' => array(
        'title' => array(
            'center' => '用户',
        ),
        'center' => array(
            '0' => array(
                'title' => '修改信息',
                'icon'  => 'fa fa-edit',
                'url'   => 'User/Center/profile',
                'color' => '#F68A3A',
            ),
            '1' => array(
                'title' => '消息中心',
                'icon'  => 'fa fa-envelope-o',
                'url'   => 'User/Message/index',
                'badge' => array('User/Message', 'newMessageCount'),
                'badge_class' => 'badge-danger',
                'color' => '#80C243',
            ),
            '2' => array(
                'title' => '关注粉丝',
                'icon'  => 'fa fa-users',
                'url'   => 'User/Follow/index',
                'badge' => array('User/Follow', 'newFansCount'),
                'badge_class' => 'badge-danger',
                'color' => '#DC6AC6',
            ),
            '3' => array(
                'title' => '安全中心',
                'icon'  => 'fa fa-shield',
                'url'   => 'User/Safety/index',
                'color' => '#3C9746',
            ),
            '4' => array(
                'title' => '收货地址',
                'icon'  => 'fa fa-map-marker',
                'url'   => 'User/Address/my',
                'color' => '#FDC300',
            ),
        ),
        'main' => array(
            '0' => array(
                'title' => '个人中心',
                'icon'  => 'fa fa-tachometer',
                'url'   => '/User',
            ),
        ),
    ),

    // 模块配置
    'config' => array(
        'status' => array(
            'title'   => '是否开启',
            'type'    => 'radio',
            'options' => array(
                '1' => '开启',
                '0' => '关闭',
            ),
            'value' => '1',
        ),
        'reg_toggle' => array(
            'title'   => '注册开关',
            'type'    => 'radio',
            'options' => array(
                '1'   => '开启',
                '0'   => '关闭',
            ),
            'value'   => '1',
        ),
        'allow_reg_type' => array(
            'title'   => '允许注册类型',
            'type'    =>'checkbox',
            'options' => array(
                'username' => '用户名注册',
                'email'    => '邮箱注册',
                'mobile'   => '手机注册',
            ),
            'value'=> array(
                '0' => 'username',
            ),
        ),
        'deny_username' => array(
            'title'   => '禁止注册的用户名',
            'type'    =>'textarea',
            'value'   => '',
        ),
        'user_protocol' => array(
            'title'   => '用户协议',
            'type'    =>'kindeditor',
            'value'=>'请在“后台——用户——用户设置”中设置',
        ),
        'behavior' => array(
            'title'   => '行为扩展',
            'type'   =>'checkbox',
            'options'=> array(
                'User' => 'User',
            ),
            'value'  => array(
                '0'  => 'User',
            ),
        ),
    ),

    // 后台菜单及权限节点配置
    'admin_menu' => array(
        '1' => array(
            'pid'   => '0',
            'title' => '用户',
            'icon'  => 'fa fa-user',
        ),
        '2' => array(
            'pid'   => '1',
            'title' => '用户管理',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '3' => array(
            'pid'   => '2',
            'title' => '用户设置',
            'icon'  => 'fa fa-wrench',
            'url'   => 'User/Index/module_config',
        ),
        '4' => array(
            'pid'   => '2',
            'title' => '用户统计',
            'icon'  => 'fa fa-area-chart',
            'url'   => 'User/Index/index',
        ),
        '5' => array(
            'pid'   => '2',
            'title' => '用户列表',
            'icon'  => 'fa fa-list',
            'url'   => 'User/User/index',
        ),
        '6' => array(
            'pid'   => '5',
            'title' => '新增',
            'url'   => 'User/User/add',
        ),
        '7' => array(
            'pid'   => '5',
            'title' => '编辑',
            'url'   => 'User/User/edit',
        ),
        '8' => array(
            'pid'   => '5',
            'title' => '设置状态',
            'url'   => 'User/User/setStatus',
        ),
        '9' => array(
            'pid'   => '2',
            'title' => '用户类型',
            'icon'  => 'fa fa-user',
            'url'   => 'User/Type/index',
        ),
        '10' => array(
            'pid'   => '9',
            'title' => '新增',
            'url'   => 'User/Type/add',
        ),
        '11' => array(
            'pid'   => '9',
            'title' => '编辑',
            'url'   => 'User/Type/edit',
        ),
        '12' => array(
            'pid'   => '9',
            'title' => '设置状态',
            'url'   => 'User/Type/setStatus',
        ),
        '13' => array(
            'pid'   => '9',
            'title' => '字段管理',
            'icon'  => 'fa fa-users',
            'url'   => 'User/Attribute/index',
        ),
        '14' => array(
            'pid'   => '13',
            'title' => '新增',
            'url'   => 'User/Attribute/add',
        ),
        '15' => array(
            'pid'   => '13',
            'title' => '编辑',
            'url'   => 'User/Attribute/edit',
        ),
        '16' => array(
            'pid'   => '13',
            'title' => '设置状态',
            'url'   => 'User/Attribute/setStatus',
        ),
        '17' => array(
            'pid'   => '2',
            'title' => '消息管理',
            'icon'  => 'fa fa-envelope-o',
            'url'   => 'User/Message/index',
        ),
        '18' => array(
            'pid'   => '17',
            'title' => '新增',
            'url'   => 'User/Message/add',
        ),
        '19' => array(
            'pid'   => '17',
            'title' => '编辑',
            'url'   => 'User/Message/edit',
        ),
        '20' => array(
            'pid'   => '17',
            'title' => '设置状态',
            'url'   => 'User/Message/setStatus',
        ),
        '21' => array(
            'pid'   => '1',
            'title' => '用户纪录',
            'icon'  => 'fa fa-folder-open-o',
        ),
        '22' => array(
            'pid'   => '21',
            'title' => '积分纪录',
            'icon'  => 'fa fa-genderless',
            'url'   => 'User/Log/score',
        ),
        '23' => array(
            'pid'   => '21',
            'title' => '登录日志',
            'icon'  => 'fa fa-calendar-check-o',
            'url'   => 'User/Log/login',
        ),
        '24' => array(
            'pid'   => '2',
            'title' => '收货地址',
            'icon'  => 'fa fa-map-marker',
            'url'   => 'User/Address/index',
        ),
        '25' => array(
            'pid'   => '24',
            'title' => '新增',
            'url'   => 'User/Address/add',
        ),
        '26' => array(
            'pid'   => '24',
            'title' => '编辑',
            'url'   => 'User/Address/edit',
        ),
        '27' => array(
            'pid'   => '24',
            'title' => '设置状态',
            'url'   => 'User/Address/setStatus',
        ),
        '28' => array(
            'pid'   => '2',
            'title' => '实名认证',
            'icon'  => 'fa fa-user',
            'url'   => 'User/Cert/index',
        ),
        '29' => array(
            'pid'   => '28',
            'title' => '新增',
            'url'   => 'User/Cert/add',
        ),
        '30' => array(
            'pid'   => '28',
            'title' => '编辑',
            'url'   => 'User/Cert/edit',
        ),
        '31' => array(
            'pid'   => '28',
            'title' => '设置状态',
            'url'   => 'User/Cert/setStatus',
        ),
        '32' => array(
            'pid'   => '28',
            'title' => '审核',
            'url'   => 'User/Cert/review',
        ),
        '33' => array(
            'pid'   => '28',
            'title' => '取消审核',
            'url'   => 'User/Cert/canel_review',
        ),
    )
);
