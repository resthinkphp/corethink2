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
 * 点赞控制器
 * @author jry <598821125@qq.com>
 */
class Zan extends Home {
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 我的
     * @author jry <598821125@qq.com>
     */
    public function my() {
        $map['status'] = array('eq', '1');  // 禁用和正常状态
        $map['uid']  = $this->is_login();
        $p = !empty($_GET["p"]) ? $_GET['p'] : 1;
        $zan_object =  D('Zan');
        $data_list = $zan_object
                   ->page($p, C('ADMIN_PAGE_ROWS'))
                   ->where($map)
                   ->order('id asc')
                   ->select();
        $page = new Page(
            $zan_object->where($map)->count(),
            C('ADMIN_PAGE_ROWS')
        );

        // 获取标题
        $index_object = D('Cms/Index');
        foreach ($data_list as &$val) {
            $temp = $index_object->detail($val['data_id']);
            $val['title_url'] = '<a target="_blank" href="'.U(D('Index')->moduleName.'/Index/detail', array('id' => $temp['id'])).'">'.$temp['title'].'</a>';
        }

        // 取消点赞按钮
        $attr['name']  = 'cencel';
        $attr['title'] = '取消点赞';
        $attr['class'] = 'label label-danger-outline label-pill ajax-get';
        $attr['href'] = U(D('Index')->moduleName.'/Zan/add', array(
            'data_id' => __data_id__,
        ));

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle("我赞的文档")  // 设置页面标题
                ->addTableColumn("id", "ID")
                ->addTableColumn("title_url", "标题")
                ->addTableColumn("create_time", "创建时间", "time")
                ->addTableColumn("status", "状态", "status")
                ->addTableColumn("right_button", "操作", "btn")
                ->setTableDataList($data_list)     // 数据列表
                ->setTableDataPage($page->show())  // 数据列表分页
                ->addRightButton("self", $attr)    // 添加取消点赞按钮
                ->setTemplate(C('USER_CENTER_LIST'))
                ->setTableDataListKey('data_id')
                ->display();
    }

    /**
     * 点赞
     * @author jry <598821125@qq.com>
     */
    public function add($data_id){
        $zan_object = D('Cms/Zan');
        $con['data_id'] = $data_id;
        $con['uid'] = $this->is_login();
        $find = $zan_object->where($con)->find()->toArray();
        if ($find) {
            if ($find['status'] === '1') {
                $where['id'] = $find['id'];
                $result = $zan_object
                        ->where($where)
                        ->setField(array('status' => 0, 'update_time' => time()));
                if ($result) {
                    $return['status'] = 1;
                    $return['info'] = '取消点赞成功'.$zan_object->getError();
                    $return['follow_status'] = 0;

                    // 点赞数量-1
                    $result = D('Index')->where(array('id' => $data_id))->SetDec('good');
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info'] = '取消点赞失败'.$zan_object->getError();
                    $return['follow_status'] = 1;
                    $this->ajaxReturn($return);
                }
            } else {
                $where['id'] = $find['id'];
                $result = $zan_object
                        ->where($where)
                        ->setField(array('status' => 1, 'update_time' => time()));
                if ($result) {
                    $return['status'] = 1;
                    $return['info'] = '点赞成功'.$zan_object->getError();
                    $return['follow_status'] = 1;

                    // 点赞数量+1
                    $result = D('Index')->where(array('id' => $data_id))->SetInc('good');
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info'] = '点赞失败'.$zan_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        } else {
            $data = $zan_object->create($con);
            if ($data) {
                $result = $zan_object->add($data);
                if ($result) {
                    $return['status'] = 1;
                    $return['info'] = '点赞成功'.$zan_object->getError();
                    $return['follow_status'] = 1;

                    // 点赞数量+1
                    $result = D('Index')->where(array('id' => $data_id))->SetInc('good');
                    $this->ajaxReturn($return);
                } else {
                    $return['status'] = 0;
                    $return['info'] = '点赞失败'.$zan_object->getError();
                    $this->ajaxReturn($return);
                }
            }
        }
    }
}
