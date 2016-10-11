<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\user\admin;
use admin\controller\Admin;
use common\util\think\Page;
/**
 * 实名认证控制器
 * @author jry <598821125@qq.com>
 */
class Cert extends Admin {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index($status = '0') {
        // 获取列表
        $map["status"] = array("eq", $status);  // 禁用和正常状态
        $p = $_GET["p"] ? : 1;
        $model_object = D("Cert");
        $data_list = $model_object
                   ->page($p, C("ADMIN_PAGE_ROWS"))
                   ->where($map)
                   ->order("id desc")
                   ->select();
        $page = new Page(
            $model_object->where($map)->count(),
            C("ADMIN_PAGE_ROWS")
        );

        // 设置Tab导航数据列表
        $tab_list[0]['title'] = '未审核';
        $tab_list[0]['href']  = U('index', array('status' => 0));
        $tab_list[1]['title'] = '已审核';
        $tab_list[1]['href']  = U('index', array('status' => 1));

        $attr['name']  = 'review';
        $attr['title'] = '审核';
        $attr['class'] = 'label label-success-outline label-pill';
        $attr['href'] = U('review', array('id' => '__data_id__'));

        $attr1['name']  = 'cancel_review';
        $attr1['title'] = '取消审核';
        $attr1['class'] = 'label label-danger-outline label-pill ajax-get confirm';
        $attr1['href'] = U('canel_review', array('id' => '__data_id__'));

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle("列表")  // 设置页面标题
                ->addTopButton("addnew")    // 添加新增按钮
                ->setSearch("请输入ID", U("index"))
                ->setTabNav($tab_list, $status)  // 设置页面Tab导航
                ->addTableColumn("id", "ID")
                ->addTableColumn("uid", "UID")
                ->addTableColumn("cert_title", "名称")
                ->addTableColumn("create_time", "认证时间", "time")
                ->addTableColumn("status", "状态", "status")
                ->addTableColumn("right_button", "操作", "btn")
                ->setTableDataList($data_list)     // 数据列表
                ->setTableDataPage($page->show());  // 数据列表分页
                if ($status === '0') {
                    $builder->addRightButton("self", $attr)
                            ->addRightButton("edit");
                } else {
                    $builder->addRightButton("edit")
                            ->addRightButton("self", $attr1);
                }
        $builder->display();
    }

    /**
     * 新增
     * @author jry <598821125@qq.com>
     */
    public function add() {
        $model_object = D('User/Cert');
        $cert_info = $model_object->where(array('uid' => I('uid')))->find()->toArray();
        if (IS_POST) {
            if ($cert_info) {
                $this->error('已存在');
            }
            $data = $model_object->create();
            if ($data) {
                $id = $model_object->add($data);
                if ($id) {
                    $this->success("申请成功", U("User/Cert/index"));
                } else {
                    $this->error("申请失败".$model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            if ($cert_info) {
                redirect(U('User/Cert/index'));
            } else {
                $builder = new \Common\Builder\FormBuilder();
                $builder->setMetaTitle("实名认证")  // 设置页面标题
                    ->setPostUrl(U(""))      // 设置表单提交地址
                    ->addFormItem('uid', 'uid', '用户ID', '用户ID')
                    ->addFormItem("type", "radio", "认证类型", "认证类型", $model_object->type_list())
                    ->addFormItem("cert_type", "radio", "证件类型", "证件类型", $model_object->cert_type_list())
                    ->addFormItem("cert_no", "text", "证件号码", "证件号码")
                    ->addFormItem("cert_title", "text", "真实名称", "与下图保持一致")
                    ->addFormItem("cert_photo", "picture", "证件照片", "身份证请手持拍半身照")
                    ->addFormItem("status", "radio", "认证状态", "认证状态", array('1' => '通过审核', '0' => '未审核'))
                    ->display();
            }
        }
    }

    /**
     * 编辑
     * @author jry <598821125@qq.com>
     */
    public function edit($id) {
        $model_object = D('User/Cert');
        $cert_info = $model_object->find($id);
        if (IS_POST) {
            $data = $model_object->create();
            if ($data) {
                $id = $model_object->save($data);
                if ($id) {
                    $this->success("修改成功", U("User/Cert/index"));
                } else {
                    $this->error("修改失败".$model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle("实名认证")  // 设置页面标题
                ->setPostUrl(U(""))      // 设置表单提交地址
                ->addFormItem('id','hidden','ID','ID')
                ->addFormItem('uid', 'uid', '用户ID', '用户ID')
                ->addFormItem("type", "radio", "认证类型", "认证类型", $model_object->type_list())
                ->addFormItem("cert_type", "radio", "证件类型", "证件类型", $model_object->cert_type_list())
                ->addFormItem("cert_no", "text", "证件号码", "证件号码")
                ->addFormItem("cert_title", "text", "真实名称", "与下图保持一致")
                ->addFormItem("cert_photo", "picture", "证件照片", "身份证请手持拍半身照")
                ->setFormData($cert_info)
                ->display();
        }
    }

    /**
     * 审核
     * @author jry <598821125@qq.com>
     */
    public function review($id) {
        $model_object = D('User/Cert');
        $cert_info = $model_object->find($id);
        if (IS_POST) {
            $data = $model_object->create();
            if ($data) {
                $status = $model_object->where(array('id' => $id))->setField('status', '1');
                if ($status) {
                    // 发送消息
                    $user_info = D('Admin/User')->getUserInfo($cert_info['uid']);
                    $msg_data['content'] = $user_info['nickname'].'您好：<br>'.'您的实名认证已通过审核！';
                    $msg_data['to_uid'] = $cert_info['uid'];
                    $msg_data['title']  = '实名认证通知';
                    $msg_data['url']    = oc_url('Git/Cert/index/', '', true, true);
                    $msg_return = D('User/Message')->sendMessage($msg_data);

                    $this->success("审核成功", U("User/Cert/index"));
                } else {
                    $this->error("审核失败".$model_object->getError());
                }
            } else {
                $this->error($model_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle("审核")  // 设置页面标题
                ->setPostUrl(U(""))      // 设置表单提交地址
                ->addFormItem('id','hidden','ID','ID')
                ->addFormItem('uid', 'uid', '用户ID', '用户ID')
                ->addFormItem("type", "radio", "认证类型", "认证类型", $model_object->type_list())
                ->addFormItem("cert_type", "radio", "证件类型", "证件类型", $model_object->cert_type_list())
                ->addFormItem("cert_no", "text", "证件号码", "证件号码")
                ->addFormItem("cert_title", "text", "真实名称", "与下图保持一致")
                ->addFormItem("cert_photo", "picture", "证件照片", "身份证请手持拍半身照")
                ->setFormData($cert_info)
                ->setSubmitTitle('通过审核')
                ->display();
        }
    }

    /**
     * 取消审核
     * @author jry <598821125@qq.com>
     */
    public function canel_review($id) {
        $model_object = D('User/Cert');
        $cert_info = $model_object->find($id);
        if ($cert_info['status']) {
            $status = $model_object->where(array('id' => $id))->setField('status', '0');
            if ($status) {
                // 发送消息
                $user_info = D('Admin/User')->getUserInfo($cert_info['uid']);
                $msg_data['content'] = $user_info['nickname'].'您好：<br>'.'您的实名认证状态已被取消，如有疑问请联系官方！';
                $msg_data['to_uid'] = $cert_info['uid'];
                $msg_data['title']  = '实名认证通知';
                $msg_data['url']    = oc_url('Git/Cert/index/', '', true, true);
                $msg_return = D('User/Message')->sendMessage($msg_data);

                $this->success("取消审核成功", U("User/Cert/index"));
            } else {
                $this->error("取消审核失败".$model_object->getError());
            }
        } else {
            $this->error('未审核通过');
        }
    }
}
