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
 * 用户积分模型
 * @author jry <598821125@qq.com>
 */
class ScoreLog extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $table = 'user_score_log';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid','require','UID必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', 'number', 'UID必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type', 'number', '变动方式必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('type','require','变动方式必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('score','require','变动数量必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('score', 'number', '变动数量必须数字', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('message','require','变动说明必须填写', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('message', '1,255', '变动说明长度为1-255个字符', self::EXISTS_VALIDATE, 'length', self::MODEL_BOTH),
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
     * 积分变动类型
     * @author jry <598821125@qq.com>
     */
    public function change_type($id) {
        $list[1]  = '增加';
        $list[2]  = '减少';
        return $id ? $list[$id] : $list;
    }

    /**
     * 积分变动
     * @author jry <598821125@qq.com>
     */
    public function changeScore($type, $uid, $score, $message, $field='score') {
        $data['type']    = $type;
        $data['uid']     = $uid;
        $data['score']   = $score;
        $data['message'] = $message;
        $data = $this->create($data);
        if ($data) {
            $map['id'] = $data['uid'];
            switch ($data['type']) {
                case 1:
                    $result = D('User/User')->where($map)->setInc($field, $data['score']);
                    break;
                case 2:
                    $result = D('User/User')->where($map)->setDec($field, $data['score']);
                    break;
            }
            if ($result) {
                $result = $this->add($data);
                return true;
            }
        } else {
            return $this->getError();
        }
    }
}
