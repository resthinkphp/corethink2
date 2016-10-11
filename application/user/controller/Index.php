<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\user\controller;
use app\home\controller\Home;
use common\util\think\Page;
/**
 * 默认控制器
 * @author jry <598821125@qq.com>
 */
class Index extends Home {
    /**
     * 用户个人中心
     * @author jry <598821125@qq.com>
     */
    public function index() {
        if (!C('IS_WAP')) {
            $uid  =  $this->is_login();
        } else {
            $uid  = is_login();
        }
        $template = ''; 
        if ($uid) {
            $user_info = D('Admin/User')->getUserInfo($uid);
            $user_type_info = D('Type')->find($user_info['user_type']);
            if ($user_type_info['center_template']) {
                $template = 'Index/' . $user_type_info['center_template'];
            }
        }
        $this->assign('meta_title', '个人中心');
        $this->display($template);
    }

    /**
     * 用户列表
     * @author jry <598821125@qq.com>
     */
    public function lists($user_type = 1) {
        // 获取用户类型的搜索字段
        $user_type_info = D('User/Type')->find($user_type);
        $con = array();
        $con['user_type'] = $user_type;
        $con['id'] = array('in', $user_type_info['list_field']);
        $query_attribute = D('User/Attribute')->where($con)->select();
        foreach ($query_attribute as &$value) {
            $value['options'] = \Common\Util\Think\Str::parseAttr($value['options']);

            // 构造搜索条件
            if ($_GET[$value['name']] !== 'all' && $_GET[$value['name']]) {
                switch ($value['type']) {
                    case 'radio':
                        $tmp = $_GET[$value['name']];
                        $map[$value['name']] = $tmp;
                        break;
                    case 'select':
                        $tmp = $_GET[$value['name']];
                        $map[$value['name']] = $tmp;
                        break;
                    case 'checkbox':
                        $tmp = $_GET[$value['name']];
                        $map[$value['name']] = array(
                            'like',
                            array(
                                $tmp,
                                $tmp.',%',
                                '%,'.$tmp.',%',
                                '%,'.$tmp
                            ),
                            'OR'
                        );
                        break;
                }
            }
        }

        // 获取用户基本信息
        $map['status']    = 1;

        // 关键字搜索
        $keyword = I('keyword', '', 'string');
        if ($keyword) {
            $condition = array('like','%'.$keyword.'%');
            $map['id|nickname|username|email|mobile'] = array(
                $condition,
                $condition,
                $condition,
                $condition,
                $condition,
                '_multi'=>true
            );
        }

        // 获取列表
        $map['user_type'] = $user_type;
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $user_object  = D('User/User');
        $base_table   = C('DB_PREFIX').'admin_user';
        $extend_table = C('DB_PREFIX').'user_'.strtolower($user_type_info['name']);
        $user_list = $user_object
                   ->page($p, 16)
                   ->where($map)
                   ->order('id desc')
                   ->join($extend_table.' ON '.$base_table.'.id = '.$extend_table.'.uid', 'LEFT')
                   ->select();
        $page = new Page(
            $user_object
            ->where($map)
            ->join($extend_table.' ON '.$base_table.'.id = '.$extend_table.'.uid', 'LEFT')
            ->count(),
            16
        );

        foreach ($user_list as &$val) {
            $val['gender_icon'] = $user_object->user_gender_icon($val['gender']);
            $val['home_href'] = U('home', array('uid' => $val['id']));
        }

        // Builder搜索支持
        if (I('from') === 'builder') {
            echo json_encode($user_list);
            exit();
        }

        $this->assign('page', $page->show());
        $this->assign('query_attribute', $query_attribute);
        $this->assign('meta_title', '用户');
        $this->assign('data_list', $user_list);
        $this->display();
    }

    /**
     * 用户个人主页
     * @author jry <598821125@qq.com>
     */
    public function home($uid) {
        $user_info = D('Admin/User')->getUserInfo($uid);

        // 关注信息
        $user_info['follow_status'] = D('User/Follow')->get_follow_status($uid);

        $user_type_info = D('User/Type')->find($user_info['user_type']);
        if ($user_info['status'] !== '1') {
            $this->error('该用户不存在或已禁用');
        }
        if ($user_type_info['home_template']) {
            $template = $user_type_info['home_template'];
        } else {
            $template = 'home';
        }
        $this->assign('meta_title', $user_info['username'].'的主页');
        $this->assign('user_info', $user_info);
        $this->display($template);
    }

    /**
     * 用户协议
     * @author jry <598821125@qq.com>
     */
    public function agreement() {
        $this->assign('meta_title', '用户协议');
        $this->display();
    }
}
