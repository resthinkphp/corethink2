<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace Cms\Admin;
use Admin\Controller\AdminController;
use common\util\think\Page;
/**
 * 评论控制器
 * @author jry <598821125@qq.com>
 */
class CommentAdmin extends AdminController {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        // 搜索
        $keyword = I('keyword', '', 'string');
        $condition = array('like','%'.$keyword.'%');
        $map['id|content'] = array($condition, $condition,'_multi'=>true);

        // 获取列表
        $p = !empty($_GET["p"]) ? $_GET["p"] : 1;
        $map['status'] = array('egt', '0');  // 禁用和正常状态
        $comment_object = D('Comment');
        $data_list = $comment_object
                   ->page($p, C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id desc')
                   ->select();
        $page = new Page(
            $comment_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('评论列表')  // 设置页面标题
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                ->setSearch('请输入ID/模型标题', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('uid', 'UID')
                ->addTableColumn('content', '内容')
                ->addTableColumn('good', '点赞')
                ->addTableColumn('bad', '拍砖')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)     // 数据列表
                ->setTableDataPage($page->show())  // 数据列表分页
                ->addRightButton('edit')           // 添加编辑按钮
                ->addRightButton('forbid')  // 添加禁用/启用按钮
                ->addRightButton('delete')  // 添加删除按钮
                ->display();
    }

    /**
     * 编辑
     * @author jry <598821125@qq.com>
     */
    public function edit($id) {
        if (IS_POST) {
            $comment_object = D('Comment');
            $data = $comment_object->create();
            if ($data) {
                $id = $comment_object->save();
                if ($id !== false) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($comment_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑评论')  // 设置页面标题
                    ->setPostUrl(U('edit'))     // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('data_id', 'hidden', '数据ID', '数据ID')
                    ->addFormItem('content', 'textarea', '评论', '评论')
                    ->setFormData(D('Comment')->find($id))
                    ->display();
        }
    }
}
