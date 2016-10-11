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
 * 用户中心控制器
 * @author jry <598821125@qq.com>
 */
class Center extends Home {
    /**
     * 修改昵称
     * @author jry <598821125@qq.com>
     */
    public function nickname() {
        $uid  = $this->is_login();
        if (IS_POST) {
            if (I('post.nickname')) {
                $user_object = D('User/User');
                $result = $user_object->where(array('id' => $uid))
                        ->setField('nickname', I('post.nickname'));
                if ($result) {
                    $this->success('昵称修改成功');
                } else {
                    $this->error('昵称修改失败'.$user_object->getError());
                }
            } else {
                $this->error('请填写昵称');
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('修改昵称')  // 设置页面标题
                    ->setPostUrl(U(''))        // 设置表单提交地址
                    ->addFormItem('nickname', 'text', '用户昵称')
                    ->setFormData(D('User/User')->detail($uid))
                    ->setTemplate(C('USER_CENTER_FORM'))
                    ->display();
        }
    }

    /**
     * 修改头像
     * @author jry <598821125@qq.com>
     */
    public function avatar() {
        $uid  = $this->is_login();
        if (IS_POST) {
            if ($_POST) {
                if (!$_POST['avatar']['src'] || !$_POST['avatar']['w'] || !$_POST['avatar']['h'] || $_POST['avatar']['x'] === '' || $_POST['avatar']['y'] === '') {
                    $this->error('参数不完整');
                }
                $result = D('Admin/Upload')->crop($_POST['avatar']);
                if ($result && $result['error'] != 1) {
                    $user_object = D('User/User');
                    $result = $user_object->where(array('id' => $uid))->setField('avatar', $result['id']);
                    if ($result) {
                        $this->success('头像修改成功');
                    } else {
                        $this->error('头像修改失败'.$user_object->getError());
                    }
                } else {
                    $this->error('头像保存失败');
                }
            } else {
                $this->error('请选择文件');
            }
        } else {
            $this->assign('user_info', D('User/User')->detail($uid));
            $this->assign('meta_title', '修改头像');
            $this->display();
        }
    }

    /**
     * 用户修改信息
     * @author jry <598821125@qq.com>
     */
    public function profile() {
        $uid = $this->is_login();
        if (IS_POST) {
            // 强制设置用户ID
            $_POST['uid'] = $uid;
            $_POST = format_data();

            // 获取用户信息
            $user_object = D('User/User');
            $user_info = $user_object->find($uid);

            // 保存昵称
            if (I('post.nickname')) {
                $result = $user_object->where(array('id' => $uid))->setField('nickname', I('post.nickname'));
                if ($result === false) {
                    $this->error('昵称修改失败'.$user_object->getError());
                }
            } else {
                $this->error('请填写昵称');
            }

            // 保存扩展信息
            $type = $user_info['user_type'];
            $map['user_type'] = array('eq', $type);
            $count = D('User/Attribute')->where($map)->count();
            if ($count) {
                $user_type_name = D('User/Type')->where(array('id' => $user_info['user_type']))->getField('name');
                $user_extend_object = D('User'.ucfirst($user_type_name));
                $extend_data = $user_extend_object->create();
                if (!$extend_data) {
                    $this->error($user_extend_object->getError());
                }
                $extend_info = $user_extend_object->find($uid);
                if ($extend_info) {
                    $result = $user_extend_object->save($extend_data);
                } else {
                    $result = $user_extend_object->add($extend_data);
                }
                if ($result === false) {
                    $this->error('扩展信息修改失败'.$user_extend_object->getError());
                } else {
                    $this->success('信息修改成功');
                }
            }
        } else {
            // 获取当前用户
            $user_object = D('User/User');
            $user_info = $user_object->detail($this->is_login());
            $type = $user_info['user_type'];
            $user_type_info = D('User/Type')->find($type);

            // 获取扩展字段
            $map['user_type'] = array('eq', $type);
            $attribute_list[$type] = D('User/Attribute')->where($map)->order('id asc')->select();

            // 解析字段options
            $new_attribute_list_sort['user']['type'] = 'group';
            if ($attribute_list[$type]) {
                // 增加昵称表单
                $nick['name']  = 'nickname';
                $nick['title'] = '昵称';
                $nick['type']  = 'text';
                $nick['value']  = $user_info['nickname'];
                $new_attribute_list[1][0] = $nick;
                foreach ($attribute_list[$type] as $attr) {
                    $attr['options'] = \Common\Util\Think\Str::parseAttr($attr['options']);
                    $new_attribute_list[$type][$attr['id']] = $attr;
                    $new_attribute_list[$type][$attr['id']]['value'] = $user_info[$attr['name']];
                }
                $new_attribute_list_sort['user']['options']['group_extend']['title'] = '完善'.$user_type_info['title'].'信息';
                $new_attribute_list_sort['user']['options']['group_extend']['options'] = $new_attribute_list[$type];
            }

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('修改信息')  // 设置页面标题
                    ->setPostUrl(U(''))        // 设置表单提交地址
                    ->setExtraItems($new_attribute_list_sort)
                    ->setTemplate(C('USER_CENTER_FORM'))
                    ->display();
        }
    }
}
