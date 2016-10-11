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
 * 实名认证控制器
 * @author jry <598821125@qq.com>
 */
class Cert extends Home {
    /**
     * 初始化方法
     * @author jry <598821125@qq.com>
     */
    protected function _initialize(){
        parent::_initialize();
        $this->is_login();
    }

    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        $cert_info = D('User/Cert')->where(array('uid' => is_login()))->find()->toArray();
        if ($cert_info) {
            $this->assign('cert_info', $cert_info);
            $this->assign('meta_title', "实名认证");
            $this->display();
        } else {
            redirect(U('User/Cert/add'));
        }
    }

    /**
     * 申请实名认证
     * @author jry <598821125@qq.com>
     */
    public function add() {
        $model_object = D('User/Cert');
        $cert_info = $model_object->where(array('uid' => is_login()))->find()->toArray();
        if ($cert_info) {
            redirect(U('User/Cert/index'));
        }
        if (IS_POST) {
            if ($cert_info) {
                $this->error('请耐心等待审核');
            }
            $_POST['uid'] = $this->is_login();
            if ($_POST['status']) {
                unset($_POST['status']);
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
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle("实名认证")  // 设置页面标题
                ->setPostUrl(U(""))      // 设置表单提交地址
                ->addFormItem("type", "radio", "认证类型", "认证类型", $model_object->type_list())
                ->addFormItem("cert_type", "radio", "证件类型", "证件类型", $model_object->cert_type_list())
                ->addFormItem("cert_no", "text", "证件号码", "证件号码")
                ->addFormItem("cert_title", "text", "真实名称", "与下图保持一致")
                ->addFormItem("cert_photo", "picture", "证件照片", "身份证请手持拍半身照")
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }

    /**
     * 申请实名认证
     * @author jry <598821125@qq.com>
     */
    public function edit() {
        $model_object = D('User/Cert');
        $cert_info = $model_object->where(array('uid' => is_login()))->find()->toArray();
        if (!$cert_info) {
            redirect(U('User/Cert/add'));
        }
        if ($cert_info['status'] === '1') {
            $this->error('审核通过不允许修改');
        }
        if (IS_POST) {
            $_POST['id'] = $cert_info['id'];
            if ($_POST['status']) {
                unset($_POST['status']);
            }
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
                ->addFormItem("type", "radio", "认证类型", "认证类型", $model_object->type_list())
                ->addFormItem("cert_type", "radio", "证件类型", "证件类型", $model_object->cert_type_list())
                ->addFormItem("cert_no", "text", "证件号码", "证件号码")
                ->addFormItem("cert_title", "text", "真实名称", "与下图保持一致")
                ->addFormItem("cert_photo", "picture", "证件照片", "身份证请手持拍半身照")
                ->setFormData($cert_info)
                ->setTemplate(C('USER_CENTER_FORM'))
                ->display();
        }
    }
}
