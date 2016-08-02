<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
/**
 * 后台默认控制器
 * @author jry <598821125@qq.com>
 */
class Index extends Admin {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index(){
        $this->assign('meta_title', "首页");
        $this->display();
    }

    /**
     * 删除缓存
     * @author jry <598821125@qq.com>
     */
    public function removeRuntime() {
        $file = new \Common\Util\File();
        $result = $file->del_dir(RUNTIME_PATH);
        if ($result) {
            $this->success("缓存清理成功");
        } else {
            $this->error("缓存清理失败");
        }
    }
}
