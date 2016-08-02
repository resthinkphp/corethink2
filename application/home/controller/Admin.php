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
/**
 * 跳转到后台控制器
 * @author jry <598821125@qq.com>
 */
class Admin extends Controller {
    /**
     * 自动跳转到后台入口文件
     * @author jry <598821125@qq.com>
     */
    public function index() {
        redirect(C('HOME_PAGE').'/admin.php');
    }
}
