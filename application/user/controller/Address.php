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
 * 收货地址控制器
 * @author jry <598821125@qq.com>
 */
class Address extends Home {
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
        $map['uid'] = $this->is_login();
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $address_object = D('Address');
        $data_list = $address_object->where($map)->order('id asc')->select();

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('我的收货地址') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->setSearch('请输入ID/姓名', U('User/Address/my'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('title', '标题')
                ->addTableColumn('mobile', '电话')
                ->addTableColumn('city', '城市')
                ->addTableColumn('address', '详细地址')
                ->addTableColumn('code', '邮编')
                ->addTableColumn('status', '状态', 'status')
                ->addTableColumn('right_button', '操作', 'btn')
                ->setTableDataList($data_list)    // 数据列表
                ->addRightButton('edit')    // 添加编辑按钮
                ->addRightButton('forbid')  // 添加禁用/启用按钮
                ->addRightButton('delete')  // 添加删除按钮
                ->setTemplate(C('USER_CENTER_LIST'))
                ->display();
    }

    /**
     * 新增
     * @author jry <598821125@qq.com>
     */
    public function add() {
        if (IS_POST) {
            $address_object = D('Address');
            $_POST['uid'] = $this->is_login();
            $data = $address_object->create(format_data());
            if ($data) {
                if ($data['default']) {
                    //使之前默认失效
                    $address_object->where(array('uid' => is_login(), 'default' => 1))->setField('default', 0);
                }
                $id = $address_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('User/Address/my'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($address_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增收货地址') //设置页面标题
                    ->setPostUrl(U(''))    //设置表单提交地址
                    ->addFormItem('title', 'text', '姓名', '收货人姓名')
                    ->addFormItem('mobile', 'text', '电话', '收货人电话')
                    ->addFormItem('city', 'linkage', '城市', '城市')
                    ->addFormItem('address', 'text', '详细地址', '详细地址')
                    ->addFormItem('code', 'text', '邮编', '邮编')
                    ->addFormItem('default', 'radio', '默认地址', '默认地址', array('1' => '是', '0' => '否'))
                    ->setTemplate(C('USER_CENTER_FORM'))
                    ->display();
        }
    }

    /**
     * 编辑
     * @author jry <598821125@qq.com>
     */
    public function edit($id) {
        if (IS_POST) {
            // 提交数据
            $address_object = D('Address');
            $data = $address_object->create(format_data());
            if ($data) {
                if ($data['default']) {
                    //使之前默认失效
                    $address_object->where(array('uid' => is_login(), 'default' => 1))->setField('default', 0);
                }
                $result = $address_object->save($data);
                if ($result) {
                    $this->success('更新成功', U('User/Address/my'));
                } else {
                    $this->error('更新失败', $address_object->getError());
                }
            } else {
                $this->error($address_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑收货地址')  // 设置页面标题
                    ->setPostUrl(U(''))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('title', 'text', '姓名', '收货人姓名')
                    ->addFormItem('mobile', 'text', '电话', '收货人电话')
                    ->addFormItem('city', 'linkage', '城市', '城市')
                    ->addFormItem('address', 'text', '详细地址', '详细地址')
                    ->addFormItem('code', 'text', '邮编', '邮编')
                    ->addFormItem('default', 'radio', '默认地址', '默认地址', array('1' => '是', '0' => '否'))
                    ->setFormData(D('Address')->find($id))
                    ->setTemplate(C('USER_CENTER_FORM'))
                    ->display();
        }
    }

    /**
     * 设置默认地址
     * @author jry <598821125@qq.com>
     */
    public function set_default($id){
        $map['uid'] = is_login();
        $map['default'] = 1;
        $model_object = D('Address');

        //使之前默认失效
        $model_object->where($map)->setField('default', 0);
        $result = $model_object->where(array('id' => $id))->setField('default', 1);
        if ($result) {
            $this->success('默认地址设置成功');
        }else{
            $this->error('默认地址设置失败：' . $model_object->getError());
        }
    }
}
