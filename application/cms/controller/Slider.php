<?php
// +----------------------------------------------------------------------
// | CoreThink [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.corethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------
namespace app\cms\controller;
use app\home\controller\Home;
use common\util\think\Page;
/**
 * 幻灯片控制器
 * @author jry <598821125@qq.com>
 */
class Slider extends Home {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index($limit = 5, $page = 1, $order = '') {
        $map['status'] = 1;
        $list = D("Cms/Slider")->getList($limit, $page, $order, $map);
        $this->success('幻灯片列表', '', array('data' => $list));
    }
}
