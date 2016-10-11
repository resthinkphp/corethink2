<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\cms\controller;
use app\home\controller\Home;
use common\util\think\Page;
/**
 * 举报控制器
 * @author jry <598821125@qq.com>
 */
class Report extends Home {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index($data_id) {
        if (IS_POST) {
            $report_object = D('Cms/Report');
            $data = $report_object->create();
            if ($data) {
                $result = $report_object->add($data);
                if ($result) {
                    $this->success('您的举报提交成功，请您耐心等待！');
                } else {
                    $this->error($report_object->getError());
                }
            } else {
                $this->error($report_object->getError());
            }
        } else {
            $this->assign('info', D('Cms/Index')->detail($data_id));
            $this->assign('reason_list', D('Cms/Report')->reason_list());
            $this->assign('meta_title', '举报页面');
            $this->display($template);
        }
    }
}