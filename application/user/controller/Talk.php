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
/**
 * im控制器(为了兼容1.4以前的链接)
 * @author jry <598821125@qq.com>
 */
class Talk extends Home {
    /**
     * im聊天
     * @author jry <598821125@qq.com>
     */
    public function index($to_uid) {
        redirect(U('Im/Index/detail', array('id' => $to_uid), true, true));
    }
}
