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
 * 用户类型模型
 * @author jry <598821125@qq.com>
 */
class Type extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $table = 'user_type';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('name', 'require', '类型名称不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('name', '/^[\w]+$/', '名称必须是纯英文，不包含下划线、空格及其他字符', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('title', 'require', '类型标题不能为空', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
}
