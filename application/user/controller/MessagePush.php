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
 * 推送设备记录控制器
 * @author jry <598821125@qq.com>
 */
class MessagePush extends Home {
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
     * @param $type 消息类型
     * @author jry <598821125@qq.com>
     */
    public function add($token){
        $data['token'] = $token;
        $data['uid']   = is_login();
        $push_object = D('MessagePush');
        $create_data = $push_object->create($data);
        if ($create_data) {
            $exist = $push_object->where(array('session_id' => session_id()))->find()->toArray();
            if ($exist) {
                $result = $push_object->where(array('id' => $exist['id']))->save($create_data);
            } else {
                $create_data['session_id'] = session_id();
                $result = $push_object->add($create_data);
            }
            if ($result) {
                $this->success('推送Token上传成功');
            } else {
                $this->error('错误：' . $push_object->getError());
            }
        } else {
            $this->error('错误：' . $push_object->getError());
        }
    }
}
