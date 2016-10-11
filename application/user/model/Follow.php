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
 * 用户关注模型
 * @author jry <598821125@qq.com>
 */
class Follow extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $table = 'user_follow';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('follow_uid', 'require', '粉丝ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('status', 1, self::MODEL_INSERT, 'string'),
    );

    /**
     * 当天新粉丝数量
     * @param $int UID
     * @author jry <598821125@qq.com>
     */
    public function newFansCount($uid = null) {
        if ($uid) {
            $map['uid'] = array('eq', $uid);
        } else {
            $map['uid'] = array('eq', is_login());
        }
        $map['status'] = array('eq', 1);
        $today = strtotime(date('Y-m-d', time())); //今天
        $map['create_time'] = array(
                                array('egt', $today),
                                array('lt', $today+86400)
                            );
        return $this->where($map)->count();
    }

    /**
     * 获取用户的粉丝数量
     * @param $int UID
     * @author jry <598821125@qq.com>
     */
    public function fansCount($uid = null) {
        if ($uid) {
            $map['uid'] = array('eq', $uid);
        } else {
            $map['uid'] = array('eq', is_login());
        }
        $map['status'] = array('eq', 1);
        return $this->where($map)->count();
    }

    /**
     * 获取关注的用户数量
     * @param $int UID
     * @author jry <598821125@qq.com>
     */
    public function followCount($uid = null) {
        if ($uid) {
            $map['follow_uid'] = array('eq', $uid);
        } else {
            $map['follow_uid'] = array('eq', is_login());
        }
        $map['status'] = array('eq', 1);
        return $this->where($map)->count();
    }

    /**
     * 获取收藏状态
     * @author jry <598821125@qq.com>
     */
    public function get_follow_status($uid) {
        $con = array();
        $con['uid'] = $uid;
        $con['follow_uid'] = is_login();
        $con['status'] = 1;
        $result = $this->where($con)->find()->toArray();
        return $result;
    }
}
