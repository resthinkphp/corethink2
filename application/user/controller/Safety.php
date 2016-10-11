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
use common\util\think\Page;
/**
 * 帐号安全控制器
 * @author jry <598821125@qq.com>
 */
class Safety extends Home {
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
        $uid = $this->is_login();
        $user_info = D('Admin/User')->find($uid);
        $this->assign('meta_title', '安全中心');
        $this->assign('user_info', $user_info);
        $this->display();
    }

    /**
     * 修改密码
     * @author jry <598821125@qq.com>
     */
    public function password() {
        $uid  = $this->is_login();
        if (IS_POST) {
            $validate = array (
                array('password', 'require', '请填写旧密码', 1, 'regex'),
                array('newpassword', '6,30', '密码长度为6-30位', 1, 'length'),
                array('newpassword', '/(?!^(\d+|[a-zA-Z]+|[~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+)$)^[\w~!@#$%^&*()_+{}:"<>?\-=[\];\',.\/]+$/', '密码至少由数字、字符、特殊字符三种中的两种组成', 1, 'regex'),
                array('repassword', 'newpassword', '两次输入的密码不一致', 1, 'confirm')
            );
            $user_object = D('User/User');
            $user_object->setProperty("_validate", $validate);
            $data = $user_object->create();
            if ($data) {
                $password = user_md5(I('password'));
                $newpassword = user_md5(I('newpassword'));
                if ($password === D('Admin/User')->getFieldById($uid, 'password')) {
                    $result = $user_object->where(array('id' => $uid))
                            ->setField('password', $newpassword);
                    if ($result) {
                        $this->success('密码修改成功', U('User/User/logout'));
                    } else {
                        $this->error('密码修改失败'.$user_object->getError());
                    }
                } else {
                    $this->error('旧密码输入错误');
                }
            } else {
                    $this->error('错误：'.$user_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('修改密码')  // 设置页面标题
                    ->setPostUrl(U(''))        // 设置表单提交地址
                    ->addFormItem('password', 'password', '旧密码')
                    ->addFormItem('newpassword', 'password', '新密码')
                    ->addFormItem('repassword', 'password', '重复新密码')
                    ->setTemplate(C('USER_CENTER_FORM'))
                    ->display();
        }
    }

    /**
     * 帐号绑定
     * @author jry <598821125@qq.com>
     */
    public function bind() {
        if (IS_POST) {
            $uid = $this->is_login();
            $user_object = D('Admin/User');
            switch (I('post.bind_type')) {
                case 'email': //邮箱绑定
                    //验证码严格加盐加密验证
                    if (user_md5(I('post.verify'), I('post.email')) !== session('reg_verify')) {
                        $this->error('验证码错误！');
                    }

                    // 检查绑定
                    $map = array();
                    $map['email']      = I('post.email');
                    $map['email_bind'] = 1;
                    $exist = $user_object->where($map)->count();
                    if ($exist) {
                        $this->error('该邮箱已被绑定'. $user_object->getError());
                    }

                    // 开始绑定
                    $con = array('id' => $uid);
                    $result = $user_object->where($con)->setField('email', I('post.email'));
                    if ($result !== false) {
                        $status = $user_object->where($con)->setField('email_bind', 1);
                        if ($status !== false) {
                            // 构造消息数据
                            $msg_data['to_uid'] = $uid;
                            $msg_data['title']  = '绑定成功';
                            $msg_data['content'] = '您好：<br>'
                                                  .'恭喜您成功将邮箱'.I('post.email').'绑定了'.C('WEB_SITE_TITLE').'的帐号，'
                                                  .'您可以使用该邮箱直接登录'.C('WEB_SITE_TITLE').'。<br>'
                                                  .'<br>';
                            D('User/Message')->sendMessage($msg_data);
                            $this->success('恭喜您，邮箱绑定成功！', U('index'));
                        } else {
                            $this->error('邮箱绑定失败！'. $user_object->getError());
                        }
                    } else {
                        $this->error('邮箱绑定失败！'. $user_object->getError());
                    }
                    break;
                case 'mobile': //手机号绑定
                    //验证码严格加盐加密验证
                    if (user_md5(I('post.verify'), I('post.mobile')) !== session('reg_verify')) {
                        $this->error('验证码错误！');
                    }

                    // 检查绑定
                    $map = array();
                    $map['mobile']      = I('post.mobile');
                    $map['mobile_bind'] = 1;
                    $exist = $user_object->where($map)->count();
                    if ($exist) {
                        $this->error('该手机已被另一帐号绑定'. $user_object->getError());
                    }

                    // 开始绑定
                    $con = array('id' => $uid);
                    $result = $user_object->where($con)->setField('mobile', I('post.mobile'));
                    if ($result !== false) {
                        $status = $user_object->where($con)->setField('mobile_bind', 1);
                        if ($status !== false) {
                            // 构造消息数据
                            $msg_data['to_uid'] = $uid;
                            $msg_data['title']  = '绑定成功';
                            $msg_data['content'] = '您好：<br>'
                                                  .'恭喜您成功将手机号'.I('post.mobile').'绑定了'.C('WEB_SITE_TITLE').'的帐号，'
                                                  .'您可以使用该手机号直接登录'.C('WEB_SITE_TITLE').'。<br>'
                                                  .'<br>';
                            D('User/Message')->sendMessage($msg_data);
                            $this->success('恭喜您，手机绑定成功！', U('index'));
                        } else {
                            $this->error('手机绑定失败！'. $user_object->getError());
                        }
                    } else {
                        $this->error('手机绑定失败！'. $user_object->getError());
                    }
                    break;
            }
        } else {
            $this->assign('meta_title', '帐号绑定');
            $this->display();
        }
    }

    /**
     * 取消绑定
     * @author jry <598821125@qq.com>
     */
    public function cancel() {
        $uid = $this->is_login();
        $user_object = D('Admin/User');
        switch (I('bind_type')) {
            case 'email':
                $con = array('id' => $uid);
                $result = $user_object->where($con)->setField('email', '');
                if ($result !== false) {
                    $status = $user_object->where($con)->setField('email_bind', 0);
                    if ($status !== false) {
                        $this->success('恭喜您，取消邮箱绑定成功！', U('index'));
                    } else {
                        $this->error('取消邮箱绑定失败！'. $user_object->getError());
                    }
                } else {
                    $this->error('邮箱绑定失败！'. $user_object->getError());
                }
                break;
            case 'mobile':
                $con = array('id' => $uid);
                $result = $user_object->where($con)->setField('mobile', '');
                if ($result !== false) {
                    $status = $user_object->where($con)->setField('mobile_bind', 0);
                    if ($status !== false) {
                        $this->success('恭喜您，取消手机绑定成功！', U('index'));
                    } else {
                        $this->error('取消手机绑定失败！'. $user_object->getError());
                    }
                } else {
                    $this->error('取消手机绑定失败！'. $user_object->getError());
                }
                break;
        }
    }
}
