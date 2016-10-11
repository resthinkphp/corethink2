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
 * 收货地址模型
 * @author jry <598821125@qq.com>
 */
class Address extends Model {
    /**
     * 数据库表名
     * @author jry <598821125@qq.com>
     */
    protected $table = 'user_address';

    /**
     * 自动验证规则
     * @author jry <598821125@qq.com>
     */
    protected $_validate = array(
        array('uid', 'require', '用户ID必须', self::MUST_VALIDATE, 'regex', self::MODEL_INSERT),
        array('title', 'require', '请输入收货人姓名', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('mobile', 'require', '请输入收货人电话', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('city', 'require', '请选择城市', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('address', 'require', '请输入详细地址', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
        array('code', 'require', '请输入邮编', self::MUST_VALIDATE, 'regex', self::MODEL_BOTH),
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
}
