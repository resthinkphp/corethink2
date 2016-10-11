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
 * 评论控制器
 * @author jry <598821125@qq.com>
 */
class Comment extends Home {
    /**
     * 评论列表
     * @author jry <598821125@qq.com>
     */
    public function index($data_id, $limit = 10, $page = 1, $order = '', $con = null) {
        $comment_object = D('Comment');
        $list = $comment_object->getCommentList($data_id, $limit, $page, $order, $con);
        $this->success('评论列表', '', array('data' => $list));
    }

    /**
     * 新增评论
     * @author jry <598821125@qq.com>
     */
    public function add() {
        if (IS_POST) {
            $uid = $this->is_login();
            $comment_object = D(D('Index')->moduleName.'/Comment');
            $data = $comment_object->create();
            if ($data) {
                $result = $comment_object->addNew($data);
                if ($result) {
                    $this->success('评论成功');
                } else {
                    $this->error('评论失败'.$comment_object->getError());
                }
            } else {
                $this->error($comment_object->getError());
            }
        }
    }
}