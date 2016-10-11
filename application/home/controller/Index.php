<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\home\controller;
use think\Controller;
//use common\util\think\Page;
/**
 * 前台默认控制器
 * @author jry <598821125@qq.com>
 */
class Index extends Home {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        $this->assign('meta_title', "首页");
        $this->display();
    }

    /**
     * 系统配置
     * @author jry <598821125@qq.com>
     */
    public function config($name = '') {
        $data_list = C($name);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '系统配置');
        $this->display();
    }

    /**
     * 导航
     * @author jry <598821125@qq.com>
     */
    public function nav($group = 'wap_bottom') {
        $data_list = D('Admin/Nav')->getNavTree(0, $group);
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '导航列表');
        $this->display();
    }

    /**
     * 模块
     * @author jry <598821125@qq.com>
     */
    public function module() {
        $map['status'] = 1;
        $data_list = D('Admin/MODULE')->where($map)->select();
        $this->assign('data_list', $data_list);
        $this->assign('meta_title', '模块列表');
        $this->display();
    }
}
