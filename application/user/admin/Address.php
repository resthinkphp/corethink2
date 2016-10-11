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
 * 收货地址控制器
 * @author jry <598821125@qq.com>
 */
class Address extends Admin {
    /**
     * 默认方法
     * @author jry <598821125@qq.com>
     */
    public function index() {
        $map['status'] = array('egt', '0'); // 禁用和正常状态
        $address_object = D('Address');
        $data_list = $address_object->where($map)->order('id asc')->select();

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('地址列表') // 设置页面标题
                ->addTopButton('addnew')  // 添加新增按钮
                ->addTopButton('resume')  // 添加启用按钮
                ->addTopButton('forbid')  // 添加禁用按钮
                ->addTopButton('delete')  // 添加删除按钮
                ->setSearch('请输入ID/姓名', U('index'))
                ->addTableColumn('id', 'ID')
                ->addTableColumn('uid', 'UID')
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
                ->display();
    }

    /**
     * 新增
     * @author jry <598821125@qq.com>
     */
    public function add() {
        if (IS_POST) {
            $address_object = D('Address');
            $data = $address_object->create(format_data());
            if ($data) {
                if ($data['default']) {
                    //使之前默认失效
                    $address_object->where(array('uid' => $data['uid'], 'default' => 1))->setField('default', 0);
                }
                $id = $address_object->add();
                if ($id) {
                    $this->success('新增成功', U('index'));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($address_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增') //设置页面标题
                    ->setPostUrl(U('add'))    //设置表单提交地址
                    ->addFormItem('uid', 'num', 'UID', '用户ID')
                    ->addFormItem('title', 'text', '姓名', '收货人姓名')
                    ->addFormItem('mobile', 'text', '电话', '收货人电话')
                    ->addFormItem('city', 'linkage', '城市', '城市')
                    ->addFormItem('address', 'text', '详细地址', '详细地址')
                    ->addFormItem('code', 'text', '邮编', '邮编')
                    ->addFormItem('default', 'radio', '默认地址', '默认地址', array('1' => '是', '0' => '否'))
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
                    $address_object->where(array('uid' => $data['uid'], 'default' => 1))->setField('default', 0);
                }
                $result = $address_object->save($data);
                if ($result) {
                    $this->success('更新成功', U('index'));
                } else {
                    $this->error('更新失败', $address_object->getError());
                }
            } else {
                $this->error($address_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑用户类型')  // 设置页面标题
                    ->setPostUrl(U('edit'))    // 设置表单提交地址
                    ->addFormItem('id', 'hidden', 'ID', 'ID')
                    ->addFormItem('uid', 'num', 'UID', '用户ID')
                    ->addFormItem('title', 'text', '姓名', '收货人姓名')
                    ->addFormItem('mobile', 'text', '电话', '收货人电话')
                    ->addFormItem('city', 'linkage', '城市', '城市')
                    ->addFormItem('address', 'text', '详细地址', '详细地址')
                    ->addFormItem('code', 'text', '邮编', '邮编')
                    ->addFormItem('default', 'radio', '默认地址', '默认地址', array('1' => '是', '0' => '否'))
                    ->setFormData(D('Address')->find($id))
                    ->display();
        }
    }
}
