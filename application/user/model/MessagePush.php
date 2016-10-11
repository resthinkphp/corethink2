<?php
// +----------------------------------------------------------------------
// | 零云 [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.lingyun.net All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\user\model;
use app\common\model\Model;
/**
 * 消息推送设备记录模型
 * @author jry <598821125@qq.com>
 */
class MessagePush extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $table = 'user_message_push';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid','require','UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('token', '1,127', 'token长度为1-127个字符', self::MUST_VALIDATE, 'length', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * OS类型
     * @author jry <598821125@qq.com>
     */
    public function os_type($id) {
        $list[1] = 'iOS';
        $list[2] = 'Android';
        $list[3] = 'Windows';
        return $id ? $list[$id] : $list;
    }
}
