<?php
// +----------------------------------------------------------------------
// | OpenCMF [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.opencmf.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: jry <598821125@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\model;
use think\Model;
/**
 * 管理员与用户组对应关系模型
 * @author jry <598821125@qq.com>
 */
class Access extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $tableName = 'admin_access';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', 'UID不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('group', 'require', '部门不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('uid', 'checkUser', '该用户不存在', self::MUST_VALIDATE, 'callback', self::MODEL_BOTH),
    );

    /**
     * 自动完成规则
     * @author jry <598821125@qq.com>
     */
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('update_time', 'time', self::MODEL_BOTH, 'function'),
        array('sort', '0', self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**
     * 检查用户是否存在
     * @author jry <598821125@qq.com>
     */
    protected function checkUser($uid){
        $user_info = D('User')->find($uid);
        if($user_info){
            return true;
        }
        return false;
    }
}
